<?php

use yii\db\Migration;

class m161021_065508_new_fild_history_of_contacts extends Migration
{
    public function up()
    {
        $this->addColumn('{{%history_of_contacts}}', 'from_user_id', $this->integer(11)->null());

        $this->createIndex('from_user_id_index_history_of_contacts', '{{%history_of_contacts}}',  'from_user_id');
        $this->addForeignKey('history_of_contacts_from_user_id-user_id', '{{%history_of_contacts}}', 'from_user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function down()
    {
        $this->dropColumn('{{%history_of_contacts}}', 'from_user_id');
    }
}
