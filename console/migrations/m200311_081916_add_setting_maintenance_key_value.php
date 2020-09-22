<?php

use yii\db\Migration;

/**
 * Class m200311_081916_add_setting_maintenance_key_value
 */
class m200311_081916_add_setting_maintenance_key_value extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('setting', [
            'param' => 'Bảo trì hệ thống',
            'key_value' => 'system_maintenance',
            'value' => 0
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200311_081916_add_setting_maintenance_key_value cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200311_081916_add_setting_maintenance_key_value cannot be reverted.\n";

        return false;
    }
    */
}
