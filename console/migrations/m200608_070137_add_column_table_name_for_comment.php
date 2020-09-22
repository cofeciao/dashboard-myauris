<?php

use yii\db\Migration;

/**
 * Class m200608_070137_add_column_table_name_for_comment
 */
class m200608_070137_add_column_table_name_for_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /* check column exists */
        $get_fields_comment = Yii::$app->db->getTableSchema('thuchi_comment')->columns;
        if (!array_key_exists('table_name', $get_fields_comment)) {
            $this->addColumn('thuchi_comment', 'table_name', $this->string(255)->null()->defaultValue('thuchi_de_xuat_chi')->comment('Comment cho table nào'));
        }
        if(!array_key_exists('comment_for', $get_fields_comment)){
            $this->addColumn('thuchi_comment', 'comment_for', $this->integer(11)->null()->comment('Trả lời bình luận nào'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200608_070137_add_column_table_name_for_comment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200608_070137_add_column_table_name_for_comment cannot be reverted.\n";

        return false;
    }
    */
}
