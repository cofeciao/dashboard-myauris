<?php

use yii\db\Migration;

/**
 * Class m190615_034930_insert_rbac_auth
 */
class m190615_034930_insert_rbac_auth extends Migration
{

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->insert('rbac_auth_item', [
            'name' => 'user_api',
            'type' => 1,
            'description' => 'User Api',
        ]);

        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_administrator',
            'child' => 'user_api',
        ]);
    }

    public function down()
    {
        echo "m190615_034930_insert_rbac_auth cannot be reverted.\n";
        return false;
    }
}
