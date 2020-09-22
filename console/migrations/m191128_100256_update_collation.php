<?php

use yii\db\Migration;

/**
 * Class m191128_100256_update_collation
 */
class m191128_100256_update_collation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE upload_audio CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE phong_kham_thong_bao CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE list_chuphinh_lichdieutri CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE list_chup_focus_face CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE list_chuphinh_lichdieutri_many CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191128_100256_update_collation cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191128_100256_update_collation cannot be reverted.\n";

        return false;
    }
    */
}
