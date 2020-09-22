<?php

use yii\db\Migration;

/**
 * Class m200306_073632_create_permission_for_de_xuat_chi
 */
class m200306_073632_create_permission_for_de_xuat_chi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $this->insert('rbac_auth_item', [
                'name' => 'chiNhom-chiLoad-nhom-chi-by-danh-muc',
                'type' => 2,
                'description' => 'Backend - Chi - Load nhóm chi theo danh mục chi'
            ]);
        } catch (\yii\db\Exception $ex) {
        }
        try {
            $this->insert('rbac_auth_item', [
                'name' => 'chiKhoan-chiLoad-khoan-chi-by-nhom-chi',
                'type' => 2,
                'description' => 'Backend - Chi - Load khoản chi theo nhóm chi'
            ]);
        } catch (\yii\db\Exception $ex) {
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200306_073632_create_permission_for_de_xuat_chi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200306_073632_create_permission_for_de_xuat_chi cannot be reverted.\n";

        return false;
    }
    */
}
