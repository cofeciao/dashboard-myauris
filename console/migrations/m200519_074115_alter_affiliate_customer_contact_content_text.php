<?php

use yii\db\Migration;

/**
 * Class m200519_074115_alter_affiliate_customer_contact_content_text
 */
class m200519_074115_alter_affiliate_customer_contact_content_text extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('affiliate_customer_contact', 'note', $this->text()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200519_074115_alter_affiliate_customer_contact_content_text cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200519_074115_alter_affiliate_customer_contact_content_text cannot be reverted.\n";

        return false;
    }
    */
}
