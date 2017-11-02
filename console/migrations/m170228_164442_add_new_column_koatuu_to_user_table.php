<?php

use yii\db\Migration;

class m170228_164442_add_new_column_koatuu_to_user_table extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'koatuu', $this->string(10).' COLLATE \'utf8_general_ci\'');
    }

    public function down()
    {
		$this->dropColumn('user', 'koatuu');
    }
}
