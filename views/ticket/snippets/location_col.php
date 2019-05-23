<table>
    <tbody>
        <tr class="tr-top">
            <td><?= $location->address ?></td>
        </tr>
        <tr>
            <td><a class="btn btn-default" href="javascript:void(0)" data-toggle="modal" data-target="#car-location-map" data-lat="<?= $model->lat ?>" data-lon="<?= $model->lon ?>" data-model="<?= $car->model ?>" data-number="<?= $car->number ?>">показать на карте</a></td>
        </tr>
        <tr>
            <td><a class="btn btn-default" href="yandexnavi://build_route_on_map?lat_to=<?= $model->lat ?>&lon_to=<?= $model->lon ?>">построить маршрут в Яндекс.Навигаторе</a></td>
        </tr>
    </tbody>
</table>