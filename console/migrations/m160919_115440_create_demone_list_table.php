<?php

use yii\db\Migration;

/**
 * Handles the creation for table `demone_list`.
 */
class m160919_115440_create_demone_list_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%demons}}', [
            'id' 			=> $this->primaryKey(),
            'className' 	=> $this->string(255)->notNull(),
            'enabled' 		=> $this->boolean()->defaultValue(0),
            'main_daemon' 	=> $this->boolean()->defaultValue(0),
            'comment' 		=> $this->string(255)->null(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('demons');
    }
}
