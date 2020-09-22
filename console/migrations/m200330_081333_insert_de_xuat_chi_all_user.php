<?php

use yii\db\Migration;

/**
 * Class m200330_081333_insert_de_xuat_chi_all_user
 */
class m200330_081333_insert_de_xuat_chi_all_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("insert into rbac_auth_item_child (parent, child) select distinct d.parent,'chiDe-xuat-chi' from rbac_auth_item_child as d where parent not in (select parent from rbac_auth_item_child where child='chiDe-xuat-chi')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200330_081333_insert_de_xuat_chi_all_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200330_081333_insert_de_xuat_chi_all_user cannot be reverted.\n";

        return false;
    }
    */
}
