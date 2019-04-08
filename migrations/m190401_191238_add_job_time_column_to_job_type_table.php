<?php

use yii\db\Migration;

/**
 * Handles adding job_time to table `{{%job_type}}`.
 */
class m190401_191238_add_job_time_column_to_job_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%job_type}}', 'job_time', $this->float()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%job_type}}', 'job_time');
    }
}
