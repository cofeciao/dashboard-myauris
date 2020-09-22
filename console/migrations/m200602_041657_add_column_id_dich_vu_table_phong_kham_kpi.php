<?php

use yii\db\Migration;

/**
 * Class m200602_041657_add_column_id_dich_vu_table_phong_kham_kpi
 */
class m200602_041657_add_column_id_dich_vu_table_phong_kham_kpi extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('phong_kham_kpi', 'id_dich_vu', $this->integer()->notNull()->after('kpi_time'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200602_041657_add_column_id_dich_vu_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200602_041657_add_column_id_dich_vu_table_phong_kham_kpi cannot be reverted.\n";

        return false;
    }
    */
}
