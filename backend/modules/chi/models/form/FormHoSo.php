<?php

namespace backend\modules\chi\models\form;

use common\commands\DeleteImageCommand;
use common\helpers\MyHelper;
use Yii;
use yii\base\Exception;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class FormHoSo extends \yii\base\Model {
	public $i;
	public $files;
	private $listExtensions = [
		'jpg',
		'jpeg',
		'png',
		'docx',
		'pptm',
		'doc',
		'ppt',
		'xls',
		'xlsx',
		'rar',
		'zip',
		'pdf'
	];
	private $maxSize = 50 * 1024 * 1024;
	public $fileUploadSuccess = [];
	public $fileUploadFail = [];

	public $image_base64;

	public static $iconSize = [ 32, 64, 128, 256 ];
	public static $iconByExtension = [
		'doc'  => 'word',
		'docx' => 'word',
		'xls'  => 'excel',
		'xlsx' => 'excel',
		'ppt'  => 'powerpoint',
		'pptx' => 'powerpoint',
		'zip'  => 'zip',
		'rar'  => 'zip',
		'pdf'  => 'pdf',
		'txt'  => 'txt',
	];

	public function behaviors() {
		return [
			[
				'class'      => AttributeBehavior::class,
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_VALIDATE => [ 'files' ]
				],
				'value'      => function () {
					$files = [];
					if ( is_array( $this->i ) ) {
						foreach ( $this->i as $i ) {
							$a           = UploadedFile::getInstance( $this, 'files[' . $i . ']' );
							$files[ $i ] = $a;
						}
					}

					return $files;
				}
			],
		];
	}

	public function rules() {
		return [
			[
				'files',
				'each',
				'rule' =>
					[
						'file',
						'extensions'     => $this->listExtensions,
						'maxSize'        => $this->maxSize,
						'wrongExtension' => 'Chỉ chấp nhận định dạng: {extensions}{file}'
					]
			],
			[ 'i', 'each', 'rule' => [ 'safe' ] ],
		];
	}

	public function attributeLabels() {
		return [
			'files' => \Yii::t( 'backend', 'Files' )
		];
	}

	public function getFilesError() {
		if ( $this->hasErrors( 'files' ) ) {
			$errors = [];
			foreach ( $this->files as $i => $file ) {
				if ( ! in_array( $file->extension, $this->listExtensions ) ) {
					Yii::error( $file->extension . 'DEBUGGING SERVER' );
					$errors[ $i ] = [
						'Chỉ chấp nhận định dạng: ' . implode( ', ', $this->listExtensions ) . __FUNCTION__
					];
					continue;
				}
				if ( $file->size > $this->maxSize ) {
					$errors[ $i ] = [
						'Kích thước tối đa: ' . $this->maxSize
					];
				}
			}

			return $errors;
		}
	}

	public function saveFiles( $url = null ) {
		if ( ! $this->hasErrors() ) {
			if ( is_array( $this->files ) && count( $this->files ) > 0 ) {
				if ( $url == null ) {
					$url = \Yii::getAlias( '@backend/web' ) . '/uploads/tmp/';
				}
				foreach ( $this->files as $i => $file ) {
					if ( $file == null ) {
						continue;
					}
					$urlFile = $this->saveFile( $i, $url );
					if ( $urlFile != null ) {
						$this->fileUploadSuccess[ $i ] = $urlFile;
					} else {
						$this->fileUploadFail[] = $i;
					}
				}
			}
		}
	}

	private function saveFile( $i = null, $url = null ) {
		if ( ! $this->hasErrors() ) {
			if ( $i === null || ! array_key_exists( $i, $this->files ) ) {
				return null;
			}
			try {
				if ( $url == null ) {
					$url = \Yii::getAlias( '@backend/web' ) . '/uploads/tmp/';
				}
				$name = MyHelper::createAlias( $this->files[ $i ]->baseName ) . '-' . MyHelper::randomString( 5 ) . '-' . time() . '.' . $this->files[ $i ]->extension;

				if ( $this->files[ $i ]->saveAs( $url . $name ) ) {
					return $name;
				} else {
					return null;
				}
			} catch ( Exception $ex ) {
				return null;
			}
		}
	}

	public function savePasteImage( $url = null ) {
		foreach ( $this->image_base64 as $data ) {
			if ( preg_match( '/^data:image\/(\w+);base64,/', $data, $type ) ) {
				$data = substr( $data, strpos( $data, ',' ) + 1 );
				$type = strtolower( $type[1] ); // jpg, png, gif

				if ( ! in_array( $type, [ 'jpg', 'jpeg', 'gif', 'png' ] ) ) {
					throw new \Exception( 'invalid image type' );
				}

				$data = base64_decode( $data );
				$file = uniqid() . "." . $type;
				if ( ! is_dir( $url ) ) {
					mkdir( $url, 0755, true );
				}
				$file_path = $url . $file;
				if ( $data === false ) {
					throw new \Exception( 'base64_decode failed' );
				}
				file_put_contents( $file_path, $data );
				$this->fileUploadSuccess[] = $file;
			}
		}
	}

	public function deleteFiles( $getAlias = '@backend/web', $alias = '/uploads/tmp/' ) {
		if ( ! $this->hasErrors() ) {
			if ( is_array( $this->fileUploadSuccess ) && count( $this->fileUploadSuccess ) > 0 ) {
				foreach ( $this->fileUploadSuccess as $i => $file ) {
					Yii::$app->commandBus->handle( new DeleteImageCommand( [
						'getAlias' => $getAlias,
						'alias'    => $alias,
						'image'    => $file,
					] ) );
				}
			}

			return true;
		}
	}

	public static function getUrlIconByExt( $ext = null, $size = null ) {
		if ( $size == null ) {
			$size = 128;
		}
		$file = array_key_exists( $ext, self::$iconByExtension ) ? self::$iconByExtension[ $ext ] : 'file';
		$url  = '/images/icons/' . $size . 'x' . $size . '/' . $file . '.png';
		if ( file_exists( Yii::getAlias( '@backend/web' ) . $url ) ) {
			return $url;
		} else {
			return '/images/icons/' . $size . 'x' . $size . '/file.png';
		}
	}
}
