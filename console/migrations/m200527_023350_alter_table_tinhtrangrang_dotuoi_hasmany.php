<?php

use yii\db\Migration;

/**
 * Class m200527_023350_alter_table_tinhtrangrang_dotuoi_hasmany
 */
class m200527_023350_alter_table_tinhtrangrang_dotuoi_hasmany extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
//        $this->alterColumn('tinhtrangrang_dotuoi_hasmany', 'id', $this->integer()->primaryKey()->au);
        $this->dropTable('tinhtrangrang_dotuoi_hasmany');

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('tinhtrangrang_dotuoi_hasmany', [
            'id' => $this->primaryKey(),
            'do_tuoi' => $this->integer(),
            'tinh_trang' => $this->integer(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(11)->null(),
            'created_by' => $this->integer(11)->null(),
            'updated_by' => $this->integer(11)->null(),
            'updated_at' => $this->integer(11)->null(),
        ], $tableOptions);

        for($i = 1; $i <= 3 ; $i++){
            for($j = 1; $j <= 7; $j++ ){
                $this->insert('tinhtrangrang_dotuoi_hasmany', ['do_tuoi' => $i, 'tinh_trang' => $j ]);
            }
        }

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200527_023350_alter_table_tinhtrangrang_dotuoi_hasmany cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200527_023350_alter_table_tinhtrangrang_dotuoi_hasmany cannot be reverted.\n";

        return false;
    }
    */
}
