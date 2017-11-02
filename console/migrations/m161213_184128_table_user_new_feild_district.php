<?php

use yii\db\Migration;

class m161213_184128_table_user_new_feild_district extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'district', $this->integer(7)->null()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('user', 'district');
    }
}
