<?php

use yii\db\Migration;

class m170611_115945_add_social_to_user extends Migration
{
    public function safeUp()
    {
        $this->addColumn('user', 'facebook', $this->boolean());

    }

    public function safeDown()
    {
        $this->dropColumn('user', 'facebook', $this->boolean());

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170611_115945_add_social_to_user cannot be reverted.\n";

        return false;
    }
    */
}
