<?php

use yii\db\Migration;

/**
 * Class m200303_023336_create_table_thuchi_tien_do_va_thuchi_tieu_chi
 */
class m200303_023336_create_table_thuchi_tien_do_va_thuchi_tieu_chi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200303_023336_create_table_thuchi_tien_do_va_thuchi_tieu_chi cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        //https://github.com/yiisoft/yii2/issues/3335
        $this->createTable('thuchi_tieu_chi', [
            'id' => $this->primaryKey(),
            'id_de_xuat_chi' => $this->integer(11)->notNull(),
            'tieu_chi' => \yii\db\Schema::TYPE_STRING . '(255) NOT NULL',
            'nd_hoan_thanh' => $this->text(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
        $this->createTable('thuchi_tien_do', [
            'id' => $this->primaryKey(),
            'id_de_xuat_chi' => $this->integer(11)->notNull(),
            'time_tien_do' => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200303_023336_create_table_thuchi_tien_do_va_thuchi_tieu_chi cannot be reverted.\n";

        return false;
    }
}
