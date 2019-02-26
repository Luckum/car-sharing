<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brigade_has_user`.
 */
class m190110_182423_create_brigade_has_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('brigade_has_user', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'brigade_id' => $this->integer(11)->notNull(),
            'is_master' => $this->tinyInteger(1)->notNull()->defaultValue(0),
        ], $tableOptions);
        
        $this->addForeignKey('brigade_has_user_ibfk_user', 'brigade_has_user', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('brigade_has_user_ibfk_brigade', 'brigade_has_user', 'brigade_id', 'brigade', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('brigade_has_user');
    }
}
