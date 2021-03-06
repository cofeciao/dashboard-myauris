<?php

use yii\db\Migration;

/**
 * Class m200224_025156_create_table_thuchi_khoan_chi
 */
class m200224_025156_create_table_thuchi_khoan_chi extends Migration
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
        $this->createTable("thuchi_khoan_chi", [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'category' => $this->integer(11)->notNull(),
            'code' => $this->string(50)->notNull(),
            'description' => $this->string(500)->null(),
            'status' => $this->boolean()->null()->defaultValue(1),
            'created_at' => $this->integer()->null(),
            'created_by' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
            'updated_by' => $this->integer()->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable("thuchi_khoan_chi");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200224_025156_create_table_thuchi_khoan_chi cannot be reverted.\n";

        return false;
    }
    */
}
