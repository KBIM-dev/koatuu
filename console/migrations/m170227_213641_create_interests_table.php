<?php

use yii\db\Migration;

/**
 * Handles the creation of table `interests`.
 */
class m170227_213641_create_interests_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('interests', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('interests');
    }
}
