<?php

use yii\db\Migration;

class m170302_131608_create_table_koatuu extends Migration
{
    public function up()
    {
		$this->createTable('koatuu', [
			'TE' => $this->char(10)->notNull(),
			'NP' => $this->string(1),
			'NU' => $this->text()
		]);
		$this->createIndex('TE', 'koatuu', 'TE', true);
		$this->addForeignKey('TE_fk', 'user', 'koatuu', 'koatuu', 'TE', 'SET NULL', 'CASCADE');
		$path = Yii::getAlias('@console');
		$sql = "
			LOAD DATA INFILE '$path/csv/KOATUU_23012017.csv'
			INTO TABLE koatuu
			FIELDS
				TERMINATED BY ';'
			LINES
				TERMINATED BY '\n'
			IGNORE 1 LINES
		";
		Yii::$app->db->createCommand($sql)->execute();
    }

    public function down()
    {
		$this->dropForeignKey('TE_fk', 'koatuu');
		$this->dropIndex('TE', 'koatuu');
		$this->dropTable('koatuu');
    }
}
