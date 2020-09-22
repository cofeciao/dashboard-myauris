<?php

use yii\db\Migration;

/**
 * Class m191129_111656_alter_column_table_temp
 */
class m191129_111656_alter_column_table_temp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('table_temp', 'image');
        $this->addColumn('table_temp', 'image_before', $this->string(255)->null()->after('id_do_tuoi'));
        $this->addColumn('table_temp', 'image_after', $this->string(255)->null()->after('image_before'));
        $this->execute("DELETE FROM table_temp");
        $this->execute("
            INSERT INTO `table_temp` (`id_tinh_trang_rang`, `id_do_tuoi`, `image_before`, `image_after`, `status`) VALUES
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/14A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/14B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/15A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/15B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/16A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/16B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/37A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/37B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/39A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/39B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/43A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/43B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/49A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/49B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/51A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/51B.jpg', 1),
            (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/61A.jpg', '/images/benh-ly/ho-ke-rang-thua/19-35/61B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/1A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/1B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/9A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/9B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/18A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/18B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/38A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/38B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/46A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/46B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/48A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/48B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/53A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/53A.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/55A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/55B.jpg', 1),
            (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/62A.jpg', '/images/benh-ly/ho-ke-rang-thua/36/62B.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/32A.jpg', '/images/benh-ly/ho-loi/19-35/32B.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/38A.jpg', '/images/benh-ly/ho-loi/19-35/38B.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/45A.jpg', '/images/benh-ly/ho-loi/19-35/45B.jpg', 1),
            (1, 2, '/images/benh-ly/ho-loi/19-35/61A.jpg', '/images/benh-ly/ho-loi/19-35/61B.jpg', 1),
            (1, 3, '/images/benh-ly/ho-loi/36/56A.jpg', '/images/benh-ly/ho-loi/36/56B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/13A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/13B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/33A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/33B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/40A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/40B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/45A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/45B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/50A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/50B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/51A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/51B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/61A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/61B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/66A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/66B.jpg', 1),
            (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/68A.jpg', '/images/benh-ly/khap-khenh-moc-lech/19-35/68B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/2A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/2B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/3A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/3B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/4A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/4B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/6A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/6B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/8A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/8B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/9A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/9B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/11A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/11B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/17A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/17B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/34A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/34B.jpg', 1),
            (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/69A.jpg', '/images/benh-ly/khap-khenh-moc-lech/36/69B.jpg', 1),
            (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/67A.jpg', '/images/benh-ly/khop-can-doi-dau/19-35/67B.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/4A.jpg', '/images/benh-ly/khop-can-doi-dau/36/4B.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/9A.jpg', '/images/benh-ly/khop-can-doi-dau/36/9B.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/11A.jpg', '/images/benh-ly/khop-can-doi-dau/36/11B.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/35A.jpg', '/images/benh-ly/khop-can-doi-dau/36/35B.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/46A.jpg', '/images/benh-ly/khop-can-doi-dau/36/46B.jpg', 1),
            (8, 3, '/images/benh-ly/khop-can-doi-dau/36/65A.jpg', '/images/benh-ly/khop-can-doi-dau/36/65B.jpg', 1),
            (2, 2, '/images/benh-ly/mat-gay-rang/19-35/15A.jpg', '/images/benh-ly/mat-gay-rang/19-35/15B.jpg', 1),
            (2, 2, '/images/benh-ly/mat-gay-rang/19-35/43A.jpg', '/images/benh-ly/mat-gay-rang/19-35/43B.jpg', 1),
            (2, 2, '/images/benh-ly/mat-gay-rang/19-35/63A.jpg', '/images/benh-ly/mat-gay-rang/19-35/63B.jpg', 1),
            (2, 3, '/images/benh-ly/mat-gay-rang/36/6A.jpg', '/images/benh-ly/mat-gay-rang/36/6B.jpg', 1),
            (2, 3, '/images/benh-ly/mat-gay-rang/36/7A.jpg', '/images/benh-ly/mat-gay-rang/36/7B.jpg', 1),
            (2, 3, '/images/benh-ly/mat-gay-rang/36/17A.jpg', '/images/benh-ly/mat-gay-rang/36/17B.jpg', 1),
            (2, 3, '/images/benh-ly/mat-gay-rang/36/31A.jpg', '/images/benh-ly/mat-gay-rang/36/31B.jpg', 1),
            (2, 3, '/images/benh-ly/mat-gay-rang/36/47A.jpg', '/images/benh-ly/mat-gay-rang/36/47B.jpg', 1),
            (2, 3, '/images/benh-ly/mat-gay-rang/36/64A.jpg', '/images/benh-ly/mat-gay-rang/36/64B.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/45A.jpg', '/images/benh-ly/rang-ho/19-35/45B.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/51A.jpg', '/images/benh-ly/rang-ho/19-35/51B.jpg', 1),
            (3, 2, '/images/benh-ly/rang-ho/19-35/61A.jpg', '/images/benh-ly/rang-ho/19-35/61B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/2A.jpg', '/images/benh-ly/rang-ho/36/2B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/8A.jpg', '/images/benh-ly/rang-ho/36/8B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/10A.jpg', '/images/benh-ly/rang-ho/36/10B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/12A.jpg', '/images/benh-ly/rang-ho/36/12B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/36A.jpg', '/images/benh-ly/rang-ho/36/36B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/54A.jpg', '/images/benh-ly/rang-ho/36/54B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/62A.jpg', '/images/benh-ly/rang-ho/36/62B.jpg', 1),
            (3, 3, '/images/benh-ly/rang-ho/36/69A.jpg', '/images/benh-ly/rang-ho/36/69B.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/49A.jpg', '/images/benh-ly/rang-mom/19-35/49B.jpg', 1),
            (6, 2, '/images/benh-ly/rang-mom/19-35/58A.jpg', '/images/benh-ly/rang-mom/19-35/58B.jpg', 1),
            (6, 3, '/images/benh-ly/rang-mom/36/53A.jpg', '/images/benh-ly/rang-mom/36/53B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/2A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/2B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/4A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/4B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/6A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/6B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/8A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/8B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/9A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/9B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/11A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/11B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/35A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/35B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/36A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/36B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/41A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/41B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/42A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/42B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/45A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/45B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/51A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/51B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/54A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/54B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/55A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/55B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/56A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/56B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/59A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/59B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/62A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/62B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/65A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/65B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/68A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/68B.jpg', 1),
            (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/69A.jpg', '/images/benh-ly/rang-vang-tetra/19-35/69B.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/10A.jpg', '/images/benh-ly/rang-vang-tetra/36/10B.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/12A.jpg', '/images/benh-ly/rang-vang-tetra/36/12B.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/52A.jpg', '/images/benh-ly/rang-vang-tetra/36/52B.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/56A.jpg', '/images/benh-ly/rang-vang-tetra/36/56B.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/57A.jpg', '/images/benh-ly/rang-vang-tetra/36/57B.jpg', 1),
            (7, 3, '/images/benh-ly/rang-vang-tetra/36/60A.jpg', '/images/benh-ly/rang-vang-tetra/36/60B.jpg', 1);
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191129_111656_alter_column_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191129_111656_alter_column_table_temp cannot be reverted.\n";

        return false;
    }
    */
}
