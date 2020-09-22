<?php

use yii\db\Migration;

/**
 * Class m191227_042719_update_table_facebook_user_profile
 */
class m191227_042719_update_table_facebook_user_profile extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dep365_customer_online_fanpage', 'id_facebook', $this->string());
        $this->addColumn('user_profile', 'label_pancake', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191227_042719_update_table_facebook_user_profile cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191227_042719_update_table_facebook_user_profile cannot be reverted.\n";

        return false;
    }
    */
}
