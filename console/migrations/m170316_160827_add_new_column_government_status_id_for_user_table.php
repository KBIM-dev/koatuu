<?php

use yii\db\Migration;

class m170316_160827_add_new_column_government_status_id_for_user_table extends Migration
{
    public function up()
    {
		$this->addColumn('user','government_status_id', $this->integer(11)->null());
		$this->createIndex('user-government_status_id-index', 'user', 'government_status_id');
		$this->addForeignKey('user-government_status_id-government_status-id', 'user', 'government_status_id', 'government_status', 'id', 'SET NULL', 'RESTRICT');
    }

    public function down()
    {
    	$this->dropForeignKey('user-government_status_id-government_status-id', 'user');
    	$this->dropIndex('user-government_status_id-index', 'user');
        $this->dropColumn('user','government_status_id');
    }
}
