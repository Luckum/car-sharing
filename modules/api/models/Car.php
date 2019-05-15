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
    
    protected function setParams()
    {
        $api = CustomerApi::find()->where(['customer_id' => Yii::$app->params['model_car_customer']])->one();
        if ($api) {
            $this->source = $api->api_url;
            $this->session = $api->api_url_params;
        }
    }
    
    public function getData()
    {
        $this->setParams();
        
        $url = empty($this->car_id) ? $this->source . '?' . $this->session : $this->source . '/' . $this->car_id . '/?' . $this->session;
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
}