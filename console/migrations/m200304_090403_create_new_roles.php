<?php

use yii\db\Migration;

/**
 * Class m200304_090403_create_new_roles
 */
class m200304_090403_create_new_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('rbac_auth_item', [
            'name' => 'user_manager',
            'type' => 1,
            'description' => 'User Manager'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_le_tan'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_online'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_direct_sale'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_chay_ads'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_bac_si'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_ke_toan'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_bien_tap'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_kiem_soat'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'user_manager_seo'
        ]);

        try{
            $this->insert('rbac_auth_item', [
                'name' => 'chiDe-xuat-chi',
                'type' => 2,
                'description' => 'Backend - Chi - Đề xuất chi'
            ]);
        }catch(Exception $e){

        }

        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_bac_si',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_bien_tap',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_chay_ads',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_direct_sale',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_ke_toan',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_kiem_soat',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_le_tan',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_online',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_seo',
            'child' => 'chiDe-xuat-chi'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'chiDe-xuat-chi'
        ]);

        $this->insert('rbac_auth_item', [
            'name' => 'generalNotification',
            'type' => 2,
            'description' => 'Backend - General - Notification'
        ]);

        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_bac_si',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_bien_tap',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_chay_ads',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_direct_sale',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_ke_toan',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_kiem_soat',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_le_tan',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_online',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager_seo',
            'child' => 'generalNotification'
        ]);
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_manager',
            'child' => 'generalNotification'
        ]);

        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_administrator',
            'child' => 'user_manager'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200304_090403_create_new_roles cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200304_090403_create_new_roles cannot be reverted.\n";

        return false;
    }
    */
}
