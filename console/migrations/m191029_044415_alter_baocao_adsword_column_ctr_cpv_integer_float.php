<?php

use yii\db\Migration;

/**
 * Class m191029_044415_alter_baocao_adsword_column_ctr_cpv_integer_float
 */
class m191029_044415_alter_baocao_adsword_column_ctr_cpv_integer_float extends Migration
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
        echo "m191029_044415_alter_baocao_adsword_column_ctr_cpv_integer_float cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('{{%baocao_chay_adwords}}', 'ctr', $this->float(3));
        $this->alterColumn('{{%baocao_chay_adwords}}', 'cpv', $this->float(3));
    }

    public function down()
    {
        echo "m191029_044415_alter_baocao_adsword_column_ctr_cpv_integer_float cannot be reverted.\n";

        return false;
    }
}
