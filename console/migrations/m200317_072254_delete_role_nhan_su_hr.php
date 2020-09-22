<?php

use yii\db\Migration;

/**
 * Class m200317_072254_delete_role_nhan_su_hr
 */
class m200317_072254_delete_role_nhan_su_hr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('rbac_auth_item', [
            'name' => 'user_manager_hr',
        ]);
        $this->delete('rbac_auth_item', [
            'name' => 'user_hr',
        ]);
        $this->delete('rbac_auth_item_child', [
            'parent' => ['user_manager_hr', 'user_hr'],
        ]);
        $this->delete('rbac_auth_item_child', [
            'child' => 'user_manager_hr'
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200317_072254_delete_role_nhan_su_hr cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200317_072254_delete_role_nhan_su_hr cannot be reverted.\n";

        return false;
    }
    */
}
