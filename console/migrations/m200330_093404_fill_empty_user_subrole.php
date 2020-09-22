<?php

use yii\db\Migration;

/**
 * Class m200330_093404_fill_empty_user_subrole
 */
class m200330_093404_fill_empty_user_subrole extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute('insert into user_sub_role (user_id,role,created_at,created_by,updated_at,updated_by) select id,\'\',unix_timestamp(now()),1,unix_timestamp(now()),1 from user where id not in (select user_id from user_sub_role);');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200330_093404_fill_empty_user_subrole cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200330_093404_fill_empty_user_subrole cannot be reverted.\n";

        return false;
    }
    */
}
