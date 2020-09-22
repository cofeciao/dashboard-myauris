<?php

use yii\db\Migration;

/**
 * Class m200317_045149_create_role_nhan_su
 */
class m200317_045149_create_role_nhan_su extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('rbac_auth_item', [
            'name' => 'user_manager_hr',
            'type' => 1,
            'description' => 'Trưởng phòng nhân sự'
        ]);
        $this->insert('rbac_auth_item', [
            'name' => 'user_hr',
            'type' => 1,
            'description' => 'Nhân sự'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_hr',
            'child' => 'loginToBackend'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_hr',
            'child' => 'user_hr'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_administrator',
            'child' => 'user_manager_hr'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200317_045149_create_role_nhan_su cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200317_045149_create_role_nhan_su cannot be reverted.\n";

        return false;
    }
    */
}
