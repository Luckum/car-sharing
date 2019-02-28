<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "brigade_has_user".
 *
 * @property int $id
 * @property int $user_id
 * @property int $brigade_id
 * @property int $is_master
 *
 * @property Brigade $brigade
 * @property User $user
 */
class BrigadeHasUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brigade_has_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'brigade_id', 'is_master'], 'required'],
            [['user_id', 'brigade_id', 'is_master'], 'integer'],
            [['brigade_id'], 'exist', 'skipOnError' => true, 'targetClass' => Brigade::className(), 'targetAttribute' => ['brigade_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Рабочие',
            'brigade_id' => 'Brigade ID',
            'is_master' => 'Бригадир',
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
