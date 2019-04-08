<?php

use yii\db\Migration;

/**
 * Handles adding delayed_at to table `{{%ticket}}`.
 */
class m190401_192430_add_delayed_at_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket}}', 'delayed_at', $this->dateTime()->defaultValue(null)->after('rejected_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket}}', 'delayed_at');
    }
}
