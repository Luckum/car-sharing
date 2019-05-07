<?php $script = '
$(document).ready(function () {
    $("#map-container").html("");
    ymaps.ready(init);
    function init(){ 
        var myMap = new ymaps.Map("map-container", {
            center: [60.00276950, 30.28266140],
            zoom: 13
        });';

foreach ($cars_model->cars as $car) {
    $script .= '
        myMap.geoObjects.add(new ymaps.GeoObject(
            {
                geometry: {
                    type: "Point",
                    coordinates: [' . $car->lat . ', ' . $car->lon . ']
                },
                properties: {
                    iconContent: "' . $car->model . ' - гос. номер ' . $car->gnum . '"
                }
            }, 
            {
                preset: "islands#redStretchyIcon"
            }
        ));';
}
$script .= '        
    }
})';
$this->registerJs($script, $this::POS_END);
?> 
<div class="customer-default-index">
    <div id="map-container" style="width: 100%; height: 640px;"></div>
</div>
