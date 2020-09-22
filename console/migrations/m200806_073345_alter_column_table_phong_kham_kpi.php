<?php

use yii\db\Migration;

/**
 * Class m200806_073345_alter_column_table_phong_kham_kpi
 */
class m200806_073345_alter_column_table_phong_kham_kpi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('phong_kham_kpi', 'kpi_tuong_tac',$this->float()->notNull() );
        $this->alterColumn('phong_kham_kpi', 'kpi_lich_hen',$this->float()->notNull() );
        $this->alterColumn('phong_kham_kpi', 'kpi_lich_moi',$this->float()->notNull() );
        $this->alterColumn('phong_kham_kpi', 'kpi_khach_den',$this->float()->notNull() );
        $this->alterColumn('phong_kham_kpi', 'kpi_khach_lam',$this->float()->notNull() );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200806_073345_alter_column_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200806_073345_alter_column_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }
    */
}
