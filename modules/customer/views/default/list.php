<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Компания каршеринга ' . Yii::$app->user->identity->customerHasUser->customer->title . ' | Панель управления';
?>

<div class="customer-list-index">
    <div class="customer-list-buttons">
        <?= Html::a('Карта', Url::to(['/']), ['class' => 'btn btn-default']) ?>
    </div>
    
    <div class="ticket-index-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-fixed'
            ],
            'columns' => [
                [
                    'label' => 'ID',
                    'headerOptions' => ['width' => '10%'],
                    'contentOptions' => ['width' => '10%'],
                    'content' => function ($data) {
                        return $data->car_id;
                    }
                ],
                [
                    'label' => 'Модель',
                    'headerOptions' => ['width' => '16%'],
                    'contentOptions' => ['width' => '16%'],
                    'content' => function ($data) {
                        return $data->model;
                    }
                ],
                [
                    'label' => 'Цвет',
                    'headerOptions' => ['width' => '7%'],
                    'contentOptions' => ['width' => '7%'],
                    'content' => function ($data) {
                        return $data->color;
                    }
                ],
                [
                    'label' => 'Гос. номер',
                    'headerOptions' => ['width' => '9%'],
                    'contentOptions' => ['width' => '9%'],
                    'content' => function ($data) {
                        return $data->gnum;
                    }
                ],
                [
                    'label' => 'VIN',
                    'headerOptions' => ['width' => '17%'],
                    'contentOptions' => ['width' => '17%'],
                    'content' => function ($data) {
                        return $data->vin;
                    }
                ],
                [
                    'label' => 'IMEI',
                    'headerOptions' => ['width' => '17%'],
                    'contentOptions' => ['width' => '17%'],
                    'content' => function ($data) {
                        return $data->imei;
                    }
                ],
                [
                    'label' => 'Топливо',
                    'headerOptions' => ['width' => '7%'],
                    'contentOptions' => ['width' => '7%'],
                    'content' => function ($data) {
                        return $data->fuel . ' / ' . $data->fuelmax;
                    }
                ],
                [
                    'label' => 'Пробег',
                    'headerOptions' => ['width' => '8%'],
                    'contentOptions' => ['width' => '8%'],
                    'content' => function ($data) {
                        return $data->mileage;
                    }
                ],
                [
                    'label' => 'Статус',
                    'headerOptions' => ['width' => '9%'],
                    'contentOptions' => ['width' => '9%'],
                    'content' => function ($data) {
                        return $data->status;
                    }
                ],
            ]
        ]); ?>
        
    </div>
</div>
