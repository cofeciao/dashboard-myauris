<?php

use yii\db\Migration;

/**
 * Class m200423_073642_add_field_remind_call_date_table_remind_call
 */
class m200423_073642_add_field_remind_call_date_table_remind_call extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dep365_customer_online_remind_call', 'remind_call_date', $this->integer()->null()->after('remind_call_time'));
        $this->execute("UPDATE dep365_customer_online_remind_call SET remind_call_date=UNIX_TIMESTAMP(FROM_UNIXTIME(remind_call_time, '%Y-%m-%d')) WHERE remind_call_time IS NOT null");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200423_073642_add_field_remind_call_date_table_remind_call cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200423_073642_add_field_remind_call_date_table_remind_call cannot be reverted.\n";

        return false;
    }
    */
}
