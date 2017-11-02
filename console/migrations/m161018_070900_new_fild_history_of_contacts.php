<?php

use yii\db\Migration;

class m161018_070900_new_fild_history_of_contacts extends Migration
{
    public function up()
    {
        $this->addColumn('{{%history_of_contacts}}', 'comment', $this->text()->null());
    }

    public function down()
    {
        $this->dropColumn('{{%history_of_contacts}}', 'comment');
    }

}
