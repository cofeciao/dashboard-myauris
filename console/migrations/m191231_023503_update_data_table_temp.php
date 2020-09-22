<?php

use yii\db\Migration;

/**
 * Class m191231_023503_update_data_table_temp
 */
class m191231_023503_update_data_table_temp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("TRUNCATE TABLE table_temp");
        $this->execute("
            INSERT INTO `table_temp` (`id_tinh_trang_rang`, `id_do_tuoi`, `image_after`, `image_before`, `status`) VALUES
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/10a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/10b.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/11a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/11b.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/1a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/1b.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/2a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/2b.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/3a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/3b.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/7a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/7b.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/8a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/8b.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/9a.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/9b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/10a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/10b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/11a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/11b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/12a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/12b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/1a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/1b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/2a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/2b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/3a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/3b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/4a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/4b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/5a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/5b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/6a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/6b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/7a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/7b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/8a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/8b.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/9a.jpg', '/images/benh-ly/ho-ke-rang-thua/36/9b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/10a.jpg', '/images/benh-ly/ho-loi/19-35/10b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/11a.jpg', '/images/benh-ly/ho-loi/19-35/11b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/12a.jpg', '/images/benh-ly/ho-loi/19-35/12b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/1a.jpg', '/images/benh-ly/ho-loi/19-35/1b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/2a.jpg', '/images/benh-ly/ho-loi/19-35/2b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/3a.jpg', '/images/benh-ly/ho-loi/19-35/3b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/4a.jpg', '/images/benh-ly/ho-loi/19-35/4b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/5a.jpg', '/images/benh-ly/ho-loi/19-35/5b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/6a.jpg', '/images/benh-ly/ho-loi/19-35/6b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/7a.jpg', '/images/benh-ly/ho-loi/19-35/7b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/8a.jpg', '/images/benh-ly/ho-loi/19-35/8b.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/9a.jpg', '/images/benh-ly/ho-loi/19-35/9b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/10a.jpg', '/images/benh-ly/ho-loi/36/10b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/11a.jpg', '/images/benh-ly/ho-loi/36/11b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/12a.jpg', '/images/benh-ly/ho-loi/36/12b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/1a.jpg', '/images/benh-ly/ho-loi/36/1b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/2a.jpg', '/images/benh-ly/ho-loi/36/2b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/3a.jpg', '/images/benh-ly/ho-loi/36/3b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/5a.jpg', '/images/benh-ly/ho-loi/36/5b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/6a.jpg', '/images/benh-ly/ho-loi/36/6b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/7a.jpg', '/images/benh-ly/ho-loi/36/7b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/8a.jpg', '/images/benh-ly/ho-loi/36/8b.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/9a.jpg', '/images/benh-ly/ho-loi/36/9b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/10a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/10b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/11a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/11b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/12a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/12b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/1a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/1b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/2a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/2b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/3a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/3b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/4a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/4b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/5a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/5b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/6a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/6b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/7a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/7b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/8a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/8b.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/9a.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/9b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/10a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/10b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/11a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/11b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/12a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/12b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/1a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/1b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/2a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/2b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/3a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/3b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/4a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/4b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/5a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/5b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/6a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/6b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/7a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/7b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/8a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/8b.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/9a.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/9b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/10a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/10b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/11a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/11b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/12a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/12b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/1a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/1b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/2a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/2b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/3a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/3b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/4a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/4b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/5a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/5b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/6a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/6b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/7a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/7b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/8a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/8b.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/9a.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/9b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/10a.jpg', '/images/benh-ly/khop-can-doi-dau/36/10b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/11a.jpg', '/images/benh-ly/khop-can-doi-dau/36/11b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/12a.jpg', '/images/benh-ly/khop-can-doi-dau/36/12b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/1a.jpg', '/images/benh-ly/khop-can-doi-dau/36/1b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/2a.jpg', '/images/benh-ly/khop-can-doi-dau/36/2b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/3a.jpg', '/images/benh-ly/khop-can-doi-dau/36/3b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/4a.jpg', '/images/benh-ly/khop-can-doi-dau/36/4b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/5a.jpg', '/images/benh-ly/khop-can-doi-dau/36/5b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/6a.jpg', '/images/benh-ly/khop-can-doi-dau/36/6b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/7a.jpg', '/images/benh-ly/khop-can-doi-dau/36/7b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/8a.jpg', '/images/benh-ly/khop-can-doi-dau/36/8b.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/9a.jpg', '/images/benh-ly/khop-can-doi-dau/36/9b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/1a.jpg', '/images/benh-ly/mat-rang-gay/19-35/1b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/2a.jpg', '/images/benh-ly/mat-rang-gay/19-35/2b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/3a.jpg', '/images/benh-ly/mat-rang-gay/19-35/3b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/4a.jpg', '/images/benh-ly/mat-rang-gay/19-35/4b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/5a.jpg', '/images/benh-ly/mat-rang-gay/19-35/5b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/6a.jpg', '/images/benh-ly/mat-rang-gay/19-35/6b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/7a.jpg', '/images/benh-ly/mat-rang-gay/19-35/7b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/8a.jpg', '/images/benh-ly/mat-rang-gay/19-35/8b.jpg', 1),
            (2, 2, '/images/benh-ly/mat-rang-gay/19-35/9a.jpg', '/images/benh-ly/mat-rang-gay/19-35/9b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/10a.jpg', '/images/benh-ly/mat-rang-gay/36/10b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/11a.jpg', '/images/benh-ly/mat-rang-gay/36/11b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/12a.jpg', '/images/benh-ly/mat-rang-gay/36/12b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/13a.jpg', '/images/benh-ly/mat-rang-gay/36/13b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/14a.jpg', '/images/benh-ly/mat-rang-gay/36/14b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/15a.jpg', '/images/benh-ly/mat-rang-gay/36/15b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/16a.jpg', '/images/benh-ly/mat-rang-gay/36/16b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/17a.jpg', '/images/benh-ly/mat-rang-gay/36/17b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/18a.jpg', '/images/benh-ly/mat-rang-gay/36/18b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/19a.jpg', '/images/benh-ly/mat-rang-gay/36/19b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/20a.jpg', '/images/benh-ly/mat-rang-gay/36/20b.jpg', 1),
            (2, 3, '/images/benh-ly/mat-rang-gay/36/21a.jpg', '/images/benh-ly/mat-rang-gay/36/21b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/10a.jpg', '/images/benh-ly/rang-ho/19-35/10b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/11a.jpg', '/images/benh-ly/rang-ho/19-35/11b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/12a.jpg', '/images/benh-ly/rang-ho/19-35/12b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/1a.jpg', '/images/benh-ly/rang-ho/19-35/1b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/2a.jpg', '/images/benh-ly/rang-ho/19-35/2b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/3a.jpg', '/images/benh-ly/rang-ho/19-35/3b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/4a.jpg', '/images/benh-ly/rang-ho/19-35/4b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/5a.jpg', '/images/benh-ly/rang-ho/19-35/5b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/6a.jpg', '/images/benh-ly/rang-ho/19-35/6b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/7a.jpg', '/images/benh-ly/rang-ho/19-35/7b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/8a.jpg', '/images/benh-ly/rang-ho/19-35/8b.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/9a.jpg', '/images/benh-ly/rang-ho/19-35/9b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/10a.jpg', '/images/benh-ly/rang-ho/36/10b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/11a.jpg', '/images/benh-ly/rang-ho/36/11b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/12a.jpg', '/images/benh-ly/rang-ho/36/12b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/1a.jpg', '/images/benh-ly/rang-ho/36/1b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/2a.jpg', '/images/benh-ly/rang-ho/36/2b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/3a.jpg', '/images/benh-ly/rang-ho/36/3b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/4a.jpg', '/images/benh-ly/rang-ho/36/4b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/5a.jpg', '/images/benh-ly/rang-ho/36/5b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/6a.jpg', '/images/benh-ly/rang-ho/36/6b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/7a.jpg', '/images/benh-ly/rang-ho/36/7b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/8a.jpg', '/images/benh-ly/rang-ho/36/8b.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/9a.jpg', '/images/benh-ly/rang-ho/36/9b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/10a.jpg', '/images/benh-ly/rang-mom/19-35/10b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/11a.jpg', '/images/benh-ly/rang-mom/19-35/11b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/12a.jpg', '/images/benh-ly/rang-mom/19-35/12b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/1a.jpg', '/images/benh-ly/rang-mom/19-35/1b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/2a.jpg', '/images/benh-ly/rang-mom/19-35/2b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/3a.jpg', '/images/benh-ly/rang-mom/19-35/3b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/4a.jpg', '/images/benh-ly/rang-mom/19-35/4b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/5a.jpg', '/images/benh-ly/rang-mom/19-35/5b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/6a.jpg', '/images/benh-ly/rang-mom/19-35/6b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/7a.jpg', '/images/benh-ly/rang-mom/19-35/7b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/8a.jpg', '/images/benh-ly/rang-mom/19-35/8b.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/9a.jpg', '/images/benh-ly/rang-mom/19-35/9b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/10a.jpg', '/images/benh-ly/rang-mom/36/10b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/11a.jpg', '/images/benh-ly/rang-mom/36/11b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/12a.jpg', '/images/benh-ly/rang-mom/36/12b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/1a.jpg', '/images/benh-ly/rang-mom/36/1b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/2a.jpg', '/images/benh-ly/rang-mom/36/2b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/3a.jpg', '/images/benh-ly/rang-mom/36/3b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/4a.jpg', '/images/benh-ly/rang-mom/36/4b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/5a.jpg', '/images/benh-ly/rang-mom/36/5b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/6a.jpg', '/images/benh-ly/rang-mom/36/6b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/7a.jpg', '/images/benh-ly/rang-mom/36/7b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/8a.jpg', '/images/benh-ly/rang-mom/36/8b.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/9a.jpg', '/images/benh-ly/rang-mom/36/9b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/10a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/10b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/11a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/11b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/12a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/12b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/1a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/1b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/2a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/2b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/3a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/3b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/4a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/4b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/5a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/5b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/6a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/6b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/7a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/7b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/8a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/8b.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/9a.jpg', '/images/benh-ly/rang-vang-tetra/19-35/9b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/10a.jpg', '/images/benh-ly/rang-vang-tetra/36/10b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/11a.jpg', '/images/benh-ly/rang-vang-tetra/36/11b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/12a.jpg', '/images/benh-ly/rang-vang-tetra/36/12b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/1a.jpg', '/images/benh-ly/rang-vang-tetra/36/1b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/2a.jpg', '/images/benh-ly/rang-vang-tetra/36/2b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/3a.jpg', '/images/benh-ly/rang-vang-tetra/36/3b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/4a.jpg', '/images/benh-ly/rang-vang-tetra/36/4b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/5a.jpg', '/images/benh-ly/rang-vang-tetra/36/5b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/6a.jpg', '/images/benh-ly/rang-vang-tetra/36/6b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/7a.jpg', '/images/benh-ly/rang-vang-tetra/36/7b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/8a.jpg', '/images/benh-ly/rang-vang-tetra/36/8b.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/9a.jpg', '/images/benh-ly/rang-vang-tetra/36/9b.jpg', 1);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191231_023503_update_data_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191231_023503_update_data_table_temp cannot be reverted.\n";

        return false;
    }
    */
}
