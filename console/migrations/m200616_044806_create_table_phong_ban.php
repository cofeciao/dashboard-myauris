<?php

use yii\db\Migration;

/**
 * Class m200616_044806_create_table_phong_ban
 */
class m200616_044806_create_table_phong_ban extends Migration
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
        $check_phong_ban = Yii::$app->db->getTableSchema('phong_ban');
        if ($check_phong_ban === null) {
            $this->createTable('phong_ban', [
                'id' => $this->primaryKey(),
                'name' => $this->string(255)->notNull(),
                'parent' => $this->integer(11)->null(),
                'status' => $this->tinyInteger(1)->null()->defaultValue(1),
                'created_at' => $this->integer(11)->null(),
                'created_by' => $this->integer(11)->null(),
                'updated_at' => $this->integer(11)->null(),
                'updated_by' => $this->integer(11)->null(),
                'alias' => $this->string(500)->null()
            ], $tableOptions);
        }
        $check_phong_ban_role_hasmany = Yii::$app->db->getTableSchema('phong_ban_role_hasmany');
        if ($check_phong_ban_role_hasmany === null) {
            $this->createTable('phong_ban_role_hasmany', [
                'phong_ban_id' => $this->integer(11)->notNull(),
                'role' => $this->string(255)->notNull()
            ], $tableOptions);
        }
        /* check column exists */
        $get_fields_alias_rbac_auth_item = Yii::$app->db->getTableSchema('rbac_auth_item')->columns;
        if (!array_key_exists('alias', $get_fields_alias_rbac_auth_item)) {
            $this->addColumn('rbac_auth_item', 'alias', $this->string(500)->null());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200616_044806_create_table_phong_ban cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200616_044806_create_table_phong_ban cannot be reverted.\n";

        return false;
    }
    */
}
