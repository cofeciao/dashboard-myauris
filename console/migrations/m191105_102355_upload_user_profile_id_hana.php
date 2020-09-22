<?php

use backend\modules\user\models\User;
use yii\db\Migration;

/**
 * Class m191105_102355_upload_user_profile_id_hana
 */
class m191105_102355_upload_user_profile_id_hana extends Migration
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
        echo "m191105_102355_upload_user_profile_id_hana cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('{{%user_profile}}', 'id_hana', $this->text()->after("id_pancake"));

        if (User::find()->where([ 'id' => 229])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 505310660261344], ['user_id' => 229 ]);
        }
        if (User::find()->where([ 'id' => 228])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 535883606934416], ['user_id' => 228]);
        }
        if (User::find()->where([ 'id' => 227])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 717392975405531], ['user_id' => 227]);
        }
        if (User::find()->where([ 'id' => 226])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 787190618406876], ['user_id' => 226]);
        }
        if (User::find()->where([ 'id' => 222])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 1236553733194697], ['user_id' => 222]);
        }
        if (User::find()->where([ 'id' => 221])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 1317301011760569], ['user_id' => 221]);
        }
        if (User::find()->where([ 'id' => 216])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 1486046601535970], ['user_id' => 216]);
        }
        if (User::find()->where([ 'id' => 213])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 140408604017910], ['user_id' => 213]);
        }
        if (User::find()->where([ 'id' => 212])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 142159853810867], ['user_id' => 212]);
        }
        if (User::find()->where([ 'id' => 208])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 112104960207547], ['user_id' => 208]);
        }
        if (User::find()->where([ 'id' => 207])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 139347744131613], ['user_id' => 207]);
        }
        if (User::find()->where([ 'id' => 228])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 124297628978108], ['user_id' => 204]);
        }
        if (User::find()->where([ 'id' => 203])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 1167903130043901], ['user_id' => 203]);
        }
        if (User::find()->where([ 'id' => 201])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 2558878511010797], ['user_id' => 201]);
        }
        if (User::find()->where([ 'id' => 183])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 1199161663575530], ['user_id' => 183]);
        }
        if (User::find()->where([ 'id' => 181])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 2225413284367969], ['user_id' => 181]);
        }
        if (User::find()->where([ 'id' => 96])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 1124110724453299], ['user_id' => 96]);
        }
        if (User::find()->where([ 'id' => 230])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 631999163903530], ['user_id' => 230]);
        }
        if (User::find()->where([ 'id' => 231])->exists()) {
            $this->update('{{%user_profile}}', ['id_hana' => 615457878901672], ['user_id' => 231]);
        }
    }
    /*

    public function down()
    {
        echo "m191105_102355_upload_user_profile_id_hana cannot be reverted.\n";

        return false;
    }
    */
}
