<?php

use yii\db\Migration;

/**
 * Class m200306_103757_insert_permission_notification_dexuatchi_user_keToan5
 */
class m200306_103757_insert_permission_notification_dexuatchi_user_keToan5 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200306_103757_insert_permission_notification_dexuatchi_user_keToan5 cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        try {
            $this->insert('rbac_auth_item', [
                'name' => 'chiDanh-muc-chi',
                'type' => 2,
                'description' => 'Backend - Chi - Danh mục chi'
            ]);
        } catch (\yii\db\Exception $ex) {
            try {
                $this->insert('rbac_auth_item', [
                    'name' => 'chiKhoan-chi',
                    'type' => 2,
                    'description' => 'Backend - Chi - Khoản chi'
                ]);
            } catch (\yii\db\Exception $ex) {
            }
            try {
                $this->insert('rbac_auth_item', [
                    'name' => 'chiNhom-chi',
                    'type' => 2,
                    'description' => 'Backend - Chi - Nhóm chi'
                ]);
            } catch (\yii\db\Exception $ex) {
            }
        }
        try {
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_ke_toan',
                'child' => 'chiDanh-muc-chi'
            ]);
        } catch (Exception $e) {

        }
        try {
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_ke_toan',
                'child' => 'chiNhom-chi'
            ]);
        } catch (Exception $e) {

        }
        try {
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_ke_toan',
                'child' => 'chiKhoan-chi'
            ]);
        } catch (Exception $e) {

        }
    }

    public function down()
    {
        echo "m200306_103757_insert_permission_notification_dexuatchi_user_keToan5 cannot be reverted.\n";

        return false;

    }
}
