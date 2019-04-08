<?php

namespace app\modules\api\models;

use yii\base\Model;

class Car extends Model
{
    protected $session = 'session_id=1a17ffe1-9146-4f75-9b09-23d7f93a52aa';
    protected $source = 'http://car5.ru/car5/api/cars';
    protected $cars;
    protected $car;
    protected $car_id = '';
    
    public function getData()
    {
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
}