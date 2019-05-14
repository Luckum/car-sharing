<?php

use yii\db\Migration;

/**
 * Class m190514_153206_alter_brigade_status_table
 */
class m190514_153206_alter_brigade_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('brigade_status', 'status', "enum('online', 'offline', 'pause', 'work')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190514_153206_alter_brigade_status_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190514_153206_alter_brigade_status_table cannot be reverted.\n";

        return false;
    }
    */
}
