<?php

use yii\db\Migration;

/**
 * Class m200728_044110_create_table_cskh_cham_soc
 */
class m200728_044110_create_table_cskh_cham_soc extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('cskh_cham_soc', [
            'id' => $this->primaryKey(),
            'cskh_quan_ly_id' => $this->integer(11),
            'note' => $this->string(),
            'status' => $this->integer(11),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200728_044110_create_table_cskh_cham_soc cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200728_044110_create_table_cskh_cham_soc cannot be reverted.\n";

        return false;
    }
    */
}
