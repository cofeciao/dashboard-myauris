<?php

use yii\db\Migration;

/**
 * Class m200624_065324_create_table_app_myauris_group_san_pham
 */
class m200624_065324_create_table_app_myauris_group_san_pham extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('app_myauris_group_san_pham', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'list' => $this->json()->null(),
            'status' => $this->tinyInteger(1)->null()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200624_065324_create_table_app_myauris_group_san_pham cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200624_065324_create_table_app_myauris_group_san_pham cannot be reverted.\n";

        return false;
    }
    */
}
