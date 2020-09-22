<?php

use yii\db\Migration;

/**
 * Class m200730_035141_create_permission_tai_kham_for_user_dat_hen
 */
class m200730_035141_create_permission_tai_kham_for_user_dat_hen extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $check_rows = Yii::$app->db->createCommand('SELECT name FROM rbac_auth_item WHERE name="clinicClinic-dieu-triCreate-tai-kham" AND type=2')->queryOne();
        if($check_rows === false){
            $this->insert('rbac_auth_item', [
                'name' => 'clinicClinic-dieu-triCreate-tai-kham',
                'description' => 'Backend - Phòng khám - Lịch điều trị - Tạo lịch tái khám',
                'type' => 2
            ]);
        }
        $check_rows = Yii::$app->db->createCommand('SELECT name FROM rbac_auth_item WHERE name="clinicClinic-dieu-triUpdate-tai-kham" AND type=2')->queryOne();
        if($check_rows === false){
            $this->insert('rbac_auth_item', [
                'name' => 'clinicClinic-dieu-triUpdate-tai-kham',
                'description' => 'Backend - Phòng khám - Lịch điều trị - Cập nhật tái khám',
                'type' => 2
            ]);
        }
        $check_rows = Yii::$app->db->createCommand('SELECT name FROM rbac_auth_item WHERE name="clinicClinic-dieu-triValidate-tai-kham" AND type=2')->queryOne();
        if($check_rows === false){
            $this->insert('rbac_auth_item', [
                'name' => 'clinicClinic-dieu-triValidate-tai-kham',
                'description' => 'Backend - Phòng khám - Lịch điều trị - Validate tái khám',
                'type' => 2
            ]);
        }
        $check_rows = Yii::$app->db->createCommand('SELECT name FROM rbac_auth_item WHERE name="clinicClinic-orderView" AND type=2')->queryOne();
        if($check_rows === false){
            $this->insert('rbac_auth_item', [
                'name' => 'clinicClinic-orderView',
                'description' => 'Backend - Phòng khám - Đơn hàng - Xem chi tiết đơn hàng',
                'type' => 2
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200730_035141_create_permission_tai_kham_for_user_dat_hen cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200730_035141_create_permission_tai_kham_for_user_dat_hen cannot be reverted.\n";

        return false;
    }
    */
}
