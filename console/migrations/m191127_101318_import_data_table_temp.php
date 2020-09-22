<?php

use yii\db\Migration;

/**
 * Class m191127_101318_import_data_table_temp
 */
class m191127_101318_import_data_table_temp extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%table_temp}}', [
            'id' => $this->primaryKey(),
            'id_tinh_trang_rang' => $this->integer(11)->notNull(),
            'id_do_tuoi' => $this->integer(11)->notNull(),
            'image' => $this->string(255)->null(),
            'status' => $this->integer(11)->null()->defaultValue(1),
        ], $tableOptions);
        $this->execute("
        /*HỞ KẺ RĂNG THƯA - 19-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/14A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/14B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/15A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/15B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/16A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/16B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/37A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/37B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/39A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/39B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/43A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/43B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/49A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/49B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/51A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/51B.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/61A.JPG'),
        (5, 2, '/images/benh-ly/ho-ke-rang-thua/19-35/61B.JPG');
        /*HỞ KẺ RĂNG THƯA - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/1A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/1B.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/9A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/9B.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/18A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/18B.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/38A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/38B.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/46A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/46B.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/48A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/48B.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/53A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/53A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/55A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/55B.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/62A.JPG'),
        (5, 3, '/images/benh-ly/ho-ke-rang-thua/36/62B.JPG');
        /*HỞ LỢI - 19-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (1, 2, '/images/benh-ly/ho-loi/19-35/32A.JPG'),
        (1, 2, '/images/benh-ly/ho-loi/19-35/32B.JPG'),
        (1, 2, '/images/benh-ly/ho-loi/19-35/38A.JPG'),
        (1, 2, '/images/benh-ly/ho-loi/19-35/38B.JPG'),
        (1, 2, '/images/benh-ly/ho-loi/19-35/45A.JPG'),
        (1, 2, '/images/benh-ly/ho-loi/19-35/45B.JPG'),
        (1, 2, '/images/benh-ly/ho-loi/19-35/61A.JPG'),
        (1, 2, '/images/benh-ly/ho-loi/19-35/61B.JPG');
        /*HỞ LỢI - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (1, 3, '/images/benh-ly/ho-loi/36/56A.JPG'),
        (1, 3, '/images/benh-ly/ho-loi/36/56B.JPG');
        /*KHẤP KHỂNH MỌC LỆC - 19-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/13A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/13B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/33A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/33B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/40A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/40B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/45A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/45B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/50A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/50B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/51A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/51B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/61A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/61B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/66A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/66B.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/68A.JPG'),
        (4, 2, '/images/benh-ly/khap-khenh-moc-lech/19-35/68B.JPG');
        /*KHẤP KHỂNH MỌC LỆC - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/2A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/2B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/3A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/3B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/4A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/4B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/6A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/6B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/8A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/8B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/9A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/9B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/11A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/11B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/17A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/17B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/34A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/34B.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/69A.JPG'),
        (4, 3, '/images/benh-ly/khap-khenh-moc-lech/36/69B.JPG');
        /*KHỚP CẮN ĐỐI ĐẦU - 18-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/67A.JPG'),
        (8, 2, '/images/benh-ly/khop-can-doi-dau/19-35/67B.JPG');
        /*KHỚP CẮN ĐỐI ĐẦU - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/4A.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/4B.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/9A.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/9B.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/11A.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/11B.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/35A.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/35B.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/46A.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/46B.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/65A.JPG'),
        (8, 3, '/images/benh-ly/khop-can-doi-dau/36/65B.JPG');
        /*MẤT GÃY RĂNG - 19-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/15A.JPG'),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/15B.JPG'),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/43A.JPG'),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/43B.JPG'),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/63A.JPG'),
        (2, 2, '/images/benh-ly/mat-gay-rang/19-35/63B.JPG');
        /*MẤT GÃY RĂNG - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (2, 3, '/images/benh-ly/mat-gay-rang/36/6A.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/6B.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/7A.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/7B.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/17A.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/17B.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/31A.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/31B.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/47A.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/47B.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/64A.JPG'),
        (2, 3, '/images/benh-ly/mat-gay-rang/36/64B.JPG');
        /*RĂNG HÔ - 19-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (3, 2, '/images/benh-ly/rang-ho/19-35/45A.JPG'),
        (3, 2, '/images/benh-ly/rang-ho/19-35/45B.JPG'),
        (3, 2, '/images/benh-ly/rang-ho/19-35/51A.JPG'),
        (3, 2, '/images/benh-ly/rang-ho/19-35/51B.JPG'),
        (3, 2, '/images/benh-ly/rang-ho/19-35/61A.JPG'),
        (3, 2, '/images/benh-ly/rang-ho/19-35/61B.JPG');
        /*RĂNG HÔ - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (3, 3, '/images/benh-ly/rang-ho/36/2A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/2B.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/8A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/8B.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/10A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/10B.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/12A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/12B.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/36A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/36B.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/54A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/54B.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/62A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/62B.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/69A.JPG'),
        (3, 3, '/images/benh-ly/rang-ho/36/69B.JPG');
        /*RĂNG MÓM - 19-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (6, 2, '/images/benh-ly/rang-mom/19-35/49A.JPG'),
        (6, 2, '/images/benh-ly/rang-mom/19-35/49B.JPG'),
        (6, 2, '/images/benh-ly/rang-mom/19-35/58A.JPG'),
        (6, 2, '/images/benh-ly/rang-mom/19-35/58B.JPG');
        /*RĂNG MÓM - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (6, 3, '/images/benh-ly/rang-mom/36/53A.JPG'),
        (6, 3, '/images/benh-ly/rang-mom/36/53B.JPG');
        /*RĂNG VÀNG TETRA - 19-35*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/2A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/2B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/4A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/4B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/6A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/6B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/8A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/8B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/9A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/9B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/11A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/11B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/35A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/35B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/36A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/36B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/41A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/41B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/42A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/42B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/45A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/45B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/51A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/51B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/54A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/54B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/55A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/55B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/56A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/56B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/59A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/59B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/62A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/62B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/65A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/65B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/68A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/68B.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/69A.JPG'),
        (7, 2, '/images/benh-ly/rang-vang-tetra/19-35/69B.JPG');
        /*RĂNG VÀNG TETRA - 36+*/
        INSERT INTO table_temp(id_tinh_trang_rang, id_do_tuoi, image) VALUES
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/10A.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/10B.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/12A.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/12B.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/52A.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/52B.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/56A.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/56B.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/57A.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/57B.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/60A.JPG'),
        (7, 3, '/images/benh-ly/rang-vang-tetra/36/60B.JPG');
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191127_101318_import_data_table_temp cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191127_101318_import_data_table_temp cannot be reverted.\n";

        return false;
    }
    */
}
