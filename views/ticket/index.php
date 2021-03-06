<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use app\models\User;
use app\models\Ticket;
use app\modules\api\models\Car;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Заявки';
$this->title = Yii::$app->name . ' | ' . $title;

$sort_items = [
    'urgency' => 'срочности',
    'date' => 'времени поступления',
    'address' => 'адресу',
];
?>
<div class="ticket-index">
    <?php if ($dataProvider): ?>
        <div class="ticket-index-buttons">
            <?php if (Yii::$app->user->identity->role == User::ROLE_OPERATOR): ?>
                <?= Html::a('Создать заявку', Url::to(['/ticket/create']), ['class' => 'btn btn-default']) ?>
            <?php endif; ?>
            <?= Html::a('Все заявки', Url::to(['/ticket/index']), ['class' => empty($status) ? 'btn btn-info' : 'btn btn-default']) ?>
            <?= Html::a('Новые заявки', Url::to(['/ticket/index', 'status' => 'new']), ['class' => $status == 'new' ? 'btn btn-info' : 'btn btn-default']) ?>
            <?= Html::a('Выполненные заявки', Url::to(['/ticket/index', 'status' => Ticket::STATUS_COMPLETED]), ['class' => $status == Ticket::STATUS_COMPLETED ? 'btn btn-info' : 'btn btn-default']) ?>
            <?= Html::a('Отклоненные заявки', Url::to(['/ticket/index', 'status' => Ticket::STATUS_REJECTED]), ['class' => $status == Ticket::STATUS_REJECTED ? 'btn btn-info' : 'btn btn-default']) ?>
            <?= Html::a('Просроченные', Url::to(['/ticket/index', 'status' => Ticket::STATUS_DELAYED]), ['class' => $status == Ticket::STATUS_DELAYED ? 'btn btn-info' : 'btn btn-default']) ?>
            
            <?php if (Yii::$app->user->identity->role != User::ROLE_BRIGADIER && Yii::$app->user->identity->role != User::ROLE_MANAGER && Yii::$app->user->identity->role != User::ROLE_WORKER): ?>
                <?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?>
            <?php endif; ?>
            
            <div class="pull-right">
                <?= Html::label('Группировать по:', 'sort-ticket-index', ['class' => 'control-label']) ?>
                <?= Html::dropDownList(
                    'sort_ticket_index',
                    null,
                    $sort_items,
                    [
                        'class' => 'form-control sort-dropdown',
                        'style' => 'width: 57%',
                        'id' => 'sort-ticket-index',
                        'onchange' => '$.pjax.reload({
                            container: "#ticket-index-pjax",
                            url: "' .  Url::to(['/ticket/index']) . '",
                            data: {sort: $(this).val()},
                            type: "POST"
                        });'
                    ]
                ) ?>
            </div>
        </div>
        
        <?php Pjax::begin(['id' => 'ticket-index-pjax', 'enablePushState' => false]); ?>
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
                            'headerOptions' => ['width' => '13%'],
                            'contentOptions' => ['width' => '13%']
                        ],
                        [
                            'attribute' => 'customer_id',
                            'headerOptions' => ['width' => '11%'],
                            'contentOptions' => ['width' => '11%'],
                            'content' => function ($data) {
                                return $data->customer->title;
                            },
                            'visible' => Yii::$app->user->identity->role == User::ROLE_ADMIN || Yii::$app->user->identity->role == User::ROLE_MANAGER,
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
                                $car = new Car();
                                $car->carId = $data->car_id;
                                $car->getData();
                                return $car->model . " - <br />" . 'гос. номер<br />' . $car->number;
                            }
                        ],
                        [
                            'attribute' => 'locationColumnHtmlFormatted',
                            'format' => 'raw',
                            'headerOptions' => ['width' => '18%'],
                            'contentOptions' => ['width' => '18%']
                        ],
                        [
                            'attribute' => 'rent_type',
                            'headerOptions' => ['width' => '15%'],
                            'contentOptions' => ['width' => '15%'],
                            'content' => function ($data) {
                                return $data->rentType;
                            }
                        ],
                        [
                            'attribute' => 'jobsColumnHtmlFormatted',
                            'format' => 'raw',
                            'headerOptions' => ['width' => '16%'],
                            'contentOptions' => ['width' => '16%']
                        ],
                    ],
                ]); ?>
            </div>
        <?php Pjax::end(); ?>
    </div>
<?php $script = <<<JS
$(document).ready(function () {
    $('#car-location-map').on('shown.bs.modal', function (e) {
        $("#map-container").html('');
        ymaps.ready(init);
        function init(){ 
            var myMap = new ymaps.Map("map-container", {
                center: [$(e.relatedTarget).attr("data-lat"), $(e.relatedTarget).attr("data-lon")],
                zoom: 19
            });
            myMap.geoObjects.add(new ymaps.GeoObject(
                {
                    geometry: {
                        type: "Point",
                        coordinates: [$(e.relatedTarget).attr("data-lat"), $(e.relatedTarget).attr("data-lon")]
                    },
                    properties: {
                        iconContent: $(e.relatedTarget).attr("data-model") + " (" + $(e.relatedTarget).attr("data-number") + ")"
                    }
                }, 
                {
                    preset: 'islands#redStretchyIcon'
                }
            ));
        }
    });
})
JS;
$this->registerJs($script, $this::POS_END);
?>
    <?php Modal::begin([
        'id' => 'car-location-map',
        'options' => ['tabindex' => false],
        'size' => 'modal-lg',
        'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">Закрыть</a>'
    ]); ?>

        <div id="map-container" style="width: 100%; height: 640px;"></div>

    <?php Modal::end(); ?>
<?php endif; ?>

