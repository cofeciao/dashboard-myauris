<?php

namespace backend\modules\setting\models;

use backend\modules\setting\models\query\Dep365CoSoQuery;
use common\models\UserProfile;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "dep365_co_so".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $mota
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property int $created_by
 * @property int $updated_by
 */
class Dep365CoSo extends \yii\db\ActiveRecord {
	const STATUS_PUBLISHED = 1;
	const STATUS_DRAFF = 0;
	const HEAD_OFFICE = [ 'head-office' => "HEAD office", 'iway' => "IWAY" ];


	/**
	 * {@inheritdoc}
	 */
	public static function tableName() {
		return 'dep365_co_so';
	}

	public static function find() {
		return new Dep365CoSoQuery( get_called_class() );
	}

	public function behaviors() {
		return [
			'slug'      => [
				'class'         => SluggableBehavior::class,
				'attribute'     => 'name',
				'slugAttribute' => 'slug',
				'immutable'     => true, //only create 1
				'ensureUnique'  => true, //
			],
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
		];
	}


	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			[ [ 'name', 'slug', 'address' ], 'required' ],
			[ [ 'mota' ], 'string' ],
			[
				[ 'status', 'render_virtual_booking', 'created_at', 'updated_at', 'created_by', 'updated_by' ],
				'integer'
			],
			[ [ 'name', 'slug', 'title', 'email', 'hotline', 'address' ], 'string', 'max' => 255 ],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'id'                     => Yii::t( 'backend', 'ID' ),
			'name'                   => Yii::t( 'backend', 'Tên cơ sở' ),
			'slug'                   => Yii::t( 'backend', 'Slug' ),
			'title'                  => 'Tên',
			'email'                  => 'Email',
			'hotline'                => 'Hotline',
			'mota'                   => Yii::t( 'backend', 'Mota' ),
			'status'                 => Yii::t( 'backend', 'Status' ),
			'render_virtual_booking' => Yii::t( 'backend', 'Tự động tạo lịch ảo' ),
			'created_at'             => Yii::t( 'backend', 'Created At' ),
			'updated_at'             => Yii::t( 'backend', 'Updated At' ),
			'created_by'             => Yii::t( 'backend', 'Created By' ),
			'updated_by'             => Yii::t( 'backend', 'Updated By' ),
		];
	}

	public static function getCoSo() {
		$cache = Yii::$app->cache;
		$key   = 'redis-co-so';
		$data  = $cache->get( $key );
		if ( $data === false ) {
			$data = self::find()->published()->all();
			$cache->set( $key, $data );
		}

		return $data;
	}

	public static function getCoSoArray() {
		$list   = self::getCoSo();
		$return = [];
		foreach ( $list as $coso ) {
			$return[ $coso->id ] = $coso->name;
		}

		return $return;
	}

	public static function getCoSoArrayBy( $field, $array_merge = [] ) {
		$list   = self::getCoSo();
		$return = [];

		foreach ( $list as $coso ) {
			$return[ $coso[ $field ] ] = $coso->name;
		}
		if ( ! empty( $array_merge ) ) {
			foreach ( $array_merge as $key => $value ) {
				$return[ $key ] = $value;
			}
		}

		return $return;
	}

	public function getCoSoOne( $id ) {
		$cache = Yii::$app->cache;
		$key   = 'redis-co-so-' . $id;
		$data  = $cache->get( $key );
		if ( $data === false ) {
			$data = self::find()->where( [ 'id' => $id ] )->published()->one();
			$cache->set( $key, $data );
		}

		return $data;
	}

	public function getUserCreatedBy( $id ) {
		if ( $id == null ) {
			$id = 2;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}

	public function getUserUpdatedBy( $id ) {
		if ( $id == null ) {
			$id = 2;
		}
		$user = UserProfile::find()->where( [ 'user_id' => $id ] )->one();

		return $user;
	}

	public static function getById( $id ) {
		if ( $id == null ) {
			return null;
		}

		return self::find()->where( [ 'id' => $id ] )->published()->one();
	}
}
