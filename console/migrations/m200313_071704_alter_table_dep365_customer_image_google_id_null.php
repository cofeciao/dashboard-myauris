<?php

use yii\db\Migration;

/**
 * Class m200313_071704_alter_table_dep365_customer_image_google_id_null
 */
class m200313_071704_alter_table_dep365_customer_image_google_id_null extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `dep365_customer_images` CHANGE `google_id` `google_id` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200313_071704_alter_table_dep365_customer_image_google_id_null cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200313_071704_alter_table_dep365_customer_image_google_id_null cannot be reverted.\n";

        return false;
    }
    */
}
