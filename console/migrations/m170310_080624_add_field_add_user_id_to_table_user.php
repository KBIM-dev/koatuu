<?php

use yii\db\Migration;

class m170310_080624_add_field_add_user_id_to_table_user extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'added_id', $this->integer(11)->null());
        $this->createIndex('added_id_index', '{{%user}}', 'added_id');
        $this->addForeignKey('user_fk2', '{{%user}}', 'added_id', '{{%user}}', 'id', 'SET NULL', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('user_fk2', '{{%user}}');
        $this->dropColumn('user', 'added_id');
    }

}
