<?php

use yii\db\Migration;

/**
 * Class m191128_030840_list_chup_hinh
 */
class m191128_030840_list_chup_hinh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%list_chuphinh_lichdieutri}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'created_at' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);
        $this->insert('list_chuphinh_lichdieutri', [
            'name' => 'Làm mới',
        ]);
        $this->insert('list_chuphinh_lichdieutri', [
            'name' => 'Lắp hoàn thiện',
        ]);
        $this->createTable('{{%list_chuphinh_lichdieutri_many}}', [
            'id_list_chuphinh_lichdieutri' => $this->integer()->notNull(),
            'id_list_chuphinh' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->insert('list_chuphinh_lichdieutri_many', [
            'id_list_chuphinh_lichdieutri' => '1',
            'id_list_chuphinh' => '2',
        ]);
        $this->insert('list_chuphinh_lichdieutri_many', [
            'id_list_chuphinh_lichdieutri' => '1',
            'id_list_chuphinh' => '3',
        ]);
        $this->insert('list_chuphinh_lichdieutri_many', [
            'id_list_chuphinh_lichdieutri' => '2',
            'id_list_chuphinh' => '4',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%list_chuphinh_lichdieutri}}');
        return $this->dropTable('{{%list_chuphinh_lichdieutri_many}}');
    }
}
