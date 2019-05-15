<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "customer_api".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $api_url
 * @property string $api_url_params
 *
 * @property Customer $customer
 */
class CustomerApi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_api';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'api_url'], 'required'],
            [['customer_id'], 'integer'],
            [['api_url', 'api_url_params'], 'string', 'max' => 150],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'api_url' => 'API URL без параметров (до знака ?)',
            'api_url_params' => 'API URL параметры, если есть (после знака ?)',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->api_url = str_replace('?', '', $this->api_url);
            $this->api_url_params = str_replace('?', '', $this->api_url_params);

            return true;
        } else {
            return false;
        }
    }
}
