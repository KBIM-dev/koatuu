<?php

use yii\db\Migration;

class m160924_082545_create_table_regions_areas_cities_cities_types extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%regions}}', [
            'id' => $this->primaryKey(),
            'region_name' => $this->string(100),
        ]);
        $this->createTable('{{%areas}}', [
            'id' => $this->primaryKey(),
            'id_region' => $this->integer(),
            'area_name' => $this->string(100),
        ]);

        $this->createIndex('id_region', 'areas', 'id_region');

        $this->addForeignKey('areas_fk', 'areas', 'id_region', 'regions', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('areas_fk', 'areas');

        $this->dropIndex('id_region','areas');

        $this->dropTable('{{%regions}}');
        $this->dropTable('{{%areas}}');
    }
}
