<?php

use yii\db\Migration;

/**
 * Class m200102_021445_fix_support
 */
class m200102_021445_fix_support extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('support', 'desription', 'question');
        $this->renameColumn('support', 'content', 'anwser');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200102_021445_fix_support cannot be reverted.\n";

        return false;
    }
}
