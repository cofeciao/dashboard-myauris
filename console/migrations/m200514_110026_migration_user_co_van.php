<?php

use yii\db\Migration;

/**
 * Class m200514_110026_migration_user_co_van
 */
class m200514_110026_migration_user_co_van extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $check_user_covan = Yii::$app->db->createCommand("SELECT * FROM rbac_auth_item WHERE name='user_covan' AND type='1'")->queryOne();
        if ($check_user_covan === false) {
            $this->insert('rbac_auth_item', [
                'name' => 'user_covan',
                'type' => 1,
                'description' => 'Cố vấn từ xa'
            ]);
        }
        $check_role_covan = Yii::$app->db->createCommand("SELECT * FROM rbac_auth_item_child WHERE (parent='user_administrator' AND child='user_covan') OR (parent='user_covan' AND child='user_manager_online') OR (parent='user_covan' AND child='user_manager_chay_ads')")->queryAll();
        $check_admin = false;
        $check_online = false;
        $check_ads = false;
        foreach ($check_role_covan as $role) {
            if ($role['parent'] == 'user_administrator' && $role['child'] == 'user_covan') $check_admin = true;
            if ($role['parent'] == 'user_covan' && $role['child'] == 'user_manager_online') $check_online = true;
            if ($role['parent'] == 'user_covan' && $role['child'] == 'user_manager_chay_ads') $check_ads = true;
        }
        if ($check_admin === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_administrator',
                'child' => 'user_covan'
            ]);
        }
        if ($check_online === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_covan',
                'child' => 'user_manager_online'
            ]);
        }
        if ($check_ads === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_covan',
                'child' => 'user_manager_chay_ads'
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200514_110026_migration_user_co_van cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200514_110026_migration_user_co_van cannot be reverted.\n";

        return false;
    }
    */
}
