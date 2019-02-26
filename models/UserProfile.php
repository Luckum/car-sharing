<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_profile".
 *
 * @property int $id
 * @property int $user_id
 * @property string $phone
 * @property string $city
 * @property string $comment
 * @property string $address_line
 * @property string $whatsapp_account
 * @property string $telegram_account
 *
 * @property User $user
 */
class UserProfile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'phone'], 'required'],
            [['user_id'], 'integer'],
            [['comment'], 'string'],
            [['phone'], 'string', 'max' => 17],
            [['city', 'whatsapp_account', 'telegram_account'], 'string', 'max' => 45],
            [['address_line'], 'string', 'max' => 255],
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
            'user_id' => 'User ID',
            'phone' => 'Телефон',
            'city' => 'Город',
            'comment' => 'Комментарий',
            'address_line' => 'Адрес',
            'whatsapp_account' => 'Whatsapp аккаунт',
            'telegram_account' => 'Telegram аккаунт',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
