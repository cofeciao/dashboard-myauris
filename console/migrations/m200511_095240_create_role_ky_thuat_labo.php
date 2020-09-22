<?php

use yii\db\Migration;

/**
 * Class m200511_095240_create_role_ky_thuat_labo
 */
class m200511_095240_create_role_ky_thuat_labo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('rbac_auth_item', [
            'name' => 'user_ky_thuat_labo',
            'type' => 1,
            'description' => 'User Ky Thuat Labo'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_administrator',
            'child' => 'user_ky_thuat_labo'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_ky_thuat_labo',
            'child' => 'loginToBackend'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_ky_thuat_labo',
            'child' => 'user_users'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200511_095240_create_role_ky_thuat_labo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200511_095240_create_role_ky_thuat_labo cannot be reverted.\n";

        return false;
    }
    */
}
