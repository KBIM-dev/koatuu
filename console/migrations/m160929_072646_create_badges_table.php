<?php

use yii\db\Migration;

/**
 * Handles the creation for table `badges`.
 */
class m160929_072646_create_badges_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%badge}}', [
            'id' 			=> $this->primaryKey(),
			'img' 			=> $this->string(255)->null(),
			'name' 			=> $this->string(255)->notNull(),
            'description' 	=> $this->text()->null(),
        ]);

		$this->createTable('{{%user_badge}}', [
			'id' 		=> $this->primaryKey(),
			'user_id' 	=> $this->integer(11),
			'badge_id' 	=> $this->integer(11),
			'time' 		=> $this->integer(11),

		]);
		
		$this->createIndex('user_id_index', '{{%user_badge}}', 'user_id');
		$this->createIndex('badge_id_index', '{{%user_badge}}', 'badge_id');

		$this->addForeignKey('user_badge-user_id', '{{%user_badge}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
		$this->addForeignKey('user_badge-badge_id', '{{%user_badge}}', 'badge_id', '{{%badge}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_badge}}');
        $this->dropTable('{{%badge}}');
    }
}
