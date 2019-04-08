<?php

use yii\db\Migration;

/**
 * Handles adding rejected_at to table `{{%ticket}}`.
 */
class m190329_135007_add_rejected_at_column_to_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket}}', 'rejected_at', $this->dateTime()->defaultValue(null)->after('finished_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%ticket}}', 'rejected_at');
    }
}
