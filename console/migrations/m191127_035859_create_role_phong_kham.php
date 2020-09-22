<?php

use yii\db\Migration;

/**
 * Class m191127_035859_create_role_phong_kham
 */
class m191127_035859_create_role_phong_kham extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("INSERT INTO rbac_auth_item(name, type, description, created_at, updated_at, created_by, updated_by) VALUES('user_phongkham', 1, 'Phòng khám', '" . time() . "', '" . time() . "', '1', '1')");
        $this->execute("INSERT INTO rbac_auth_item_child(parent, child) VALUES('user_administrator', 'user_phongkham')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191127_035859_create_role_phong_kham cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191127_035859_create_role_phong_kham cannot be reverted.\n";

        return false;
    }
    */
}
