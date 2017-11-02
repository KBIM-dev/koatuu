<?php

use yii\db\Migration;

class m161013_083523_add_primary_key_auth_asigment extends Migration
{
    public function up()
    {
    	$this->alterColumn('auth_assignment', 'user_id', $this->integer(11)->notNull());
		$this->addForeignKey('auth_assignment-user_id', 'auth_assignment', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('auth_assignment-user_id', 'auth_assignment');
		$this->alterColumn('auth_assignment', 'user_id', $this->string(64)->notNull());
	}
}
