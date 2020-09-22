<?php

use yii\db\Migration;

/**
 * Class m200520_082856_myauris_analytics_add_field_event_name
 */
class m200520_082856_myauris_analytics_add_field_event_name extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $get_fields = Yii::$app->db->getTableSchema('myauris_analytics_log')->columns;
        if (!array_key_exists('event_name', $get_fields)) {
            $this->addColumn('myauris_analytics_log', 'event_name', $this->string(255)->null()->defaultValue('')->after('call_url')->comment('event on website myauris'));
        }
        if(!array_key_exists('event_url', $get_fields)){
            $this->addColumn('myauris_analytics_log', 'event_url', $this->string(255)->null()->defaultValue('')->after('call_url')->comment('url when fire event'));
        }
        if (array_key_exists('call_url', $get_fields)) {
            $this->execute("UPDATE myauris_analytics_log SET event_url=call_url");
            $this->execute("UPDATE myauris_analytics_log SET event_name='call' WHERE event_url IS NOT null AND event_url<>''");
            $this->dropColumn('myauris_analytics_log', 'call_url');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200520_082856_myauris_analytics_add_field_event_name cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200520_082856_myauris_analytics_add_field_event_name cannot be reverted.\n";

        return false;
    }
    */
}
