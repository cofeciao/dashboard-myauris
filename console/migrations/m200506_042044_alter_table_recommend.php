<?php

use yii\db\Migration;

/**
 * Class m200506_042044_alter_table_recommend
 */
class m200506_042044_alter_table_recommend extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('recommend', 'video', $this->string());
        $this->addColumn('recommend', 'vat_lieu', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200506_042044_alter_table_recommend cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200506_042044_alter_table_recommend cannot be reverted.\n";

        return false;
    }
    */
}
