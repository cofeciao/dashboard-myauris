<?php

use yii\db\Migration;

/**
 * Class m200306_105123_insert_permission_notification_dexuatchi_user_keToan6
 */
class m200306_105123_insert_permission_notification_dexuatchi_user_keToan6 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        try {
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_ke_toan',
                'child' => 'chi'
            ]);
        } catch (Exception $e) {

        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200306_105123_insert_permission_notification_dexuatchi_user_keToan6 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200306_105123_insert_permission_notification_dexuatchi_user_keToan6 cannot be reverted.\n";

        return false;
    }
    */
}
