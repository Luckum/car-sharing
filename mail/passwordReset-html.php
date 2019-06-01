<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<div class="password-reset">
    <p>Здравствуйте, <?= Html::encode($user->fullName) ?>,</p>

    <p>для доступа к <?= Yii::$app->name ?> используйте следующий пароль: <?= $password ?></p>
</div>
