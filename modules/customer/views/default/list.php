<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

use app\modules\api\models\Car;
use app\modules\api\models\Geo;

$this->title = 'Компания каршеринга ' . Yii::$app->user->identity->customerHasUser->customer->title . ' | Панель управления';
$filter_cities = [];
?>

<div class="customer-list-index">
    <div class="customer-list-buttons">
        <?= Html::a('Карта', Url::to(['/']), ['class' => 'btn btn-default']) ?>
        
        <!--<div class="pull-right">
            <?= Html::label('Город:', 'filter-list-index', ['class' => 'control-label']) ?>
            <?= Html::dropDownList(
                'filter_list_index',
                null,
                $filter_cities,
                [
                    'class' => 'form-control sort-dropdown',
                    'style' => 'width: 57%',
                    'id' => 'filter-list-index',
                    'onchange' => '$.pjax.reload({
                        container: "#list-index-pjax",
                        url: "' .  Url::to(['/list']) . '",
                        data: {filter: $(this).val()},
                        type: "POST"
                    });'
                ]
            ) ?>
        </div>-->
    </div>
    
    <?php Pjax::begin(['id' => 'list-index-pjax', 'enablePushState' => false]); ?>
        <div class="list-index-content">
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
                        'headerOptions' => ['width' => '12%'],
                        'contentOptions' => ['width' => '12%'],
                        'content' => function ($data) {
                            return $data->vin;
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
                        'label' => 'Адрес',
                        'headerOptions' => ['width' => '18%'],
                        'contentOptions' => ['width' => '18%'],
                        'content' => function ($data) {
                            $location = new Geo;
                            $location->lon = $data->lon;
                            $location->lat = $data->lat;
                            $location->getData();
                            return $location->address;
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
                            ])
                            . '<br /><br />' . 
                            Html::a('создать заявку', ['/ticket/create', 'car_id' => $data->car_id], [
                                'class' => 'btn btn-default'
                            ]);
                        }
                    ],
                ]
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