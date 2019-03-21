<?php

namespace app\modules\api\models;

use yii\base\Model;

class Car extends Model
{
    protected $source = 'http://car5.ru/car5/api/cars?session_id=1a17ffe1-9146-4f75-9b09-23d7f93a52aa';
    protected $cars;
    
    public function __construct()
    {
        $this->getData();
    }
    
    protected function getData()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->source);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        curl_close($curl);
        
        $this->cars = json_decode($result);
    }
    
    public function getModelWithNumber()
    {
        $ret = [];
        foreach ($this->cars->cars as $car) {
            $ret[$car->car_id] = $car->model . ' (' . $car->gnum . ')';
        }
        
        return $ret;
    }
    
    public static function getLatById($car_id)
    {
        return '55.7181206';
    }
    
    public static function getLonById($car_id)
    {
        return '37.3970528';
    }
    
    public static function getModelById($car_id)
    {
        return 'Nissan X-Trail';
    }
    
    public static function getNumberById($car_id)
    {
        return 'М 496 АР 799';
    }
}