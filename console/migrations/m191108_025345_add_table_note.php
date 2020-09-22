<?php

use yii\db\Migration;

/**
 * Class m191108_025345_add_table_note
 */
class m191108_025345_add_table_note extends Migration
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
        echo "m191108_025345_add_table_note cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%note}}', [
            'id' => $this->primaryKey(),
            'note_type'         => $this->string(),
            'id_user'           => $this->integer(),
            'id_customer'       => $this->integer(),
            'id_lich_dieu_tri'  => $this->integer(),
            'note'              => $this->text(),
            'huong_dieu_tri'    => $this->text(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%note}}');
    }
}
