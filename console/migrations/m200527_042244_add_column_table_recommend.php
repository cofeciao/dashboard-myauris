<?php

use yii\db\Migration;

/**
 * Class m200527_042244_add_column_table_recommend
 */
class m200527_042244_add_column_table_recommend extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('recommend', 'tieu_de', $this->string());
        $this->addColumn('recommend', 'mo_ta', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200527_042244_add_column_table_recommend cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200527_042244_add_column_table_recommend cannot be reverted.\n";

        return false;
    }
    */
}
