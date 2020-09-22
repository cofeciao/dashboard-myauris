<?php

use yii\db\Migration;

/**
 * Class m200229_044628_create_role_test
 */
class m200229_044628_create_role_test extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('rbac_auth_item', [
            'name' => 'user_test',
            'type' => 1,
            'description' => 'User Test'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_administrator',
            'child' => 'user_test'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_test',
            'child' => 'loginToBackend'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_test',
            'child' => 'user_users'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200229_044628_create_role_test cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200229_044628_create_role_test cannot be reverted.\n";

        return false;
    }
    */
}
