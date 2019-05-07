<?php if ($model->currentTicket): ?>
    <table>
        <tbody>
            <tr class="tr-top">
                <td><?= '№ ' . $model->currentTicket->id ?></td>
            </tr>
            <tr>
                <td><a class="btn btn-default" href="javascript:void(0)" data-toggle="modal" data-target="#car-location-map" data-lat="<?= $model->currentTicket->lat ?>" data-lon="<?= $model->currentTicket->lon ?>" data-model="<?= $car->model ?>" data-number="<?= $car->number ?>">показать на карте</a></td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>