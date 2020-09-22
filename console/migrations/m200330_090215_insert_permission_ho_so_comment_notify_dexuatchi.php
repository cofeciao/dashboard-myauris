<?php

use yii\db\Migration;

/**
 * Class m200330_090215_insert_permission_ho_so_comment_notify_dexuatchi
 */
class m200330_090215_insert_permission_ho_so_comment_notify_dexuatchi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('rbac_auth_item', [
            'name'        => 'chiHo-so',
            'type'        => 2,
            'description' => 'Hồ sơ thu chi'
        ]);
        $this->insert('rbac_auth_item', [
            'name'        => 'chiComment',
            'type'        => 2,
            'description' => 'Comment Thu Chi'
        ]);
        $this->execute("insert into rbac_auth_item_child (parent, child) select distinct d.parent,'chiHo-so' from rbac_auth_item_child as d where parent not in (select parent from rbac_auth_item_child where child='chiHo-so')");
        $this->execute("insert into rbac_auth_item_child (parent, child) select distinct d.parent,'generalNotification' from rbac_auth_item_child as d where parent not in (select parent from rbac_auth_item_child where child='generalNotification')");
        $this->execute("insert into rbac_auth_item_child (parent, child) select distinct d.parent,'chiComment' from rbac_auth_item_child as d where parent not in (select parent from rbac_auth_item_child where child='chiComment')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200330_090215_insert_permission_ho_so_comment_notify_dexuatchi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200330_090215_insert_permission_ho_so_comment_notify_dexuatchi cannot be reverted.\n";

        return false;
    }
    */
}
