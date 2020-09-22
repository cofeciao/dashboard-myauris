<?php

use yii\db\Migration;

/**
 * Class m200511_021754_update_role
 */
class m200511_021754_update_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $check = Yii::$app->db->createCommand("SELECT * FROM `rbac_auth_item` WHERE type=2 AND name='bacsi'")->queryAll(); //check permission
        $check_2 = Yii::$app->db->createCommand("SELECT * FROM `rbac_auth_item_child` WHERE child='bacsi' AND parent='user_bac_si'")->queryAll(); //check permission is exists in role

        if (count($check) <= 0) {
            $this->execute("INSERT INTO `rbac_auth_item` (`name`, `type`, `description`) VALUES ('bacsi', '2', 'Backend - Bác sĩ')");
        }

        if (count($check_2) <= 0) {
            $this->execute("INSERT INTO `rbac_auth_item_child` (`parent`, `child`) VALUES ('user_bac_si', 'bacsi')");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200511_021754_update_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200511_021754_update_role cannot be reverted.\n";

        return false;
    }
    */
}
