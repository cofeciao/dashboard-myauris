<?php

use yii\db\Migration;

/**
 * Class m200815_033839_checkcode_bao_hanh
 */
class m200815_033839_checkcode_bao_hanh extends Migration
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
        $this->createTable('checkcode_bao_hanh', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11),
            'birth_date' => $this->integer(11)->null(),
            'warranty_code' => $this->string(),
            'product_code' => $this->string(),
            'product_name' => $this->string(),
            'date_buy' => $this->integer(11)->null(),
            'warranty_time' => $this->integer(11)->null(),
            'co_so' => $this->integer(11),
            'co_so_name' => $this->string(),
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
        echo "m200815_033839_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200815_033839_checkcode_bao_hanh cannot be reverted.\n";

        return false;
    }
    */
}
