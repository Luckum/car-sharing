<?php

namespace app\modules\api\models;

use yii\base\Model;

use app\models\Ticket;

class Geo extends Model
{
    protected $source = 'https://geocode-maps.yandex.ru/1.x/?geocode=';
    protected $lon;
    protected $lat;
    protected $ticket_id;
    protected $address_text;
    protected $zip;
    protected $city;
    
    public function getData()
    {
        $url = $this->source . $this->lon . ',' . $this->lat;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        curl_close($curl);
        
        $this->setData($result);
    }
    
    public function setTicketId($id)
    {
        $this->ticket_id = $id;
    }
    
    public function setLon($lon)
    {
        $this->lon = $lon;
        if (!empty($this->ticket_id)) {
            $ticket = Ticket::findOne($this->ticket_id);
            if ($ticket->status != Ticket::STATUS_COMPLETED) {
                $ticket->lon = $lon;
                $ticket->save();
            } else {
                $this->lon = $ticket->lon;
            }
        }
    }
    
    public function setLat($lat)
    {
        $this->lat = $lat;
        if (!empty($this->ticket_id)) {
            $ticket = Ticket::findOne($this->ticket_id);
            if ($ticket->status != Ticket::STATUS_COMPLETED) {
                $ticket->lat = $lat;
                $ticket->save();
            } else {
                $this->lat = $ticket->lat;
            }
        }
    }
    
    protected function setData($data)
    {
        $xml = simplexml_load_string($data);
        $this->address_text = $xml->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->Address->formatted;
        $this->zip = $xml->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->Address->postal_code;
        foreach ($xml->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->Address->Component as $component) {
            if ($component->kind == 'locality') {
                $this->city = strval($component->name);
            }
        }
    }
    
    public function getAddress()
    {
        return $this->address_text;
    }
    
    public function getZip()
    {
        return $this->zip;
    }
    
    public function getCity()
    {
        return $this->city;
    }
}