<?php

use yii\db\Migration;

/**
 * Class m200530_041412_add_column_kpi_time_table_phong_kham_kpi
 */
class m200530_041412_add_column_kpi_time_table_phong_kham_kpi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_kpi', 'kpi_time', $this->integer()->notNull()->after('kpi_khach_lam'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200530_041412_add_column_kpi_time_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200530_041412_add_column_kpi_time_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }
    */
}
