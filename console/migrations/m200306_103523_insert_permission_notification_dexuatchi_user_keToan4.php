<?php

use yii\db\Migration;

/**
 * Class m200306_103523_insert_permission_notification_dexuatchi_user_keToan4
 */
class m200306_103523_insert_permission_notification_dexuatchi_user_keToan4 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_ke_toan',
                'child' => 'chiDanh-muc-chi'
            ]);
        } catch (Exception $e) {

        } try {
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

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200306_103523_insert_permission_notification_dexuatchi_user_keToan4 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200306_103523_insert_permission_notification_dexuatchi_user_keToan4 cannot be reverted.\n";

        return false;
    }
    */
}
