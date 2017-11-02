<?php

use yii\db\Migration;

/**
 * Handles the creation of table `street`.
 */
class m170307_170333_create_street_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('street', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'type_id' => $this->integer(11)->notNull(),
            'koatuu' => $this->char(10)->notNull(),
        ]);

        $this->createIndex('type_id', '{{%street}}',  'type_id');
        $this->createIndex('koatuu', '{{%street}}',  'koatuu');
        $this->createIndex('koatuu_type_id_name', '{{%street}}',  ['koatuu', 'type_id', 'name'], true);

        $this->createTable('street_types', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'short_name' => $this->string(10)->notNull(),
        ]);

        $this->createIndex('short_name', '{{%street_types}}',  'short_name', true);
        $this->createIndex('name', '{{%street_types}}',  'name', true);

        $this->addColumn('user', 'street_id', $this->integer(11));
        $this->addColumn('user', 'korp', $this->integer(2));
        $this->addColumn('user', 'apartment', $this->integer(4));
        $this->addColumn('user', 'build', $this->string(8));

        $this->createIndex('street_id', '{{%user}}',  'street_id');

        $this->addForeignKey('user_street_id_fk1', '{{%user}}', 'street_id', '{{%street}}', 'id', 'SET NULL', 'RESTRICT');
        $this->addForeignKey('street_type_id_fk1', '{{%street}}', 'type_id', '{{%street_types}}', 'id', 'RESTRICT', 'RESTRICT');
        $this->addForeignKey('street_koatuu_fk1', '{{%street}}', 'koatuu', '{{%koatuu}}', 'TE', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m170303_162006_migrate_kotatuu cannot be reverted.\n";

        return false;
    }
}
