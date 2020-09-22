<?php

use yii\db\Migration;

/**
 * Class m200221_025603_alter_table_image_customer_delete_unique_images
 */
class m200221_025603_alter_table_image_customer_delete_unique_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE dep365_customer_images DROP INDEX image");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200221_025603_alter_table_image_customer_delete_unique_images cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200221_025603_alter_table_image_customer_delete_unique_images cannot be reverted.\n";

        return false;
    }
    */
}
