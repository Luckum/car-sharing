<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\User;
use app\models\Ticket;
use app\modules\api\models\Car;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Заявки';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="ticket-index">
    <div class="ticket-index-buttons">
        <?php if (Yii::$app->user->identity->role == User::ROLE_OPERATOR): ?>
            <?= Html::a('Создать заявку', Url::to(['/ticket/create']), ['class' => 'btn btn-default']) ?>
        <?php endif; ?>
        <?= Html::a('Все заявки', Url::to(['/ticket/index']), ['class' => 'btn btn-default']) ?>
        <?= Html::a('Новые заявки', Url::to(['/']), ['class' => 'btn btn-default']) ?>
        <?= Html::a('Выполненные заявки', Url::to(['/']), ['class' => 'btn btn-default']) ?>
        <?= Html::a('Отклоненные заявки', Url::to(['/']), ['class' => 'btn btn-default']) ?>
        <?= Html::a('Просроченные', Url::to(['/']), ['class' => 'btn btn-default']) ?>
        
        <?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?>
    </div>
    
    <div class="ticket-index-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'formatter' => [
                'class' => 'yii\i18n\Formatter', 
                'dateFormat' => 'dd/MM/yy в HH:mm',
                'locale' => 'ru'
            ],
            'tableOptions' => [
                'class' => 'table table-fixed'
            ],
            'rowOptions' => function ($model, $key, $index, $grid) {
                switch ($model->status) {
                    case Ticket::STATUS_ASAP:
                        $class = 'tr-asap';
                    break;
                    case Ticket::STATUS_COMPLETED:
                        $class = 'tr-completed';
                    break;
                    case Ticket::STATUS_COMMON:
                        $class = 'tr-online';
                    break;
                    default:
                        $class = 'tr-default';
                }
                
                return [
                    'key' => $key,
                    'index' => $index,
                    'class' => $class
                ];
            },
            'columns' => [
                [
                    'attribute' => 'id',
                    'headerOptions' => ['width' => '5%'],
                    'contentOptions' => ['width' => '5%']
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'date',
                    'headerOptions' => ['width' => '15%'],
                    'contentOptions' => ['width' => '15%']
                ],
                [
                    'attribute' => 'status',
                    'headerOptions' => ['width' => '9%'],
                    'contentOptions' => ['width' => '9%'],
                    'content' => function ($data) {
                        return $data->statusRu;
                    }
                ],
                [
                    'attribute' => 'car_id',
                    'headerOptions' => ['width' => '13%'],
                    'contentOptions' => ['width' => '13%'],
                    'content' => function ($data) {
                        return Car::getModelById($data->car_id) . " - <br />" . 'гос. номер<br />' . Car::getNumberById($data->car_id);
                    }
                ],
                [
                    'attribute' => 'placeColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '20%'],
                    'contentOptions' => ['width' => '20%']
                ],
                [
                    'attribute' => 'rent_type',
                    'headerOptions' => ['width' => '20%'],
                    'contentOptions' => ['width' => '20%'],
                    'content' => function ($data) {
                        return $data->rentType;
                    }
                ],
                [
                    'attribute' => 'jobsColumnHtmlFormatted',
                    'format' => 'raw',
                    'headerOptions' => ['width' => '18%'],
                    'contentOptions' => ['width' => '18%']
                ],
            ],
        ]); ?>
    </div>
</div>
