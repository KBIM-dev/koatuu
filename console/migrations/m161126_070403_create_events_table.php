<?php

use yii\db\Migration;

/**
 * Handles the creation of table `events`.
 */
class m161126_070403_create_events_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('events', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'need_participant' => $this->integer(10)->notNull(),
            'end_date' => $this->integer(11)->notNull(),
        ]);

        $this->createTable('events_user', [
            'id' => $this->primaryKey(),
            'events_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'is_crewman' => $this->boolean()->defaultValue(0)->notNull(),
            'is_voted' => $this->boolean()->defaultValue(0)->notNull(),
            'participant' => $this->boolean()->defaultValue(1)->notNull(),
        ]);

        $this->createIndex('events_user-events_id', '{{%events_user}}',  'events_id');
        $this->createIndex('events_user-user_id', '{{%events_user}}',  'user_id');

        $this->addForeignKey('events_id_events_user', '{{%events_user}}', 'events_id', '{{%events}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('user_id_events_user', '{{%events_user}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('events_user');
        $this->dropTable('events');
    }
}
