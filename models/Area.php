<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property int $id
 * @property string $title
 * @property string $zip
 * 
 * @property BrigadeHasArea[] $brigadeHasAreas
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'zip'], 'required'],
            [['title'], 'string', 'max' => 50],
            [['zip'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'zip' => 'Индекс',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrigadeHasAreas()
    {
        return $this->hasMany(BrigadeHasArea::className(), ['area_id' => 'id']);
    }

    public function getTitleWithZip()
    {
        return $this->title . ' (' . $this->zip . ')';
    }
}
