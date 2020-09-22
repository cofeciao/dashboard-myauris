<?php

use yii\db\Migration;

/**
 * Class m200331_102956_alter_thuchi_dexuat_add_column_title
 */
class m200331_102956_alter_thuchi_dexuat_add_column_title extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('thuchi_de_xuat_chi', 'title', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200331_102956_alter_thuchi_dexuat_add_column_title cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200331_102956_alter_thuchi_dexuat_add_column_title cannot be reverted.\n";

        return false;
    }
    */
}
