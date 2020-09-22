<?php

use yii\db\Migration;

/**
 * Class m200422_085352_add_column_recommend
 */
class m200422_085352_add_column_recommend extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('recommend', 'phan_loai', $this->json());
        $this->addColumn('recommend', 'benh_ly', $this->json());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200422_085352_add_column_recommend cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200422_085352_add_column_recommend cannot be reverted.\n";

        return false;
    }
    */
}
