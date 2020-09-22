<?php

use yii\db\Migration;

/**
 * Class m191212_041906_create_roles_screen
 */
class m191212_041906_create_roles_screen extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('rbac_auth_item', [
            'name' => 'user_screen',
            'type' => 1,
            'description' => 'Screen Media',
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' =>  'user_administrator',
            'child' => 'user_screen',
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' =>  'user_develop',
            'child' => 'user_screen',
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' =>  'user_screen',
            'child' => 'loginToBackend',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191212_041906_create_roles_screen cannot be reverted.\n";

        return false;
    }
}
