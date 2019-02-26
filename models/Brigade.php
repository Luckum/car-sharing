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
 * @property int $area_id
 * @property string $created_at
 *
 * @property Area $area
 * @property BrigadeHasUser[] $brigadeHasUsers
 * @property BrigadeStatus[] $brigadeStatuses
 * @property Ticket[] $tickets
 */
class Brigade extends \yii\db\ActiveRecord
{
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
            [['title', 'status', 'active', 'area_id'], 'required'],
            [['status'], 'string'],
            [['area_id'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['active'], 'string', 'max' => 1],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'status' => 'Status',
            'active' => 'Active',
            'area_id' => 'Area ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['id' => 'area_id']);
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
}
