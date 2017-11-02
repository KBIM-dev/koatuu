<?php

use yii\db\Migration;

class m161205_171916_new_fild_events_table extends Migration
{
    public function up()
    {
        $this->addColumn('events', 'time_period_message', $this->integer()->defaultValue(0));
        $this->renameColumn('events', 'need_message', 'need_message_telegram');
        $this->addColumn('events', 'need_message_sms', $this->boolean()->null()->defaultValue(false));
    }

    public function down()
    {
        $this->dropColumn('events', 'time_period_message');
        $this->dropColumn('events', 'need_message_sms');
        $this->renameColumn('events', 'need_message_telegram', 'need_message');
    }
}
