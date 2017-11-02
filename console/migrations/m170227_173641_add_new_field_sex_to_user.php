<?php

use yii\db\Migration;

class m170227_173641_add_new_field_sex_to_user extends Migration
{
    public function up()
    {
		$this->addColumn('user', 'sex', $this->string(10));
		$this->addCommentOnColumn('user', 'sex', "Must be female or male.");
    }

    public function down()
    {
        $this->dropColumn('user', 'sex');
    }
}
