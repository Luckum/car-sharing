<?php

use yii\db\Migration;

/**
 * Class m190513_181630_alter_brigade_table
 */
class m190513_181630_alter_brigade_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('brigade', 'status', "enum('online', 'offline', 'pause', 'work')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190513_181630_alter_brigade_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190513_181630_alter_brigade_table cannot be reverted.\n";

        return false;
    }
    */
}
