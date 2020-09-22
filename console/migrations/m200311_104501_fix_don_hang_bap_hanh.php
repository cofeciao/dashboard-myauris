<?php

use yii\db\Migration;

/**
 * Class m200311_104501_fix_don_hang_bap_hanh
 */
class m200311_104501_fix_don_hang_bap_hanh extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->execute("alter table don_hang_bao_hanh convert to character set utf8mb4 collate utf8mb4_unicode_ci");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200311_104501_fix_don_hang_bap_hanh cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200311_104501_fix_don_hang_bap_hanh cannot be reverted.\n";

        return false;
    }
    */
}
