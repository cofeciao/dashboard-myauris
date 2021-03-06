<?php

use yii\db\Migration;

/**
 * Class m191123_100209_benh_lt_phanloai
 */
class m191123_100209_benh_lt_phanloai extends Migration
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

        $this->createTable('{{%benhly_phanloai}}', [
            'id' => $this->primaryKey(),
            'customer_id'         => $this->string(),
            'phaloai_id'           => $this->integer(),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
            'created_by'        => $this->integer()->notNull(),
            'updated_by'        => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->dropTable('{{%benhly_phanloai}}');
    }
}
