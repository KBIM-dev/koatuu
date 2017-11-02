<?php

use yii\db\Migration;

class m161006_080827_add_new_fild_to_user_table extends Migration
{
    public function up()
    {
		$this->addColumn('{{%user}}', 'login_request_answer', $this->boolean()->null());
		$this->addColumn('{{%user}}', 'login_request_time', $this->integer(11)->null());
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'login_request_answer');
        $this->dropColumn('{{%user}}', 'login_request_time');
    }
}
