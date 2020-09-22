<?php

use yii\db\Migration;

/**
 * Class m200330_084013_delete_rule_rbac
 */
class m200330_084013_delete_rule_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('rbac_auth_rule', ['name' => 'dataaurisDataauris-customerUpdate']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200330_084013_delete_rule_rbac cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200330_084013_delete_rule_rbac cannot be reverted.\n";

        return false;
    }
    */
}
