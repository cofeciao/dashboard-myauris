<?php

use yii\db\Migration;

/**
 * Class m200517_133252_migrate_role_quanly_phongkham
 */
class m200517_133252_migrate_role_quanly_phongkham extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $check_quanly_phongkham = Yii::$app->db->createCommand("SELECT * FROM rbac_auth_item WHERE name='user_quanly_phongkham' AND type='1'")->queryOne();
        if ($check_quanly_phongkham === false) {
            $this->insert('rbac_auth_item', [
                'name' => 'user_quanly_phongkham',
                'type' => 1,
                'description' => 'Quản lý phòng khám'
            ]);
        }
        $check_role_quanly_phongkham = Yii::$app->db->createCommand("SELECT * FROM rbac_auth_item_child WHERE 
            (parent='user_administrator' AND child='user_quanly_phongkham') OR 
            (parent='user_quanly_phongkham' AND child='user_manager_le_tan') OR 
            (parent='user_quanly_phongkham' AND child='user_phongkham') OR 
            (parent='user_quanly_phongkham' AND child='user_studio') OR 
            (parent='user_quanly_phongkham' AND child='user_chup_hinh') OR 
            (parent='user_quanly_phongkham' AND child='user_tk_nu_cuoi') OR 
            (parent='user_quanly_phongkham' AND child='user_manager_direct_sale') OR
            (parent='user_quanly_phongkham' AND child='user_bac_si') OR
            (parent='user_quanly_phongkham' AND child='user_trothu') OR
            (parent='user_quanly_phongkham' AND child='user_sale_rang') OR
            (parent='user_quanly_phongkham' AND child='userUser')
            ")->queryAll();
        $check_admin = false;
        $check_manager_le_tan = false;
        $check_phongkham = false;
        $check_studio = false;
        $check_chup_hinh = false;
        $check_tk_nu_cuoi = false;
        $check_manager_direct_sale = false;
        $check_bac_si = false;
        $check_trothu = false;
        $check_sale_rang = false;
        $check_user = false;
        foreach ($check_role_quanly_phongkham as $role) {
            if ($role['parent'] == 'user_administrator' && $role['child'] == 'user_quanly_phongkham') $check_admin = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_manager_le_tan') $check_manager_le_tan = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_phongkham') $check_phongkham = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_studio') $check_studio = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_chup_hinh') $check_chup_hinh = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_tk_nu_cuoi') $check_tk_nu_cuoi = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_manager_direct_sale') $check_manager_direct_sale = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_bac_si') $check_bac_si = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_trothu') $check_trothu = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'user_sale_rang') $check_sale_rang = true;
            if ($role['parent'] == 'user_quanly_phongkham' && $role['child'] == 'userUser') $check_user = true;
        }
        if ($check_admin === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_administrator',
                'child' => 'user_quanly_phongkham'
            ]);
        }
        if ($check_manager_le_tan === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_manager_le_tan'
            ]);
        }
        if ($check_phongkham === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_phongkham'
            ]);
        }
        if ($check_studio === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_studio'
            ]);
        }
        if ($check_chup_hinh === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_chup_hinh'
            ]);
        }
        if ($check_tk_nu_cuoi === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_tk_nu_cuoi'
            ]);
        }
        if ($check_manager_direct_sale === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_manager_direct_sale'
            ]);
        }
        if ($check_bac_si === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_bac_si'
            ]);
        }
        if ($check_trothu === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_trothu'
            ]);
        }
        if ($check_sale_rang === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'user_sale_rang'
            ]);
        }
        if ($check_user === false) {
            $this->insert("rbac_auth_item_child", [
                'parent' => 'user_quanly_phongkham',
                'child' => 'userUser'
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200517_133252_migrate_role_quanly_phongkham cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200517_133252_migrate_role_quanly_phongkham cannot be reverted.\n";

        return false;
    }
    */
}
