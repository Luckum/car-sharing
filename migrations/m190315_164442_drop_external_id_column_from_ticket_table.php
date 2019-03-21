<?php

use yii\db\Migration;

/**
 * Handles dropping external_id from table `{{%ticket}}`.
 */
class m190315_164442_drop_external_id_column_from_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%ticket}}', 'external_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%ticket}}', 'external_id', $this->integer());
    }
}
