<?php

use yii\db\Migration;

/**
 * Class m200526_095748_covan_view_seo
 */
class m200526_095748_covan_view_seo extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $check_permission = Yii::$app->db->createCommand("SELECT * FROM rbac_auth_item WHERE name='seoMyauris-analytics-log' AND type=2")->queryOne();
        if($check_permission === false){
            $this->insert('rbac_auth_item', [
                'name' => 'seoMyauris-analytics-log',
                'type' => 2,
                'description' => 'Backend - SEO - My Auris Analytics Logs'
            ]);
        }
        $check_permission_covan = Yii::$app->db->createCommand("SELECT * FROM rbac_auth_item_child WHERE parent='user_covan' AND child='seoMyauris-analytics-log'")->queryOne();
        if($check_permission_covan === false){
            $this->insert('rbac_auth_item_child', [
                'parent' => 'user_covan',
                'child' => 'seoMyauris-analytics-log'
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200526_095748_covan_view_seo cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200526_095748_covan_view_seo cannot be reverted.\n";

        return false;
    }
    */
}
