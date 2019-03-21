<?php

use yii\db\Migration;

/**
 * Class m190305_194846_alter_user_table
 */
class m190305_194846_alter_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user', 'role', "enum('administrator', 'manager', 'worker', 'brigadier', 'operator')");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190305_194846_alter_user_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190305_194846_alter_user_table cannot be reverted.\n";

        return false;
    }
    */
}
