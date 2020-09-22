<?php

use yii\db\Migration;

/**
 * Class m200618_073739_add_column_don_hang_labo
 */
class m200618_073739_add_column_don_hang_labo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('labo_don_hang', 'so_luong', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('labo_don_hang', 'image', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200618_073739_add_column_don_hang_labo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200618_073739_add_column_don_hang_labo cannot be reverted.\n";

        return false;
    }
    */
}
