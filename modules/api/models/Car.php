<?php

namespace app\modules\api\models;

use yii\base\Model;
use Yii;

use app\models\CustomerApi;

class Car extends Model
{
    protected $session = '';
    protected $source = '';
    protected $cars;
    protected $car;
    protected $car_id = '';
    
    protected $statusRu = [
        '1' => 'Свободен',
        '2' => 'Забронирован',
        '3' => 'Арендован',
        '4' => 'Служебное использование',
        '5' => 'Мойка',
        '6' => 'PUMP',
        '7' => 'Ремонт',
        '8' => 'Низкий заряд аккум.',
        '9' => 'Промо',
        '10' => 'Шиномонтаж сезон',
        '11' => 'Шрафстоянка',
        '12' => 'Бизнес-онлайн',
        '13' => 'Диагностика',
        '14' => 'Топливовбак',
        '15' => 'Требуется заправка',
        '16' => 'Шиномонтаж срочно',
        '17' => 'Замечания по состоянию',
        '18' => 'PROIL',
        '19' => 'Продажа',
        '20' => 'Принуд. заправка',
        '21' => 'Аренда Авто',
    ];
    
    protected function setParams()
    {
        $api = CustomerApi::find()->where(['customer_id' => Yii::$app->params['model_car_customer']])->one();
        if ($api) {
            $this->source = $api->api_url;
            $this->session = $api->api_url_params;
            return true;
        }
        return false;
    }
    
    public function getData()
    {
        if (!$this->setParams()) {
            return false;
        }
        
        $url = empty($this->car_id) ? $this->source . '/all/' . '?' . $this->session : $this->source . '/' . $this->car_id . '/?' . $this->session;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        curl_close($curl);
        
        if (empty($this->car_id)) {
            $this->cars = json_decode($result);
        } else {
            $this->car = json_decode($result);
        }
        
        return true;
    }
    
    public function getModelWithNumber()
    {
        $ret = [];
        foreach ($this->cars->cars as $car) {
            $ret[$car->car_id] = $car->model . ' (' . $car->gnum . ')';
        }
        
        return $ret;
    }
    
    public function getCars()
    {
        return $this->cars->cars;
    }
    
    public function getLat()
    {
        return $this->car->cars[0]->lat;
    }
    
    public function getLon()
    {
        return $this->car->cars[0]->lon;
    }
    
    public function getModel()
    {
        return $this->car->cars[0]->model;
    }
    
    public function getNumber()
    {
        return $this->car->cars[0]->gnum;
    }
    
    public function setCarId($id)
    {
        $this->car_id = $id;
    }
    
    public function getVin()
    {
        return $this->car->cars[0]->vin;
    }
    
    public function getFuel()
    {
        return $this->car->cars[0]->fuel;
    }
    
    public function getColor()
    {
        return $this->car->cars[0]->color;
    }
    
    public function getImei()
    {
        return $this->car->cars[0]->imei;
    }
    
    public function getMileage()
    {
        return $this->car->cars[0]->mileage;
    }
    
    public function getFuelAbs()
    {
        return $this->car->cars[0]->fuelAbs;
    }
    
    public function getFuelmax()
    {
        return $this->car->cars[0]->fuelmax;
    }
    
    public function getStatusRu($status)
    {
        return $this->statusRu[$status];
    }
}