<?php

namespace app\models;

use Yii;
use app\modules\api\models\Geo;
use app\modules\api\models\Car;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property string $type
 * @property string $created_at
 * @property string $started_at
 * @property string $finished_at
 * @property string $rejected_at
 * @property string $delayed_at
 * @property int $status
 * @property int $rent_type
 * @property string $comment
 * @property int $brigade_id
 * @property string $lat
 * @property string $lon
 * @property int $car_id
 * @property int $customer_id
 * @property double $milage
 * @property double $fuel
 *
 * @property Brigade $brigade
 * @property TicketHasJobType[] $ticketHasJobTypes
 */
class Ticket extends \yii\db\ActiveRecord
{
    const STATUS_REJECTED = 0;
    const STATUS_COMMON = 1;
    const STATUS_ASAP = 2;
    const STATUS_IN_WORK = 3;
    const STATUS_DELAYED = 4;
    const STATUS_COMPLETED = 5;
    
    const RENT_TYPE_DAY = 1;
    const RENT_TYPE_HOUR = 2;
    
    const TICKET_TYPE_AUTO = 'auto';
    const TICKET_TYPE_HANDLE = 'handle';
    
    protected $statusRu = [
        self::STATUS_REJECTED => 'отклоненная',
        self::STATUS_COMMON => 'обычная',
        self::STATUS_ASAP => 'срочная',
        self::STATUS_COMPLETED => 'выполненная',
        self::STATUS_IN_WORK => 'в работе',
        self::STATUS_DELAYED => 'просроченная',
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
            [['type', 'status', 'rent_type', 'lat', 'lon', 'car_id', 'customer_id'], 'required'],
            [['type', 'comment'], 'string'],
            [['created_at', 'started_at', 'finished_at', 'rejected_at', 'delayed_at'], 'safe'],
            [['status', 'rent_type', 'brigade_id', 'car_id', 'customer_id'], 'integer'],
            [['lat', 'lon', 'milage', 'fuel'], 'number'],
            [['brigade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brigade::className(), 'targetAttribute' => ['brigade_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID номер',
            'type' => 'Type',
            'created_at' => 'Время поступления заявки',
            'started_at' => 'Время начала выполнения заявки',
            'finished_at' => 'Время окончания выполнения заявки',
            'rejected_at' => 'Время отклонения заявки',
            'delayed_at' => 'Время',
            'status' => 'Статус заявки',
            'rent_type' => 'Тип аренды',
            'comment' => 'Comment',
            'brigade_id' => 'Бригада',
            'lat' => 'Lat',
            'lon' => 'Lon',
            'car_id' => 'Автомобиль',
            'locationColumnHtmlFormatted' => 'Расположение',
            'jobsColumnHtmlFormatted' => 'Необходимые работы',
            'customer_id' => 'Компания каршеринга',
            'milage' => 'Milage',
            'fuel' => 'Fuel',
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
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
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
    
    public function getLocationColumnHtmlFormatted()
    {
        $location = new Geo;
        $car = new Car;
        
        $car->carId = $this->car_id;
        $car->getData();
        
        $location->ticketId = $this->id;
        $location->lon = $car->lon;
        $location->lat = $car->lat;
        $location->getData();
        
        return Yii::$app->view->renderFile('@app/views/ticket/snippets/location_col.php', [
            'model' => $this,
            'location' => $location,
            'car' => $car,
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
    
    public static function getTicketsForBrigade($brigade_id)
    {
        $ret = [];
        
        $brigade = Brigade::findOne($brigade_id);
        $tickets = self::find()->where(['brigade_id' => $brigade_id])->all();
        if ($tickets) {
            foreach ($tickets as $ticket) {
                $ret[] = $ticket->id;
            }
        }
        
        $tickets = self::find()
            ->where(
                ['AND', 
                    ['IS', 'brigade_id', new \yii\db\Expression('null')], 
                    ['OR', 
                        ['status' => self::STATUS_ASAP],
                        ['status' => self::STATUS_COMMON]
                    ]
                ])
            ->all();
        
        if ($tickets) {
            foreach ($tickets as $ticket) {
                $location = new Geo;
                $car = new Car;
                
                $car->carId = $ticket->car_id;
                $car->getData();
                
                $location->ticketId = $ticket->id;
                $location->lon = $car->lon;
                $location->lat = $car->lat;
                $location->getData();
                
                foreach ($brigade->brigadeHasAreas as $area) {
                    $srch = strpos($location->zip, $area->area->zip);
                    if ($srch !== false && $srch == 0) {
                        $ret[] = $ticket->id;
                    }
                }
            }
        }
        return $ret;
    }
    
    public function getJobsTime()
    {
        $hrs = $mins = 0;
        foreach ($this->ticketHasJobTypes as $job_type) {
            if (is_int($job_type->jobType->job_time)) {
                $hrs += $job_type->jobType->job_time;
            } else {
                $mins += $job_type->jobType->job_time;
            }
        }
        if ($mins > 0 && ($mins - floor($mins)) > 0.6) {
             $mins += 0.4;
        }
        
        return $hrs + $mins;
    }
    
    public function getJobsTimeFormatted()
    {
        list($num_int, $num_float) = explode('.', (string)$this->jobsTime);
        return $num_int . 'ч.' . $num_float . 'м.';
    }
    
    public function getSpentTime()
    {
        $ret = '';
        if ($this->status == self::STATUS_IN_WORK) {
            $end = new \DateTime();
        } else {
            $end = \DateTime::createFromFormat("Y-m-d H:i:s", $this->finished_at);
        }
        
        $start = \DateTime::createFromFormat("Y-m-d H:i:s", $this->started_at);
        $interval = $end->diff($start);
        
        $ret .= $interval->d > 0 ? $interval->d . 'д. ' : '';
        $ret .= $interval->h > 0 ? $interval->h . 'ч. ' : '';
        $ret .= $interval->i > 0 ? $interval->i . 'м. ' : '';
        
        return $ret;
    }
}
