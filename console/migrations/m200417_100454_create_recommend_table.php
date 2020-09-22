<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%recommend}}`.
 */
class m200417_100454_create_recommend_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $this->dropTable('recommend');

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('recommend', [
            'id' => $this->primaryKey(),
            'gioi_tinh' => $this->string(),
            'nhom_tuoi' => $this->json(),
            'bo_cuc' => $this->json(),
            'tinh_trang_rang' => $this->json(),
            'mong_muon' => $this->json(),
            'phong_cach' => $this->json(),
            'giai_phap' => $this->json(),
            'san_pham' => $this->json(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%recommend}}');
    }
}
