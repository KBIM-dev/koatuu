<?php

use yii\db\Migration;

class m161126_100431_create_cities_types_tables_add_column_type_id_in_regions_areas extends Migration
{
    public function up()
    {
		$this->createTable('cities', [
			'id' => $this->primaryKey(),
			'city_name' => $this->string(255)->notNull(),
			'type_id' => $this->integer(11)->notNull(),
			'area_id' => $this->integer(11)->notNull(),
		]);


		$this->createTable('location_types', [
			'id' => $this->primaryKey(),
			'name' => $this->string(255)->notNull(),
			'short_name' => $this->string(10)->notNull(),
            'type' => $this->boolean()->notNull()->defaultValue(0),
			'class' => "enum('regions','areas','cities') NOT NULL",
		]);

		$this->addColumn('regions', 'type_id', $this->integer(11)->notNull());
		$this->addColumn('areas', 	'type_id', $this->integer(11)->notNull());

		$this->createIndex('regions_type_id', '{{%regions}}',  'type_id');
		$this->createIndex('areas_type_id',   '{{%areas}}',    'type_id');

		$this->createIndex('cities_type_id', '{{%cities}}',  'type_id');
		$this->createIndex('cities_area_id', '{{%cities}}',  'area_id');

		$this->addForeignKey('cities_type_id_fk1', '{{%cities}}', 'type_id', '{{%location_types}}', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('cities_area_id_fk1', '{{%cities}}', 'area_id', '{{%areas}}', 'id', 'RESTRICT', 'RESTRICT');

		$this->addForeignKey('regions_type_id_fk1', '{{%regions}}', 'type_id', '{{%location_types}}', 'id', 'RESTRICT', 'RESTRICT');
		$this->addForeignKey('areas_type_id_fk1', '{{%areas}}', 'type_id', '{{%location_types}}', 'id', 'RESTRICT', 'RESTRICT');
    }

    public function down()
    {
		$this->dropForeignKey('cities_type_id_fk1', '{{%cities}}');
		$this->dropForeignKey('cities_area_id_fk1', '{{%cities}}');
		$this->dropForeignKey('regions_type_id_fk1', '{{%regions}}');
		$this->dropForeignKey('areas_type_id_fk1', '{{%areas}}');

		$this->dropIndex('regions_type_id', '{{%regions}}');
		$this->dropIndex('areas_type_id',   '{{%areas}}');
		$this->dropIndex('cities_type_id', '{{%cities}}');
		$this->dropIndex('cities_area_id', '{{%cities}}');

		$this->dropTable('{{%cities}}');
		$this->dropTable('{{%location_types}}');

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
