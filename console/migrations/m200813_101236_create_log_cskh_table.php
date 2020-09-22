<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log_cskh}}`.
 */
class m200813_101236_create_log_cskh_table extends Migration
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
        $this->createTable('{{%log_cskh}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11),
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
        $this->dropTable('{{%log_cskh}}');
    }
}
