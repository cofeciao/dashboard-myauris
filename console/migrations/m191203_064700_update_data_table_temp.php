<?php

use yii\db\Migration;

/**
 * Class m191203_064700_update_data_table_temp
 */
class m191203_064700_update_data_table_temp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("DELETE FROM table_temp");
        $this->execute("
        INSERT INTO `table_temp` (`id_tinh_trang_rang`, `id_do_tuoi`, `image_before`, `image_after`, `status`) VALUES
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/12B.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/12A.jpg', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/1B.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/1A.jpg', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/2B.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/2A.jpg', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/3B.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/3A.jpg', 1),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/4B.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/4A.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/10B.jpg', '/images/benh-ly/ho-ke-rang-thua/36/10A.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/11B.jpg', '/images/benh-ly/ho-ke-rang-thua/36/11A.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/5B.jpg', '/images/benh-ly/ho-ke-rang-thua/36/5A.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/6B.jpg', '/images/benh-ly/ho-ke-rang-thua/36/6A.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/7B.jpg', '/images/benh-ly/ho-ke-rang-thua/36/7A.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/8B.jpg', '/images/benh-ly/ho-ke-rang-thua/36/8A.jpg', 1),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/9B.jpg', '/images/benh-ly/ho-ke-rang-thua/36/9A.jpg', 1),
        (1, 2, '/images/benh-ly/ho-loi/19-35/10B.jpg', '/images/benh-ly/ho-loi/19-35/10A.jpg', 1),
        (1, 2, '/images/benh-ly/ho-loi/19-35/1B.jpg', '/images/benh-ly/ho-loi/19-35/1A.jpg', 1),
        (1, 2, '/images/benh-ly/ho-loi/19-35/2B.jpg', '/images/benh-ly/ho-loi/19-35/2A.jpg', 1),
        (1, 2, '/images/benh-ly/ho-loi/19-35/3B.jpg', '/images/benh-ly/ho-loi/19-35/3A.jpg', 1),
        (1, 2, '/images/benh-ly/ho-loi/19-35/4B.jpg', '/images/benh-ly/ho-loi/19-35/4A.jpg', 1),
        (1, 2, '/images/benh-ly/ho-loi/19-35/5B.jpg', '/images/benh-ly/ho-loi/19-35/5A.jpg', 1),
        (1, 3, '/images/benh-ly/ho-loi/36/11B.jpg', '/images/benh-ly/ho-loi/36/11A.jpg', 1),
        (1, 3, '/images/benh-ly/ho-loi/36/12B.jpg', '/images/benh-ly/ho-loi/36/12A.jpg', 1),
        (1, 3, '/images/benh-ly/ho-loi/36/6B.jpg', '/images/benh-ly/ho-loi/36/6A.jpg', 1),
        (1, 3, '/images/benh-ly/ho-loi/36/7B.jpg', '/images/benh-ly/ho-loi/36/7A.jpg', 1),
        (1, 3, '/images/benh-ly/ho-loi/36/8B.jpg', '/images/benh-ly/ho-loi/36/8A.jpg', 1),
        (1, 3, '/images/benh-ly/ho-loi/36/9B.jpg', '/images/benh-ly/ho-loi/36/9A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/10B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/10A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/11B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/11A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/12B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/12A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/1B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/1A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/2B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/2A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/3B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/3A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/4B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/4A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/5B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/5A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/6B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/6A.jpg', 1),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/7B.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/7A.jpg', 1),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/8B.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/8A.jpg', 1),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/9B.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/9A.jpg', 1),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/11B.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/11A.jpg', 1),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/12B.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/12A.jpg', 1),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/1B.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/1A.jpg', 1),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/2B.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/2A.jpg', 1),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/3B.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/3A.jpg', 1),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/4B.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/4A.jpg', 1),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/5B.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/5A.jpg', 1),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/10B.jpg', '/images/benh-ly/khop-can-doi-dau/36/10A.jpg', 1),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/6B.jpg', '/images/benh-ly/khop-can-doi-dau/36/6A.jpg', 1),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/7B.jpg', '/images/benh-ly/khop-can-doi-dau/36/7A.jpg', 1),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/8B.jpg', '/images/benh-ly/khop-can-doi-dau/36/8A.jpg', 1),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/9B.jpg', '/images/benh-ly/khop-can-doi-dau/36/9A.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/10B.jpg', '/images/benh-ly/mat-gay-rang/19-35/10A.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/11B.jpg', '/images/benh-ly/mat-gay-rang/19-35/11A.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/6B.jpg', '/images/benh-ly/mat-gay-rang/19-35/6A.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/7B.jpg', '/images/benh-ly/mat-gay-rang/19-35/7A.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/8B.jpg', '/images/benh-ly/mat-gay-rang/19-35/8A.jpg', 1),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/9B.jpg', '/images/benh-ly/mat-gay-rang/19-35/9A.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/12B.jpg', '/images/benh-ly/mat-gay-rang/36/12A.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/1B.jpg', '/images/benh-ly/mat-gay-rang/36/1A.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/2B.jpg', '/images/benh-ly/mat-gay-rang/36/2A.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/3B.jpg', '/images/benh-ly/mat-gay-rang/36/3A.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/4B.jpg', '/images/benh-ly/mat-gay-rang/36/4A.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/5B.jpg', '/images/benh-ly/mat-gay-rang/36/5A.jpg', 1),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/6B.jpg', '/images/benh-ly/mat-gay-rang/36/6A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/11B.jpg', '/images/benh-ly/rang-ho/19-35/11A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/12B.jpg', '/images/benh-ly/rang-ho/19-35/12A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/1B.jpg', '/images/benh-ly/rang-ho/19-35/1A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/2B.jpg', '/images/benh-ly/rang-ho/19-35/2A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/3B.jpg', '/images/benh-ly/rang-ho/19-35/3A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/4B.jpg', '/images/benh-ly/rang-ho/19-35/4A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/5B.jpg', '/images/benh-ly/rang-ho/19-35/5A.jpg', 1),
        (3, 2, '/images/benh-ly/rang-ho/19-35/6B.jpg', '/images/benh-ly/rang-ho/19-35/6A.jpg', 1),
        (3, 3, '/images/benh-ly/rang-ho/36/10B.jpg', '/images/benh-ly/rang-ho/36/10A.jpg', 1),
        (3, 3, '/images/benh-ly/rang-ho/36/7B.jpg', '/images/benh-ly/rang-ho/36/7A.jpg', 1),
        (3, 3, '/images/benh-ly/rang-ho/36/8B.jpg', '/images/benh-ly/rang-ho/36/8A.jpg', 1),
        (3, 3, '/images/benh-ly/rang-ho/36/9B.jpg', '/images/benh-ly/rang-ho/36/9A.jpg', 1),
        (6, 2, '/images/benh-ly/rang-mom/19-35/1B.jpg', '/images/benh-ly/rang-mom/19-35/1A.jpg', 1),
        (6, 2, '/images/benh-ly/rang-mom/19-35/2B.jpg', '/images/benh-ly/rang-mom/19-35/2A.jpg', 1),
        (6, 3, '/images/benh-ly/rang-mom/36/3B.jpg', '/images/benh-ly/rang-mom/36/3A.jpg', 1),
        (6, 3, '/images/benh-ly/rang-mom/36/4B.jpg', '/images/benh-ly/rang-mom/36/4A.jpg', 1),
        (6, 3, '/images/benh-ly/rang-mom/36/5B.jpg', '/images/benh-ly/rang-mom/36/5A.jpg', 1),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/10B.jpg', '/images/benh-ly/rang-vang-tetra/19-35/10A.jpg', 1),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/12B.jpg', '/images/benh-ly/rang-vang-tetra/19-35/12A.jpg', 1),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/1B.jpg', '/images/benh-ly/rang-vang-tetra/19-35/1A.jpg', 1),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/2B.jpg', '/images/benh-ly/rang-vang-tetra/19-35/2A.jpg', 1),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/3B.jpg', '/images/benh-ly/rang-vang-tetra/19-35/3A.jpg', 1),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/4B.jpg', '/images/benh-ly/rang-vang-tetra/19-35/4A.jpg', 1),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/11B.jpg', '/images/benh-ly/rang-vang-tetra/36/11A.jpg', 1),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/5B.jpg', '/images/benh-ly/rang-vang-tetra/36/5A.jpg', 1),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/6B.jpg', '/images/benh-ly/rang-vang-tetra/36/6A.jpg', 1),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/7B.jpg', '/images/benh-ly/rang-vang-tetra/36/7A.jpg', 1),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/8B.jpg', '/images/benh-ly/rang-vang-tetra/36/8A.jpg', 1),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/9B.jpg', '/images/benh-ly/rang-vang-tetra/36/9A.jpg', 1);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191203_064700_update_data_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191203_064700_update_data_table_temp cannot be reverted.\n";

        return false;
    }
    */
}
