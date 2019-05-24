<?php

use yii\helpers\Html;
use app\models\User;

switch ($model->role):
    case User::ROLE_ADMIN:
        echo $model->getRoleRu();
    break;
    case User::ROLE_OPERATOR:
?>
        <div>Каршеринг <?= $model->customerHasUser->customer->title ?></div>
        <div>Представитель: <?= $model->fullName ?></div>
<?php
    break;
    case User::ROLE_BRIGADIER:
        if (isset($model->brigadeHasUser)):
?>
            <div>Бригада: <?= $model->brigadeHasUser->brigade->title ?> - <?= Html::a('состав бригады', ['/brigade/view', 'id' => $model->brigadeHasUser->brigade->id]) ?></div>
<?php
        endif;
?>
        <div>Статус: <?= $model->brigadeHasUser->brigade->statusRu ?></div>
        <div><?= $model->getRoleRu() ?>: <?= $model->fullName ?></div>
<?php
        if (isset($model->brigadeHasUser)):
            foreach ($model->brigadeHasUser->brigade->brigadeHasAreas as $area):
?>
                <div><?= $area->area->title ?></div>
<?php
            endforeach;
        endif;
    break;
    case User::ROLE_MANAGER:
?>
        <div><?= $model->fullName ?></div>
        <div><?= $model->getRoleRu() ?></div>
<?php
    break;
    case User::ROLE_WORKER:
        if (isset($model->brigadeHasUser)):
?>
            <div>Бригада: <?= $model->brigadeHasUser->brigade->title ?> - <?= Html::a('состав бригады', ['/brigade/view', 'id' => $model->brigadeHasUser->brigade->id]) ?></div>
<?php
        endif;
?>
        <div>Статус: <?= $model->brigadeHasUser->brigade->statusRu ?></div>
        <div><?= $model->getRoleRu() ?>: <?= $model->fullName ?></div>
<?php
        if (isset($model->brigadeHasUser)):
            foreach ($model->brigadeHasUser->brigade->brigadeHasAreas as $area):
?>
                <div><?= $area->area->title ?></div>
<?php
            endforeach;
        endif;
    break;
endswitch;
        
