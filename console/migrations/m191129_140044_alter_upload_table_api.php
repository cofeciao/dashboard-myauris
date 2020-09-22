<?php

use yii\db\Migration;

/**
 * Class m191129_140044_alter_upload_table_api
 */
class m191129_140044_alter_upload_table_api extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191129_140044_alter_upload_table_api cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('{{%phong_kham_uom_rang_1}}', 'created_by', $this->integer());
        $this->alterColumn('{{%phong_kham_uom_rang_1}}', 'updated_by', $this->integer());
        $this->alterColumn('{{%phong_kham_uom_rang_2}}', 'created_by', $this->integer());
        $this->alterColumn('{{%phong_kham_uom_rang_2}}', 'updated_by', $this->integer());
        $this->alterColumn('{{%phong_kham_hinh_final}}', 'created_by', $this->integer());
        $this->alterColumn('{{%phong_kham_hinh_final}}', 'updated_by', $this->integer());
    }
    /*

    public function down()
    {
        echo "m191129_140044_alter_upload_table_api cannot be reverted.\n";

        return false;
    }
    */
}
