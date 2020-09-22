<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%labo}}`.
 */
class m200423_073922_create_labo_table extends Migration
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
        $this->createTable('labo_don_hang', [
            'id' => $this->primaryKey(),
            'bac_si_id' => $this->integer(),
            'phong_kham_don_hang_id' => $this->integer(),
            'ngay_nhan' => $this->integer(),
            'ngay_giao' => $this->integer(),
            'loai_phuc_hinh' => $this->json(),
            'loai_su' => $this->integer(),
            'yeu_cau' => $this->text(),
            'trang_thai' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createTable('labo_giai_doan', [
            'id' => $this->primaryKey(),
            'labo_don_hang_id' => $this->integer(),
            'note' => $this->text(),
            'giai_doan' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createTable('labo_giai_doan_image', [
            'id' => $this->primaryKey(),
            'labo_giai_doan_id' => $this->integer(),
            'image' => $this->string(),
            'google_id' => $this->string(),
            'status' => $this->integer(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);

        $this->createTable('labo_feedback', [
            'id' => $this->primaryKey(),
            'labo_giai_doan_id' => $this->integer(),
            'content' => $this->text(),
            'status' => $this->integer(),
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
        $this->dropTable('{{%labo}}');
    }
}
