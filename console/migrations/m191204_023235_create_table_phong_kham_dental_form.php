<?php

use yii\db\Migration;

/**
 * Class m191204_023235_create_table_phong_kham_dental_form
 */
class m191204_023235_create_table_phong_kham_dental_form extends Migration
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
        $this->createTable('{{%phong_kham_dental_form}}', [
            'id' => $this->primaryKey(),
            'customer_id'   => $this->integer()->notNull(),
            'folder_id'   => $this->string()->notNull(),
            'status'     => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%phong_kham_dental_form}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191204_023235_create_table_phong_kham_dental_form cannot be reverted.\n";

        return false;
    }
    */
}
