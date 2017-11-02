<?php

use yii\db\Migration;

class m161001_140233_alter_username_column extends Migration
{
    public function up()
    {
		$this->alterColumn('{{%user}}', 'username',$this->string(255)->null());
    }

    public function down()
    {
        echo "m161001_140233_alter_username_column cannot be reverted.\n";

        return false;
    }
}
