<?php

use yii\db\Migration;

/**
 * Handles the creation for table `history_of_contacts_with_fans`.
 */
class m160929_081014_create_history_of_contacts_with_fans_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%history_of_contacts}}', [
            'id' 					=>$this->primaryKey(),
            'date' 					=>$this->integer(11),
            'communication_type_id' =>$this->integer(11),
            'user_id' 				=>$this->integer(11),
            'rating' 				=>$this->integer(1),
        ]);

		$this->createIndex('communication_type_id_index_history_of_contacts', '{{%history_of_contacts}}',  'communication_type_id');
		$this->createIndex('user_id_index_history_of_contacts', '{{%history_of_contacts}}',  'user_id');

		$this->addForeignKey('communication_type_id_history_of_contacts', '{{%history_of_contacts}}', 'communication_type_id', '{{%communication_type}}', 'id', 'SET NULL', 'RESTRICT');
		$this->addForeignKey('user_id_history_of_contacts', '{{%history_of_contacts}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%history_of_contacts}}');
    }
}
