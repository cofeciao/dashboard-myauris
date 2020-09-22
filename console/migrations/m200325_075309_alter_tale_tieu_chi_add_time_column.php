<?php

use yii\db\Migration;

/**
 * Class m200325_075309_alter_tale_tieu_chi_add_time_column
 */
class m200325_075309_alter_tale_tieu_chi_add_time_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200325_075309_alter_tale_tieu_chi_add_time_column cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('thuchi_tieu_chi', 'thoi_gian_bat_dau', $this->integer()->notNull());
        $this->addColumn('thuchi_tieu_chi', 'thoi_gian_ket_thuc', $this->integer()->notNull());
    }

    public function down()
    {
        echo "m200325_075309_alter_tale_tieu_chi_add_time_column cannot be reverted.\n";

        return false;
    }
}
