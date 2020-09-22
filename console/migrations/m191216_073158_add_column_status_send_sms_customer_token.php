<?php

use yii\db\Migration;

/**
 * Class m191216_073158_add_column_status_send_sms_customer_token
 */
class m191216_073158_add_column_status_send_sms_customer_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('customer_token', 'status_send_sms', $this->boolean()->null()->defaultValue(0)->after('status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191216_073158_add_column_status_send_sms_customer_token cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191216_073158_add_column_status_send_sms_customer_token cannot be reverted.\n";

        return false;
    }
    */
}
