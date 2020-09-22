<?php

use yii\db\Migration;

/**
 * Class m191128_102104_update_focus_face_to_Mong
 */
class m191128_102104_update_focus_face_to_Mong extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('list_chup_focus_face', 'focus_face', $this->string(255)->null()->after('catagory_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191128_102104_update_focus_face_to_Mong cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191128_102104_update_focus_face_to_Mong cannot be reverted.\n";

        return false;
    }
    */
}
