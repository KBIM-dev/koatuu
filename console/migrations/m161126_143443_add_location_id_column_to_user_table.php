<?php

use yii\db\Migration;

/**
 * Handles adding location_id to table `user`.
 */
class m161126_143443_add_location_id_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
		$this->addColumn('{{%user}}', 'location_id', $this->integer(11));
		$this->addColumn('{{%user}}', 'loc_id', $this->integer(11));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
		$this->dropColumn('{{%user}}', 'location_id');
		$this->dropColumn('{{%user}}', 'loc_id');
    }
}
