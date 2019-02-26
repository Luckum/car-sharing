<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brigade_status".
 *
 * @property int $id
 * @property int $brigade_id
 * @property string $status
 * @property string $started_at
 * @property string $finished_at
 *
 * @property Brigade $brigade
 */
class BrigadeStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brigade_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['brigade_id', 'status'], 'required'],
            [['brigade_id'], 'integer'],
            [['status'], 'string'],
            [['started_at', 'finished_at'], 'safe'],
            [['brigade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brigade::className(), 'targetAttribute' => ['brigade_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brigade_id' => 'Brigade ID',
            'status' => 'Status',
            'started_at' => 'Started At',
            'finished_at' => 'Finished At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrigade()
    {
        return $this->hasOne(Brigade::className(), ['id' => 'brigade_id']);
    }
}
