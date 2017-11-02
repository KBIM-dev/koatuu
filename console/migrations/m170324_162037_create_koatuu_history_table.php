<?php

use yii\db\Migration;

/**
 * Handles the creation of table `koatuu_history`.
 */
class m170324_162037_create_koatuu_history_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('koatuu_history', [
            'id' 			=> $this->primaryKey(),
			'old_koatuu' 	=> $this->char(10),
			'new_koatuu' 	=> $this->char(10),
			'old_location_name' => $this->string(255),
			'new_location_name' => $this->string(255),
			'design_VRU' 		=> $this->string(255),
			'time' 				=> $this->integer(11),
        ]);

        $this->createIndex('koatuu_history-old_koatuu-index', 'koatuu_history', 'old_koatuu');
        $this->createIndex('koatuu_history-new_koatuu-index', 'koatuu_history', 'new_koatuu');
        $this->createIndex('koatuu_history-time-index', 'koatuu_history', 'time');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('koatuu_history');
    }
}
