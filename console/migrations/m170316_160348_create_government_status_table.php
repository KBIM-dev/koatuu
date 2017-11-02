<?php

use yii\db\Migration;

/**
 * Handles the creation of table `government_status`.
 */
class m170316_160348_create_government_status_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('government_status', [
            'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('government_status');
    }
}
