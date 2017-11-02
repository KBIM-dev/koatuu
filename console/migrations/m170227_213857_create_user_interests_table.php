<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_interests`.
 */
class m170227_213857_create_user_interests_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('user_interests', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'interest_id' => $this->integer(11)->notNull(),
        ]);
        $this->createIndex('user_id-index','user_interests', 'user_id');
        $this->createIndex('interest_id-index','user_interests', 'interest_id');

        $this->addForeignKey("user_id-user_interests-id", "user_interests", "user_id", 'user', 'id', 'CASCADE', "RESTRICT");
        $this->addForeignKey("interest_id-user_interests-id", "user_interests", "interest_id", 'interests', 'id', 'CASCADE', "RESTRICT");

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user_interests');
    }
}
