<?php

use yii\db\Migration;

class m161203_182759_new_fild_events_table extends Migration
{
    public function up()
    {
        $this->addColumn('events', 'need_message', $this->boolean()->null()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('events', 'need_message');
    }

}
