<?php

use yii\helpers\Html;
use yii\helpers\Url;

use app\modules\api\models\Car;

?>

<?php
if ($cars_model) {
    $car_loc = Car::instance();
    $script = '
    $(document).ready(function () {
        $("#map-container").html("");
        ymaps.ready(init);
        function init(){ 
            var myMap = new ymaps.Map("map-container", {
                center: [60.00276950, 30.28266140],
                zoom: 13
            });';
    foreach ($cars_model->cars as $car) {
        $script .= "
            myMap.geoObjects.add(new ymaps.GeoObject(
                {
                    geometry: {
                        type: 'Point',
                        coordinates: [" . $car->lat . ", " . $car->lon . "]
                    },
                    properties: {
                        iconContent: '" . $car->model . " - " . $car->gnum . ", " . round($car->fuel, 2) . "%, " . $car_loc->getStatusRu($car->status) . "',
                        balloonContent: '" . Html::a('создать заявку', ['/ticket/create', 'car_id' => $car->car_id]) . "'
                    }
                }, 
                {
                    preset: 'islands#redStretchyIcon'
                }
            ));";
    }
    $script .= '        
        }
    })';
    $this->registerJs($script, $this::POS_END);
}
 
$this->title = 'Компания каршеринга ' . Yii::$app->user->identity->customerHasUser->customer->title . ' | Панель управления';
?> 
<div class="customer-default-index">
    <?php if ($cars_model): ?>
        <div class="customer-index-buttons">
            <?= Html::a('Список', Url::to(['/list']), ['class' => 'btn btn-default']) ?>
        </div>
        <br />
        <p>При клике на метку вы можете создать заявку</p>
        <div id="map-container" style="width: 100%; height: 640px; margin-top: 25px;"></div>
    <?php endif; ?>
</div>