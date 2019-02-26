<?php

use yii\db\Migration;

/**
 * Handles the creation of table `job_type`.
 */
class m190110_183724_create_job_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('job_type', [
            'id' => $this->primaryKey(),
            'value' => $this->string(255)->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('job_type');
    }
}
