<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use app\models\Brigade;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Список бригад';
$this->title = Yii::$app->name . ' | ' . $title;

$sort_items = [
    'date' => 'дате добавления',
    'day' => 'кол-ву выполненных заявок за сутки',
    'total' => 'кол-ву выполненных заявок всего',
];
?>
<div class="brigade-index">

    <div class="brigade-index-buttons">
        <?= Html::a('Все', Url::to(['/brigade/index']), ['class' => empty($status) ? 'btn btn-info' : 'btn btn-default']) ?>
        <?= Html::a('На линии', Url::to(['/brigade/index', 'status' => Brigade::STATUS_ONLINE]), ['class' => $status == Brigade::STATUS_ONLINE ? 'btn btn-info' : 'btn btn-default']) ?>
        <?= Html::a('Простой', Url::to(['/brigade/index', 'status' => Brigade::STATUS_PAUSE]), ['class' => $status == Brigade::STATUS_PAUSE ? 'btn btn-info' : 'btn btn-default']) ?>
        <?= Html::a('Офлайн', Url::to(['/brigade/index', 'status' => Brigade::STATUS_OFFLINE]), ['class' => $status == Brigade::STATUS_OFFLINE ? 'btn btn-info' : 'btn btn-default']) ?>
        
        <?= Html::a('Собрать бригаду', ['/brigade/create'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('На главную', ['/'], ['class' => 'btn btn-info']) ?>
        <div class="pull-right">
            <?= Html::label('Сортировать по:', 'sort-brigade-index', ['class' => 'control-label']) ?>
            <?= Html::dropDownList(
                'sort_brigade_index',
                null,
                $sort_items,
                [
                    'class' => 'form-control sort-dropdown',
                    'id' => 'sort-brigade-index',
                    'onchange' => '$.pjax.reload({
                        container: "#brigade-index-pjax",
                        url: "' .  Url::to(['/brigade/index']) . '",
                        data: {sort: $(this).val()},
                        type: "POST"
                    });'
                ]
            ) ?>
        </div>
    </div>
    <?php Pjax::begin(['id' => 'brigade-index-pjax', 'enablePushState' => false]); ?>
        <div class="brigade-index-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'class' => 'table table-fixed'
                ],
                'rowOptions' => function ($model, $key, $index, $grid) {
                    switch ($model->status) {
                        case Brigade::STATUS_ONLINE:
                            $class = 'tr-online';
                        break;
                        case Brigade::STATUS_PAUSE:
                            $class = 'tr-pause';
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
                        'attribute' => 'title',
                        'headerOptions' => ['width' => '5%'],
                        'contentOptions' => ['width' => '5%']
                    ],
                    [
                        'attribute' => 'teamColumnHtmlFormatted',
                        'format' => 'raw',
                        'headerOptions' => ['width' => '15%'],
                        'contentOptions' => ['width' => '15%']
                    ],
                    [
                        'attribute' => 'statusColumnHtmlFormatted',
                        'format' => 'raw',
                        'headerOptions' => ['width' => '9%'],
                        'contentOptions' => ['width' => '9%']
                    ],
                    [
                        'attribute' => 'areaColumnHtmlFormatted',
                        'format' => 'raw',
                        'headerOptions' => ['width' => '13%'],
                        'contentOptions' => ['width' => '13%']
                    ],
                    [
                        'attribute' => 'ticketsColumnHtmlFormatted',
                        'format' => 'raw',
                        'headerOptions' => ['width' => '20%'],
                        'contentOptions' => ['width' => '20%']
                    ],
                    [
                        'attribute' => 'currentTicketColumnHtmlFormatted',
                        'format' => 'raw',
                        'headerOptions' => ['width' => '20%'],
                        'contentOptions' => ['width' => '20%']
                    ],
                    [
                        'attribute' => 'buttonsColumnHtmlFormatted',
                        'format' => 'raw',
                        'label' => 'Действия',
                        'headerOptions' => ['width' => '18%'],
                        'contentOptions' => ['width' => '18%']
                    ]
                    
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