<?php

use yii\db\Migration;

/**
 * Handles adding milage_column_fuel to table `{{%ticket}}`.
 */
class m190331_101850_add_milage_column_fuel_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket}}', 'milage', $this->float()->defaultValue(null));
        $this->addColumn('{{%ticket}}', 'fuel', $this->float()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket}}', 'milage');
        $this->dropColumn('{{%ticket}}', 'fuel');
    }
}
