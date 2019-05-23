<?php

use app\modules\api\models\Car;

$car_loc = Car::instance();
?>

<?= $car->model . ', ' . $car->color ?>
<br />
гос. номер: <?= $car->number ?>
<br />
VIN: <?= $car->vin ?>
<br />
IMEI: <?= $car->imei ?>
<br />
пробег: <?= $car->mileage ?> км.
<br />
топливо: <?= $car->fuelAbs ?> / <?= $car->fuelmax ?> л.
<br />
статус: <?= $car->status ?>