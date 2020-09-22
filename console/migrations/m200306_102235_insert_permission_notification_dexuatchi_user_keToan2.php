<?php

use yii\db\Migration;

/**
 * Class m200306_102235_insert_permission_notification_dexuatchi_user_keToan2
 */
class m200306_102235_insert_permission_notification_dexuatchi_user_keToan2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_ke_toan',
                'child' => 'generalNotification'
            ]);

        } catch (Exception $e) {

        }
        try {
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_ke_toan',
                'child' => 'chiDe-xuat-chi'
            ]);
        } catch (Exception $e) {

        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200306_102235_insert_permission_notification_dexuatchi_user_keToan2 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200306_102235_insert_permission_notification_dexuatchi_user_keToan2 cannot be reverted.\n";

        return false;
    }
    */
}
