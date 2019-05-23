<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket_photo".
 *
 * @property int $id
 * @property int $ticket_id
 * @property string $path
 * @property string $created_at
 *
 * @property Ticket $ticket
 */
class TicketPhoto extends \yii\db\ActiveRecord
{
    public $photo_file;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket_photo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'path'], 'required'],
            [['ticket_id'], 'integer'],
            [['created_at'], 'safe'],
            [['path'], 'string', 'max' => 255],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::className(), 'targetAttribute' => ['ticket_id' => 'id']],
            ['photo_file', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'skipOnEmpty' => true, 'minSize' => 120*120, 'maxFiles' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ticket_id' => 'Ticket ID',
            'path' => 'Path',
            'created_at' => 'Created At',
            'photo_file' => 'Фотографии',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasOne(Ticket::className(), ['id' => 'ticket_id']);
    }
}
