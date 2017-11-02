<?php

use yii\db\Migration;

/**
 * Handles adding new to table `user_event`.
 */
class m161201_093803_add_new_column_to_events_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
		$this->addColumn('events_user', 'added_referral', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
		$this->dropColumn('events_user', 'added_referral');
    }
}
