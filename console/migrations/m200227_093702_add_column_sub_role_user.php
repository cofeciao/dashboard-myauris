<?php

use yii\db\Migration;

/**
 * Class m200227_093702_add_column_sub_role_user
 */
class m200227_093702_add_column_sub_role_user extends Migration
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
        echo "m200227_093702_add_column_sub_role_user cannot be reverted.\n";

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
        $this->createTable('user_sub_role', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'role' => $this->text()->notNull(),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200227_093702_add_column_sub_role_user cannot be reverted.\n";

        return false;
    }
}
