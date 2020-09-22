<?php

use yii\db\Migration;

/**
 * Class m200303_064740_create_table_dep365_notification_seen
 */
class m200303_064740_create_table_dep365_notification_seen extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dep365_notification', 'for_who', $this->string(50)->null()->defaultValue('everyone')->comment('Notification for who'));

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('dep365_notification_seen', [
            'id' => $this->primaryKey(),
            'notification_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11)->null()
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200303_064740_create_table_dep365_notification_seen cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200303_064740_create_table_dep365_notification_seen cannot be reverted.\n";

        return false;
    }
    */
}
