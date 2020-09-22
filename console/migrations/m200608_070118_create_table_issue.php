<?php

use yii\db\Migration;

/**
 * Class m200608_070118_create_table_issue
 */
class m200608_070118_create_table_issue extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* check table exists */
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $check_table_issue = Yii::$app->db->getTableSchema('issue');
        if ($check_table_issue === null) {
            $this->createTable('issue', [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'description' => $this->text()->null(),
                'level' => $this->integer(11)->null()->defaultValue(0)->comment('Độ quan trọng'),
                'end_date_expected' => $this->integer(11)->null()->comment('Thời gian dự kiến giải quyết xong vấn đề'),
                'end_date' => $this->integer(11)->null()->comment('Ngày giải quyết xong vấn đề'),
                'status' => $this->tinyInteger(1)->null()->defaultValue(0)->comment('0 - Chưa giải quyết, 1 - Đã giải quyết'),
                'created_at' => $this->integer(11)->null(),
                'created_by' => $this->integer(11)->null(),
                'updated_at' => $this->integer(11)->null(),
                'updated_by' => $this->integer(11)->null(),
            ], $tableOptions);
        }
        $check_table_issue_phong_ban_hasmany = Yii::$app->db->getTableSchema('issue_phong_ban_hasmany');
        if ($check_table_issue_phong_ban_hasmany === null) {
            $this->createTable('issue_phong_ban_hasmany', [
                'issue_id' => $this->integer(11)->notNull(),
                'phong_ban_id' => $this->integer(11)->notNull()
            ], $tableOptions);
        }
        $check_table_issue_user_hasmany = Yii::$app->db->getTableSchema('issue_user_hasmany');
        if ($check_table_issue_user_hasmany === null) {
            $this->createTable('issue_user_hasmany', [
                'issue_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull()
            ], $tableOptions);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200608_070118_create_table_issue cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200608_070118_create_table_issue cannot be reverted.\n";

        return false;
    }
    */
}
