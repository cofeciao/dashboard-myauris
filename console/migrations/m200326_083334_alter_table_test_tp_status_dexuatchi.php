<?php

use yii\db\Migration;

/**
 * Class m200326_083334_alter_table_test_tp_status_dexuatchi
 */
class m200326_083334_alter_table_test_tp_status_dexuatchi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('thuchi_de_xuat_chi', 'tp_status',
            $this->tinyInteger()
                ->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200326_083334_alter_table_test_tp_status_dexuatchi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200326_083334_alter_table_test_tp_status_dexuatchi cannot be reverted.\n";

        return false;
    }
    */
}
