<?php

use yii\db\Migration;

class m161202_175656_add_unique_index_in_events_user_table extends Migration
{
    public function up()
    {
		$this->createIndex('user_id_events_id', '{{%events_user}}', ['user_id', 'events_id'], true);
    }

    public function down()
    {
		$this->dropIndex('user_id_events_id', '{{%events_user}}');
    }

}
