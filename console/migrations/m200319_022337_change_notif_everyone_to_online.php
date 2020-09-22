<?php

use yii\db\Migration;

/**
 * Class m200319_022337_change_notif_everyone_to_online
 */
class m200319_022337_change_notif_everyone_to_online extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("UPDATE dep365_notification SET for_who='online' WHERE for_who='everyone'");
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_nhanvien_online',
            'child' => 'generalNotification'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200319_022337_change_notif_everyone_to_online cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200319_022337_change_notif_everyone_to_online cannot be reverted.\n";

        return false;
    }
    */
}
