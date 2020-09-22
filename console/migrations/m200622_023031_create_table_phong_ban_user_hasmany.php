<?php

use yii\db\Migration;

/**
 * Class m200622_023031_create_table_phong_ban_user_hasmany
 */
class m200622_023031_create_table_phong_ban_user_hasmany extends Migration
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
        /* check table exists */
        $check_phong_ban_user_hasmany = Yii::$app->db->getTableSchema('phong_ban_user_hasmany');
        if ($check_phong_ban_user_hasmany === null) {
            $this->createTable('phong_ban_user_hasmany', [
                'phong_ban_id' => $this->integer(11)->notNull(),
                'user_id' => $this->integer(11)->notNull()
            ], $tableOptions);
        }
        $get_fields_user = Yii::$app->db->getTableSchema('user')->columns;
        if (array_key_exists('alias', $get_fields_user)) {
            $this->dropColumn('user', 'alias');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200622_023031_create_table_phong_ban_user_hasmany cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200622_023031_create_table_phong_ban_user_hasmany cannot be reverted.\n";

        return false;
    }
    */
}
