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
 * @property int $status
 * @property int $rent_type
 * @property string $comment
 * @property int $brigade_id
 * @property string $lat
 * @property string $lon
 * @property int $car_id
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
    
    const RENT_TYPE_DAY = 1;
    const RENT_TYPE_HOUR = 2;
    
    const TICKET_TYPE_AUTO = 'auto';
    const TICKET_TYPE_HANDLE = 'handle';
    
    protected $statusRu = [
        self::STATUS_REJECTED => 'отклоненная',
        self::STATUS_COMMON => 'обычная',
        self::STATUS_ASAP => 'срочная',
        self::STATUS_COMPLETED => 'выполненная',
    ];
    
    protected $rentTypeRu = [
        self::RENT_TYPE_DAY => 'суточная',
        self::RENT_TYPE_HOUR => 'часовая',
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'status', 'rent_type', 'lat', 'lon', 'car_id'], 'required'],
            [['type', 'comment'], 'string'],
            [['created_at', 'started_at', 'finished_at'], 'safe'],
            [['status', 'rent_type', 'brigade_id', 'car_id'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['brigade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brigade::className(), 'targetAttribute' => ['brigade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'created_at' => 'Время поступления заявки',
            'started_at' => 'Started At',
            'finished_at' => 'Finished At',
            'status' => 'Статус заявки',
            'rent_type' => 'Тип аренды',
            'comment' => 'Comment',
            'brigade_id' => 'Brigade ID',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'car_id' => 'Автомобиль',
            'placeColumnHtmlFormatted' => 'Расположение',
            'jobsColumnHtmlFormatted' => 'Необходимые работы',
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
    
    public function getCreateStatuses()
    {
        return [
            self::STATUS_COMMON => $this->statusRu[self::STATUS_COMMON],
            self::STATUS_ASAP => $this->statusRu[self::STATUS_ASAP],
        ];
    }
    
    public function getRentTypeRu()
    {
        return $this->rentTypeRu;
    }
    
    public function getStatusRu()
    {
        return $this->statusRu[$this->status];
    }
    
    public function getPlaceColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/ticket/snippets/place_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getRentType()
    {
        return $this->rentTypeRu[$this->rent_type];
    }
    
    public function getJobsColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/ticket/snippets/jobs_col.php', [
            'model' => $this,
        ]);
    }
}
