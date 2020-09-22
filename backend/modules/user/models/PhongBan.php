<?php

namespace backend\modules\user\models;

use cornernote\linkall\LinkAllBehavior;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use common\models\UserProfile;
use backend\modules\user\models\query\PhongBanQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "phong_ban".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property int $status
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property string $alias
 */
class PhongBan extends \yii\db\ActiveRecord {
	const STATUS_DISABLED = 0;
	const STATUS_PUBLISHED = 1;

	public $roles;
	public $users;
	public $self_id;

	public static function tableName() {
		return 'phong_ban';
	}

	public function behaviors() {
		return [
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
			LinkAllBehavior::class
		];
	}

	public static function find() {
		return new PhongBanQuery( get_called_class() );
	}

	public function afterSave( $insert, $changedAttributes ) {
		$old_alias = $this->alias;
		$alias     = '';
		$parent    = self::getById( $this->parent );
		if ( $parent != null ) {
			$alias = $parent->alias;
		}
		$alias        .= '/' . $this->primaryKey;
		$change_alias = $old_alias != $alias;
		if ( $change_alias ) {
			$this->updateAttributes( [
				'alias' => $alias
			] );
		}
		$roles = [];
		if ( is_array( $this->roles ) ) {
			foreach ( $this->roles as $role ) {
				$role = RbacAuthItem_::getByName( $role );
				if ( $role != null ) {
					$roles[] = $role;
				}
			}
		}
		$users = [];
		if ( is_array( $this->users ) ) {
			foreach ( $this->users as $user ) {
				$user = User::getById( $user );
				if ( $user != null ) {
					$users[] = $user;
				}
			}
		}
		$this->linkAll( 'roleHasMany', $roles );
		$this->linkAll( 'userHasMany', $users );
		if ( $change_alias && $old_alias != null ) {
			Yii::$app->db->createCommand( "UPDATE " . self::tableName() . " SET alias=REPLACE(alias, '{$old_alias}/', '{$alias}/') WHERE alias LIKE '{$old_alias}/%'" )->execute();
		}
		parent::afterSave( $insert, $changedAttributes ); // TODO: Change the autogenerated stub
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'name' ], 'required' ],
			[ [ 'parent', 'status' ], 'integer' ],
			[ [ 'name' ], 'string', 'max' => 255 ],
			[ [ 'roles', 'users' ], 'safe' ],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'         => Yii::t( 'backend', 'ID' ),
			'name'       => Yii::t( 'backend', 'Name' ),
			'parent'     => Yii::t( 'backend', 'Parent' ),
			'status'     => Yii::t( 'backend', 'Status' ),
			'created_at' => Yii::t( 'backend', 'Created At' ),
			'created_by' => Yii::t( 'backend', 'Created By' ),
			'updated_at' => Yii::t( 'backend', 'Updated At' ),
			'updated_by' => Yii::t( 'backend', 'Updated By' ),
			'alias'      => Yii::t( 'backend', 'Alias' ),
		];
	}

	public function getRoleHasMany() {
		return $this->hasMany( RbacAuthItem_::class, [ 'name' => 'role' ] )
		            ->viaTable( 'phong_ban_role_hasmany', [ 'phong_ban_id' => 'id' ] );
	}

	public function getUserHasMany() {
		return $this->hasMany( User::class, [ 'id' => 'user_id' ] )
		            ->viaTable( 'phong_ban_user_hasmany', [ 'phong_ban_id' => 'id' ] );
	}

	public static function getById( $id ) {
		return self::find()->where( [ 'id' => $id ] )->one();
	}

	public static function getMenuPhongBan( $current = null, $parent = null, $prefix = '|-', $data = [], $phong_ban_key = null ) {
		if ( ! is_array( $data ) ) {
			$data = [];
		}
		$select = [
			self::tableName() . '.id AS self_id',
			self::tableName() . '.id',
			self::tableName() . '.name',
			self::tableName() . '.alias'
		];
		$rows   = self::find()->select( $select )->where( [ 'parent' => $parent ] )->all();
		foreach ( $rows as $row ) {
			if ( $row->id === $current ) {
				continue;
			}
			$data[] = [
				'id'      => $row->id,
				'self_id' => $row->self_id,
				'name'    => $prefix . $row->name,
				'alias'   => $row->alias
			];
			if ( self::find()->select( [ 'id', 'name' ] )->where( [ 'parent' => $row->id ] )->count() > 0 ) {
				$data = self::getMenuPhongBan( $current, $row->id, $prefix . '|-', $data, $phong_ban_key );
			}
		}

		return $data;
	}

	public function getUserCreatedBy( $id ) {
		if ( $id == null ) {
			return null;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}

	public function getUserUpdatedBy( $id ) {
		if ( $id == null ) {
			return null;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}


}