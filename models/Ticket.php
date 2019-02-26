<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property string $type
 * @property string $created_at
 * @property string $started_at
 * @property string $finished_at
 * @property int $external_id
 * @property int $status
 * @property int $rent_type
 * @property string $comment
 * @property int $brigade_id
 * @property string $lat
 * @property string $lon
 *
 * @property Brigade $brigade
 * @property TicketHasJobType[] $ticketHasJobTypes
 */
class Ticket extends \yii\db\ActiveRecord
{
    const STATUS_REJECTED = 0;
    const STATUS_COMMON = 1;
    const STATUS_ASAP = 2;
    const STATUS_COMPLETED = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'external_id', 'status', 'rent_type', 'lat', 'lon'], 'required'],
            [['type', 'comment'], 'string'],
            [['created_at', 'started_at', 'finished_at'], 'safe'],
            [['external_id', 'brigade_id'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['status', 'rent_type'], 'string', 'max' => 1],
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
            'type' => 'Type',
            'created_at' => 'Created At',
            'started_at' => 'Started At',
            'finished_at' => 'Finished At',
            'external_id' => 'External ID',
            'status' => 'Status',
            'rent_type' => 'Rent Type',
            'comment' => 'Comment',
            'brigade_id' => 'Brigade ID',
            'lat' => 'Lat',
            'lon' => 'Lon',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrigade()
    {
        return $this->hasOne(Brigade::className(), ['id' => 'brigade_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicketHasJobTypes()
    {
        return $this->hasMany(TicketHasJobType::className(), ['ticket_id' => 'id']);
    }
}
