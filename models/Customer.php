<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property int $id
 * @property string $title
 * @property string $subdomain
 * @property string $email
 * @property string $phone
 * @property string $site_url
 * @property string $address_line
 * @property string $logo
 * @property string $created_at
 * 
 * @property CustomerHasUser[] $customerHasUsers
 */
class Customer extends \yii\db\ActiveRecord
{
    public $logo_file;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'email', 'phone', 'subdomain'], 'required'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['email', 'site_url', 'logo'], 'string', 'max' => 50],
            ['email', 'email'],
            ['site_url', 'url'],
            [['phone'], 'string', 'max' => 17],
            [['subdomain'], 'string', 'max' => 20],
            [['address_line'], 'string', 'max' => 255],
            ['logo_file', 'file', 'extensions' => ['png', 'jpg', 'gif'], 'skipOnEmpty' => true, 'minSize' => 120*120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'email' => 'Email',
            'phone' => 'Телефон',
            'site_url' => 'Домашняя страница',
            'address_line' => 'Адрес',
            'logo' => 'Логотип',
            'created_at' => 'Дата добавления',
            'logo_file' => 'Логотип',
            'subdomain' => 'Поддомен',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerHasUsers()
    {
        return $this->hasMany(CustomerHasUser::className(), ['customer_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['customer_id' => 'id']);
    }
    
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if (!empty($this->logo) && file_exists(Yii::getAlias('@webroot') . '/uploads/logos/' . $this->logo)) {
            unlink(Yii::getAlias('@webroot') . '/uploads/logos/' . $this->logo);
        }
        return true;
    }
}
