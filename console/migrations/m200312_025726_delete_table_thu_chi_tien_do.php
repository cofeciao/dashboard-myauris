<?php

use yii\db\Migration;

/**
 * Class m200312_025726_delete_table_thu_chi_tien_do
 */
class m200312_025726_delete_table_thu_chi_tien_do extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('thuchi_tien_do');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200312_025726_delete_table_thu_chi_tien_do cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200312_025726_delete_table_thu_chi_tien_do cannot be reverted.\n";

        return false;
    }
    */
}
