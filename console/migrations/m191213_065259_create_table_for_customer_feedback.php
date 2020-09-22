<?php

use yii\db\Migration;

/**
 * Class m191213_065259_create_table_for_customer_feedback
 */
class m191213_065259_create_table_for_customer_feedback extends Migration
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
        $this->createTable('customer_token', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11)->notNull(),
            'token' => $this->string(255)->notNull(),
            'type' => $this->integer()->notNull()->comment('Loại đánh giá'),
            'status' => $this->boolean()->null()->defaultValue(0)->comment('Trạng thái. 0 - chưa sử dụng, 1 - đã sử dụng'),
            'expired_at' => $this->integer(11)->null()->comment('null = forever'),
            'created_at' => $this->integer(11)->null(),
        ], $tableOptions);
        $this->createTable('customer_feedback', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11)->notNull(),
            'token_id' => $this->integer(11)->null(),
            'feedback' => $this->text()->null(),
            'status' => $this->boolean()->null()->defaultValue(0),
            'created_at' => $this->integer(11)->null()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('customer_token');
        $this->dropTable('customer_feedback');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191213_065259_create_table_for_customer_feedback cannot be reverted.\n";

        return false;
    }
    */
}
