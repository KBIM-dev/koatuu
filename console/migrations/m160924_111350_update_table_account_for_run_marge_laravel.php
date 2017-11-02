<?php

use yii\db\Migration;

class m160924_111350_update_table_account_for_run_marge_laravel extends Migration
{
    public function safeUp()
    {
		$this->addColumn('{{%user}}', 'chat_id', $this->integer(10)->unsigned()->unique()->null());
		$this->addColumn('{{%user}}', 'recruiter_id', $this->integer(11)->null());
		$this->addColumn('{{%user}}', 'phone', $this->string(13)->unique()->null());
		$this->addColumn('{{%user}}', 'areas_id', $this->integer(11)->null());
		$this->addColumn('{{%user}}', 'live_locality_name', $this->string(255)->null());
		$this->addColumn('{{%user}}', 'address', $this->string(255)->null());
		$this->addColumn('{{%user}}', 'profession_id', $this->integer(11)->null());
		$this->addColumn('{{%user}}', 'status_id', $this->integer(11)->null());
		$this->addColumn('{{%user}}', 'potential_id',  $this->integer(11)->null());
		$this->addColumn('{{%profile}}', 'date_of_birth', $this->date()->null());
		$this->addColumn('{{%profile}}', 'middle_name', $this->string(255)->null());
		$this->addColumn('{{%profile}}', 'last_name', $this->string(255)->null());

	}

    public function safeDown()
    {
		$this->dropColumn('{{%user}}', 'potential_id');
		$this->dropColumn('{{%user}}', 'status_id');
		$this->dropColumn('{{%user}}', 'profession_id');
		$this->dropColumn('{{%user}}', 'chat_id');
		$this->dropColumn('{{%user}}', 'live_locality_name');
		$this->dropColumn('{{%user}}', 'arias_id');
		$this->dropColumn('{{%user}}', 'recruiter_id');
		$this->dropColumn('{{%user}}', 'phone');
		$this->dropColumn('{{%user}}', 'address');
		$this->dropColumn('{{%profile}}', 'date_of_birth');
		$this->dropColumn('{{%profile}}', 'middle_name');
		$this->dropColumn('{{%profile}}', 'last_name');
    }

}
