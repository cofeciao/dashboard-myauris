<?php

use yii\db\Migration;

/**
 * Class m200304_042345_alter_table_dep365_notification_seen
 */
class m200304_042345_alter_table_dep365_notification_seen extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('dep365_notification_seen');
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('dep365_notification_seen', [
            'notification_id' => $this->integer(11),
            'user_id' => $this->integer(11),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('dep365_notification_seen');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200304_042345_alter_table_dep365_notification_seen cannot be reverted.\n";

        return false;
    }
    */
}
