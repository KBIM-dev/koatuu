<?php

use yii\db\Migration;

class m160928_083316_add_relation_table_foe_many_to_many_relation extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {

		$this->createTable('{{%user_communication_type}}', [
			'id' 						=> $this->primaryKey(),
			'user_id' 					=> $this->integer(11)->notNull(),
			'communication_type_id' 	=> $this->integer(11)->notNull()
		]);

		$this->createIndex('user_id_index', '{{%user_communication_type}}', 'user_id');
		$this->createIndex('communication_type_id_index', '{{%user_communication_type}}', 'communication_type_id');

		$this->addForeignKey('user_id_fk1',  '{{%user_communication_type}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
		$this->addForeignKey('communication_type_id_fk1', '{{%user_communication_type}}', 'communication_type_id', '{{%communication_type}}', 'id', 'CASCADE', 'RESTRICT');

	}

    public function safeDown()
    {
    	$this->dropTable('{{%user_communication_type}}');
    }
}
