<?php

namespace backend\modules\user\models;

use backend\models\Test;
use common\helpers\MyHelper;
use MongoDB\Driver\Exception\ExecutionTimeoutException;
use PharIo\Manifest\InvalidApplicationNameException;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use yii\db\Exception;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $access_token
 * @property string $password_hash
 * @property string $oauth_client
 * @property string $oauth_client_user_id
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $logged_at
 * @property int $created_by
 * @property int $updated_by
 */
class User extends \common\models\User {
	public static $ids = [];
	public $manager;
	public $online;

	public $item_name;
	public $self_id;

	public function init() {
		$this->manager = Yii::$app->authManager;
	}

	public function rules() {
		$rules = parent::rules();

		return array_merge(
			$rules,
			[
				[ 'role_name', 'string' ],
				[ [ 'vpbx_acc', 'vpbx_pass' ], 'string' ]
			]
		);
	}

	public function behaviors() {
		$behaiviors = parent::behaviors();

		return array_merge(
			$behaiviors,
			[
				[
					'class'              => BlameableBehavior::class,
					'createdByAttribute' => 'created_by',
					'updatedByAttribute' => 'updated_by',
				],
				'timestamp' => [
					'class'                  => 'yii\behaviors\TimestampBehavior',
					'preserveNonEmptyValues' => true,
					'attributes'             => [
						ActiveRecord::EVENT_BEFORE_INSERT => [ 'created_at', 'updated_at' ],
						ActiveRecord::EVENT_BEFORE_UPDATE => [ 'updated_at' ],
					],
				],
			]
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'                   => Yii::t( 'backend', 'ID' ),
			'username'             => Yii::t( 'backend', 'Username' ),
			'fullname'             => Yii::t( 'backend', 'Họ tên' ),
			'auth_key'             => Yii::t( 'backend', 'Auth Key' ),
			'access_token'         => Yii::t( 'backend', 'Access Token' ),
			'password_hash'        => Yii::t( 'backend', 'Password Hash' ),
			'oauth_client'         => Yii::t( 'backend', 'Oauth Client' ),
			'oauth_client_user_id' => Yii::t( 'backend', 'Oauth Client User ID' ),
			'email'                => Yii::t( 'backend', 'Email' ),
			'phone'                => Yii::t( 'backend', 'Số điện thoại' ),
			'status'               => Yii::t( 'backend', 'Status' ),
			'created_at'           => Yii::t( 'backend', 'Created At' ),
			'updated_at'           => Yii::t( 'backend', 'Updated At' ),
			'logged_at'            => Yii::t( 'backend', 'Đăng nhập lần cuối' ),
			'created_by'           => Yii::t( 'backend', 'Created By' ),
			'updated_by'           => Yii::t( 'backend', 'Updated By' ),
			'role_name'            => 'Quyền',
			'idpancake'            => 'Id Pancake',
			'online'               => 'Trực tuyến',
			'permission_coso'      => 'Cơ sở',
			'vpbx_acc'             => 'Call account',
			'vpbx_pass'            => 'Password',
			'team'                 => 'Team',
			'alias'                => 'Alias'
		];
	}

	public function afterDelete() {
		$coso  = $this->primaryKey;
		$cache = Yii::$app->cache;
		$keys  = [ 'get-user-info-noti-' . $this->id, 'get-co-so-' . $coso ];
		foreach ( $keys as $key ) {
			$cache->delete( $key );
		}
		parent::afterDelete(); // TODO: Change the autogenerated stub
	}

	public function afterSave( $insert, $changedAttributes ) {
		$coso  = $this->primaryKey;
		$cache = Yii::$app->cache;
		$keys  = [ 'get-user-info-noti-' . $this->id, 'get-co-so-' . $coso ];
		foreach ( $keys as $key ) {
			$cache->delete( $key );
		}
		parent::afterSave( $insert, $changedAttributes ); // TODO: Change the autogenerated stub
	}

	/*
	 * Trả về tên Role của user.
	 */

	public function getRoleName( $id ) {
		$cache = Yii::$app->cache;
		$key   = 'rbac-' . $id;

		$assignment = $cache->get( $key );

		if ( $assignment == false ) {
			$assignment = array_keys( $this->manager->getAssignments( $id ) );
			$assignment = $assignment != null ? $assignment[0] : User::USER_USERS;

			$cache->set( $key, $assignment );
		}


		return $assignment;
	}

	public static function getUserInfo( $id ) {
		$cache = Yii::$app->cache;
		$key   = 'get-user-info-noti-' . $id;
		$data  = $cache->get( $key );
		$time  = strtotime( date( 'd-m-Y' ) . '+1day' ) - time();
		if ( $data == false ) {
			$query = User::find()
			             ->select( 'user.id, user.username, user_profile.fullname, rbac_auth_assignment.item_name, user_sub_role.role, user.email' )
			             ->joinWith( [ 'userProfile', 'subroleHasOne' ] )
			             ->leftJoin( 'rbac_auth_assignment', 'rbac_auth_assignment.user_id=' . User::tableName() . '.id' )
			             ->where( [ 'user.id' => $id ] );
			$data  = $query->one();
			$cache->set( $key, $data, $time );
		}

		return $data;
	}


	public function getCoso( $id ) {
		$cache = Yii::$app->cache;
		$key   = 'get-co-so-' . $id;

		$user = $cache->get( $key );

		if ( $user == false ) {
			$user = User::find()->where( [ 'id' => $id ] )->one();
			$cache->set( $key, $user, 7 * 24 * 3600 );
		}

		if ( isset( $user ) ) {
			return $user;
		}
	}

	/*
	 * Kiểm tra parent - child
	 * $role đưa vào cần kiểm tra
	 * $roleUser role của người đang kiểm tra
	 */
	public function checkParent( string $role, string $roleUser ): bool {
		if ( $roleUser == 'user_develop' ) {
			return true;
		}
		$result = $this->manager->getChildRoles( $roleUser );
		foreach ( $result as $roleName ) {
			if ( $role == $roleName->name ) {
				return true;
			}
		}

		return false;
	}

	/*
	 * Lấy toàn bộ người dùng là do người đó tạo ra hoặc người được tạo ra tạo ra.
	 */
	public static function getChild( $id ) {
		$user = User::find()->where( [ 'created_by' => $id ] )->all();
		foreach ( $user as $item ) {
			self::$ids[] = $item->created_by;
			self::getChild( $item->id );
		}

		return self::$ids;
	}

	public static function getPermissionUser( $id ) {
		if ( $id == null ) {
			return false;
		}
		$cache = Yii::$app->cache;
		$key   = 'get-permission-user-' . $id;

		$user = $cache->get( $key );

		if ( $user == false ) {
			$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();
			$cache->set( $key, $user );
		}

		return $user;
	}

	public function getUserCreatedBy( $id ) {
		if ( $id == null ) {
			return false;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}

	public function getUserUpdatedBy( $id ) {
		if ( $id == null ) {
			return false;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}

	/**
	 * Get list user id profile
	 * @return array|ActiveRecord[] the query results.
	 */
	public static function getListUserIdProfile( $return = '' ) {
		$cache = Yii::$app->cache;
		$key   = 'get-list-user-id-profile-dxc';

		$users = $cache->get( $key );

		if ( empty( $users ) ) {
			$users = User::find()->select( 'user.id, user.status, user_profile.fullname' )
			             ->joinWith( [ 'userProfile' ] )
			             ->where( [
				             'IN',
				             'user.status',
				             [ User::STATUS_ACTIVE ]
			             ] )->andWhere( 'user_profile.fullname is not null' )->andWhere( [
					'<>',
					'user_profile.fullname',
					''
				] )->join( 'LEFT JOIN', 'rbac_auth_assignment', 'rbac_auth_assignment.user_id = id' );
				$users = $users->all();

			$cache->set( $key, $users );
		}

		return $users;
	}

	/**
	 * Get phong ban by user_id
	 * @throws
	 * @return ActiveQuery object
	 */
	public function getPhongbanHasMany() {
		return $this->hasMany( PhongBan::class, [ 'id' => 'phong_ban_id' ] )->viaTable( 'phong_ban_user_hasmany', [ 'user_id' => 'id' ] );
	}


}
