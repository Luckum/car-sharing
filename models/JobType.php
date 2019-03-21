<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "job_type".
 *
 * @property int $id
 * @property string $value
 *
 * @property TicketHasJobType[] $ticketHasJobTypes
 */
class JobType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'job_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Название',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketHasJobTypes()
    {
        return $this->hasMany(TicketHasJobType::className(), ['job_type_id' => 'id']);
    }
}
