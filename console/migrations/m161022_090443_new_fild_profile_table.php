<?php

use yii\db\Migration;

class m161022_090443_new_fild_profile_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'need_telegram', $this->boolean()->defaultValue(1));
    }

    public function down()
    {
        $this->dropColumn('{{%profile}}', 'need_telegram');
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
