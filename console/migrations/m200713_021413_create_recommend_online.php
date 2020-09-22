<?php

use yii\db\Migration;

/**
 * Class m200713_021413_create_recommend_online
 */
class m200713_021413_create_recommend_online extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('recommend_online', [
            'id' => $this->primaryKey(),
            'gioi_tinh' => $this->json(),
            'nhom_tuoi' => $this->json(),
            'tinh_trang_rang' => $this->json(),
            'khach_quan_tam' => $this->json(),
            'san_pham' => $this->json(),
            'tin_nhan' => $this->text()->null(),
            'hinh_anh' => $this->text()->null(),
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
        echo "m200713_021413_create_recommend_online cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200713_021413_create_recommend_online cannot be reverted.\n";

        return false;
    }
    */
}
