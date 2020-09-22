<?php

use yii\db\Migration;

/**
 * Class m200420_094921_alter_gioitinh_recommend
 */
class m200420_094921_alter_gioitinh_recommend extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('recommend', 'gioi_tinh', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200420_094921_alter_gioitinh_recommend cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200420_094921_alter_gioitinh_recommend cannot be reverted.\n";

        return false;
    }
    */
}
