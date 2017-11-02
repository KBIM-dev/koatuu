<?php

use yii\db\Migration;

class m161126_105242_update_columns_type_id_in_tables_regions_areas extends Migration
{
    public function up()
    {
		$this->alterColumn('{{%regions}}', 'type_id', $this->integer(11)->notNull());
		$this->alterColumn('{{%areas}}', 'type_id', $this->integer(11)->notNull());

    }

    public function down()
    {
        echo "m161126_105242_update_columns_type_id_in_tables_regions_areas cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
