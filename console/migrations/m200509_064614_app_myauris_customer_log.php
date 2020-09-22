<?php

use yii\db\Migration;

/**
 * Class m200509_064614_app_myauris_customer_log
 */
class m200509_064614_app_myauris_customer_log extends Migration
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
        $this->createTable('app_myauris_customer_log', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(),
            'tu_van' => $this->json(),
            'don_hang' => $this->json(),
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
        echo "m200509_064614_app_myauris_customer_log cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200509_064614_app_myauris_customer_log cannot be reverted.\n";

        return false;
    }
    */
}
