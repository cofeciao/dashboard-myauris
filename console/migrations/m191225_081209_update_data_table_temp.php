<?php

use yii\db\Migration;

/**
 * Class m191225_081209_update_data_table_temp
 */
class m191225_081209_update_data_table_temp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DELETE FROM table_temp WHERE id_tinh_trang_rang IN (5, 2)");
        $this->execute("
        INSERT INTO `table_temp` (`id_tinh_trang_rang`, `id_do_tuoi`, `image_before`, `image_after`, `status`) VALUES
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/10a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/10b.png', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/11a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/11b.png', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/1a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/1b.png', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/2a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/2b.png', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/3a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/3b.png', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/7a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/7b.png', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/8a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/8b.png', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/9a.png', '/images/benh-ly/ho-ke-rang-thua/19-35/9b.png', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/10a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/10b.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/11a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/11b.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/12a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/12b.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/1a.png', '/images/benh-ly/ho-ke-rang-thua/36/1b.png', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/2a.png', '/images/benh-ly/ho-ke-rang-thua/36/2b.png', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/3a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/3b.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/4a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/4b.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/5a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/5b.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/6a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/6b.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/7a.png', '/images/benh-ly/ho-ke-rang-thua/36/7b.png', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/8a.png', '/images/benh-ly/ho-ke-rang-thua/36/8b.png', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/9a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/9b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/1a.jpg', '/images/benh-ly/mat-gay-rang/19-35/1b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/2a.jpg', '/images/benh-ly/mat-gay-rang/19-35/2b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/3a.jpg', '/images/benh-ly/mat-gay-rang/19-35/3b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/4a.jpg', '/images/benh-ly/mat-gay-rang/19-35/4b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/5a.jpg', '/images/benh-ly/mat-gay-rang/19-35/5b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/6a.jpg', '/images/benh-ly/mat-gay-rang/19-35/6b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/7a.jpg', '/images/benh-ly/mat-gay-rang/19-35/7b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/8a.jpg', '/images/benh-ly/mat-gay-rang/19-35/8b.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/9a.jpg', '/images/benh-ly/mat-gay-rang/19-35/9b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/10a.jpg', '/images/benh-ly/mat-gay-rang/36/10b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/11a.jpg', '/images/benh-ly/mat-gay-rang/36/11b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/12a.jpg', '/images/benh-ly/mat-gay-rang/36/12b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/13a.jpg', '/images/benh-ly/mat-gay-rang/36/13b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/14a.jpg', '/images/benh-ly/mat-gay-rang/36/14b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/15a.jpg', '/images/benh-ly/mat-gay-rang/36/15b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/16a.jpg', '/images/benh-ly/mat-gay-rang/36/16b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/17a.jpg', '/images/benh-ly/mat-gay-rang/36/17b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/18a.jpg', '/images/benh-ly/mat-gay-rang/36/18b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/19a.jpg', '/images/benh-ly/mat-gay-rang/36/19b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/20a.jpg', '/images/benh-ly/mat-gay-rang/36/20b.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/21a.jpg', '/images/benh-ly/mat-gay-rang/36/21b.jpg', 1);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191225_081209_update_data_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191225_081209_update_data_table_temp cannot be reverted.\n";

        return false;
    }
    */
}
