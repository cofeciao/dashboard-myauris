<?php

use yii\db\Migration;

/**
 * Class m191216_083753_drop_column_status_send_sms
 */
class m191216_083753_drop_column_status_send_sms extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('customer_token', 'status_send_sms');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191216_083753_drop_column_status_send_sms cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191216_083753_drop_column_status_send_sms cannot be reverted.\n";

        return false;
    }
    */
}
