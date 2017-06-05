<?php

use yii\db\Migration;

class m170605_072423_add_date_to_comment extends Migration
{
    public function up()
    {
        $this->addColumn('comment', 'date', $this->date());
    }

    public function down()
    {
        $this->dropColumn('comment', 'date');

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
