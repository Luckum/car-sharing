<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;

use app\modules\api\models\Car;

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
                    'headerOptions' => ['width' => '8%'],
                    'contentOptions' => ['width' => '8%'],
                    'content' => function ($data) {
                        return $data->car_id;
                    }
                ],
                [
                    'label' => 'Модель',
                    'headerOptions' => ['width' => '14%'],
                    'contentOptions' => ['width' => '14%'],
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
                    'headerOptions' => ['width' => '15%'],
                    'contentOptions' => ['width' => '15%'],
                    'content' => function ($data) {
                        return $data->vin;
                    }
                ],
                [
                    'label' => 'IMEI',
                    'headerOptions' => ['width' => '15%'],
                    'contentOptions' => ['width' => '15%'],
                    'content' => function ($data) {
                        return $data->imei;
                    }
                ],
                [
                    'label' => 'Топливо',
                    'headerOptions' => ['width' => '7%'],
                    'contentOptions' => ['width' => '7%'],
                    'content' => function ($data) {
                        return $data->fuelAbs . ' / ' . $data->fuelmax . ' (' . round($data->fuel, 2) . '%)';
                    }
                ],
                [
                    'label' => 'Пробег, км.',
                    'headerOptions' => ['width' => '7%'],
                    'contentOptions' => ['width' => '7%'],
                    'content' => function ($data) {
                        return $data->mileage;
                    }
                ],
                [
                    'label' => 'Статус',
                    'headerOptions' => ['width' => '9%'],
                    'contentOptions' => ['width' => '9%'],
                    'content' => function ($data) {
                        $car_loc = Car::instance();
                        return $car_loc->getStatusRu($data->status);
                    }
                ],
                [
                    'label' => 'Действия',
                    'headerOptions' => ['width' => '9%'],
                    'contentOptions' => ['width' => '9%'],
                    'content' => function ($data) {
                        return Html::a('показать на карте', 'javascript:void(0)', [
                            'class' => 'btn btn-default',
                            'data-toggle' => 'modal',
                            'data-target' => '#car-location-map',
                            'data-lat' => $data->lat,
                            'data-lon' => $data->lon,
                            'data-model' => $data->model,
                            'data-number' => $data->gnum
                        ]);
                    }
                ],
            ]
        ]); ?>
        
    </div>
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