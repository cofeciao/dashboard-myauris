<?php

use yii\db\Migration;

/**
 * Class m200812_020358_add_column_type_table_dep365_send_sms
 */
class m200812_020358_add_column_type_table_dep365_send_sms extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* check column exists */
        $check_column = Yii::$app->db->getTableSchema('dep365_send_sms')->columns;
        if (!array_key_exists('column', $check_column)) {
            $this->addColumn('dep365_send_sms', 'type', $this->string(255)->null()->defaultValue('ccsms_vht'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200812_020358_add_column_type_table_dep365_send_sms cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200812_020358_add_column_type_table_dep365_send_sms cannot be reverted.\n";

        return false;
    }
    */
}
