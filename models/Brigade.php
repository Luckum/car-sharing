<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brigade".
 *
 * @property int $id
 * @property string $title
 * @property string $status
 * @property int $active
 * @property string $created_at
 *
 * @property BrigadeHasUser[] $brigadeHasUsers
 * @property BrigadeStatus[] $brigadeStatuses
 * @property BrigadeHasArea[] $brigadeHasAreas
 * @property Ticket[] $tickets
 */
class Brigade extends \yii\db\ActiveRecord
{
    const STATUS_ONLINE = 'online';
    const STATUS_OFFLINE = 'offline';
    const STATUS_PAUSE = 'pause';
    
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    
    protected $statusRu = [
        self::STATUS_ONLINE => 'На линии',
        self::STATUS_OFFLINE => 'Офлайн',
        self::STATUS_PAUSE => 'Простой',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brigade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'status', 'active'], 'required'],
            [['status'], 'string'],
            [['active'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
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
            'status' => 'Статус',
            'active' => 'Активна',
            'created_at' => 'Дата создания',
            'teamColumnHtmlFormatted' => 'Состав бригады',
            'statusColumnHtmlFormatted' => 'Статус',
            'areaColumnHtmlFormatted' => 'Квадрант',
            'ticketsColumnHtmlFormatted' => 'Выполненных заявок за сутки / всего',
            'currentTicketColumnHtmlFormatted' => 'Текущая заявка',
        ];
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrigadeHasAreas()
    {
        return $this->hasMany(BrigadeHasArea::className(), ['brigade_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrigadeHasUsers()
    {
        return $this->hasMany(BrigadeHasUser::className(), ['brigade_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBrigadeStatuses()
    {
        return $this->hasMany(BrigadeStatus::className(), ['brigade_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['brigade_id' => 'id']);
    }
    
    public function getCompletedTicketsForDay()
    {
        $date = date("Y-m-d");
        return Ticket::find()
            ->where(['brigade_id' => $this->id, 'status' => Ticket::STATUS_COMPLETED])
            ->andWhere("DATE_FORMAT(finished_at, '%Y-%m-%d') = '$date'")
            ->count();
    }
    
    public function getCompletedTotal()
    {
        return Ticket::find()
            ->where(['brigade_id' => $this->id, 'status' => Ticket::STATUS_COMPLETED])
            ->count();
    }
    
    public function getTeamColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/brigade/snippets/team_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getStatusColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/brigade/snippets/status_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getStatusRu()
    {
        return $this->statusRu[$this->status];
    }
    
    public function getAreaColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/brigade/snippets/area_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getTicketsColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/brigade/snippets/tickets_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getCurrentTicketColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/brigade/snippets/current_ticket_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getButtonsColumnHtmlFormatted()
    {
        return Yii::$app->view->renderFile('@app/views/brigade/snippets/buttons_col.php', [
            'model' => $this,
        ]);
    }
    
    public function getDescriptor()
    {
        return $this->title . ' (' . $this->masterName . ') - ' . $this->getStatusRu() . ($this->status != self::STATUS_OFFLINE ? ' - ' . $this->jobStatus : '');
    }
    
    public function getMasterName()
    {
        $brigade = BrigadeHasUser::find()->where(['brigade_id' => $this->id, 'is_master' => 1])->one();
        return $brigade->user->fullName;
    }
    
    public function getJobStatus()
    {
        if (Ticket::find()->where(['brigade_id' => $this->id, 'status' => Ticket::STATUS_IN_WORK])->exists()) {
            return 'занята';
        }
        return 'свободна';
    }
    
    public function getIsBusy()
    {
        if (Ticket::find()->where(['brigade_id' => $this->id, 'status' => Ticket::STATUS_IN_WORK])->exists()) {
            return true;
        }
        return false;
    }
}
