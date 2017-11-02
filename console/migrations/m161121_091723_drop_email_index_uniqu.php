<?php

use yii\db\Migration;

class m161121_091723_drop_email_index_uniqu extends Migration
{
    public function up()
    {
		$this->dropIndex('user_unique_email', 'user');
		$this->createIndex('user_email', 'user', 'email');
    }

    public function down()
    {
		$this->dropIndex('user_email', 'user');
		$this->createIndex('user_unique_email', 'user', 'email', true);
    }
}
