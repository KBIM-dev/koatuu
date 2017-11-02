<?php

use yii\db\Migration;

class m160924_150600_add_glosare extends Migration
{


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    	$this->createTable('{{%profession}}', [
			'id' 	=> $this->primaryKey(),
			'name' 	=> $this->string(255)->notNull()
		]);
		$this->createTable('{{%communication_type}}', [
			'id' 	=> $this->primaryKey(),
			'name' 	=> $this->string(255)->notNull()
		]);
		$this->createTable('{{%status}}', [
			'id' 	=> $this->primaryKey(),
			'name' 	=> $this->string(255)->notNull()
		]);
		$this->createTable('{{%potential}}', [
			'id' 	=> $this->primaryKey(),
			'name' 	=> $this->string(255)->notNull()
		]);
		$this->createIndex('recruiter_id_index', '{{%user}}', 'recruiter_id');
		$this->createIndex('profession_id_index', '{{%user}}', 'profession_id');
		$this->createIndex('status_id_index', '{{%user}}', 'status_id');
		$this->createIndex('potential_id_index', '{{%user}}', 'potential_id');

		$this->addForeignKey('profession_fk1', '{{%user}}', 'profession_id', '{{%profession}}', 'id', 'SET NULL', 'RESTRICT');
		$this->addForeignKey('status_fk1', '{{%user}}', 'status_id', '{{%status}}', 'id', 'SET NULL', 'RESTRICT');
		$this->addForeignKey('potential_fk1', '{{%user}}', 'potential_id', '{{%potential}}', 'id', 'SET NULL', 'RESTRICT');
		$this->addForeignKey('user_fk1', '{{%user}}', 'recruiter_id', '{{%user}}', 'id', 'SET NULL', 'RESTRICT');

    }

    public function safeDown()
    {
		$this->dropForeignKey('user_fk1', '{{%user}}');
		$this->dropForeignKey('profession_fk1', '{{%user}}');
		$this->dropForeignKey('status_fk1', '{{%user}}');
		$this->dropForeignKey('potential_fk1', '{{%user}}');

    	$this->dropTable('{{%profession}}');
    	$this->dropTable('{{%communication_type}}');
    	$this->dropTable('{{%status}}');
    	$this->dropTable('{{%potential}}');
    }

}
