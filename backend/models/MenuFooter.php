<?php

namespace backend\models;

use Yii;
use yii\behaviors\SluggableBehavior;

/**
 * This is the model class for table "menu_footer".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $url_page
 * @property int $parent
 * @property int $status
 * @property string $create_at
 * @property string $update_at
 */
class MenuFooter extends \yii\db\ActiveRecord
{
    public $_cats = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_footer';
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => SluggableBehavior::class,
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true, //only create 1
                'ensureUnique' => true, //
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug', 'create_at'], 'required'],
            [['parent', 'status'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            ['name', 'string'],
            [['slug', 'url_page', 'url_page'], 'string', 'max' => 255],
        ];
    }

    public function getParent($parent = null, $level = '')
    {
        $data = MenuFooter::find()->where(['parent' => $parent])->all();

        $level .= '--';

        if ($data) {
            foreach ($data as $menu) {
                if ($menu->parent == null) {
                    $level = '';
                }
                $this->_cats[$menu->id] = $level . $menu->name;
                $this->getParent($menu->id, $level);
            }
        }

        return $this->_cats;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Tên',
            'slug' => 'Đường dẫn tĩnh',
            'url_page' => 'Link page',
            'parent' => 'Menu cha',
            'status' => 'Trạng thái',
            'create_at' => 'Ngày tạo',
            'update_at' => 'Ngày cập nhật',
        ];
    }
}
