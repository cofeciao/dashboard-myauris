<?php

use yii\db\Migration;

/**
 * Class m200609_043557_cap_quyen_user_tro_thu
 */
class m200609_043557_cap_quyen_user_tro_thu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('rbac_auth_item_child', [
            'parent' => 'user_trothu',
            'child' => 'loginToBackend'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200609_043557_cap_quyen_user_tro_thu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200609_043557_cap_quyen_user_tro_thu cannot be reverted.\n";

        return false;
    }
    */
}
