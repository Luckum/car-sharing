<?php

use yii\db\Migration;

/**
 * Handles the creation of table `area`.
 */
class m190110_180454_create_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('area', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->notNull(),
            'zip' => $this->string(6)->notNull(),
        ], $tableOptions);
        
        $this->batchInsert('area', ['title', 'zip'], [
            ['Квадрант 1', '101000'],
            ['Квадрант 2', '103'],
            ['Квадрант 3', '105'],
            ['Квадрант 4', '107'],
            ['Квадрант 5', '109'],
            ['Квадрант 6', '111'],
            ['Квадрант 7', '115'],
            ['Квадрант 8', '117'],
            ['Квадрант 9', '119'],
            ['Квадрант 10', '121'],
            ['Квадрант 11', '123'],
            ['Квадрант 12', '124'],
            ['Квадрант 13', '125'],
            ['Квадрант 14', '127'],
            ['Квадрант 15', '129'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('area');
    }
}
