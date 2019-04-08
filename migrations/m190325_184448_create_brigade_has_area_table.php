<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%brigade_has_area}}`.
 */
class m190325_184448_create_brigade_has_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        
        $this->createTable('{{%brigade_has_area}}', [
            'id' => $this->primaryKey(),
            'brigade_id' => $this->integer(11)->notNull(),
            'area_id' => $this->integer(11)->notNull()
        ], $tableOptions);
        
        $this->addForeignKey('brigade_has_area_ibfk_area', 'brigade_has_area', 'area_id', 'area', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('brigade_has_area_ibfk_brigade', 'brigade_has_area', 'brigade_id', 'brigade', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%brigade_has_area}}');
    }
}
