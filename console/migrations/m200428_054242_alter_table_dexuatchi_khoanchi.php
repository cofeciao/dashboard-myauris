<?php

use yii\db\Migration;

/**
 * Class m200428_054242_alter_table_dexuatchi_khoanchi
 */
class m200428_054242_alter_table_dexuatchi_khoanchi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('thuchi_de_xuat_chi', 'khoan_chi', $this->integer(11)->defaultValue(null)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200428_054242_alter_table_dexuatchi_khoanchi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200428_054242_alter_table_dexuatchi_khoanchi cannot be reverted.\n";

        return false;
    }
    */
}
