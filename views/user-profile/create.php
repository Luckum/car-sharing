<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserProfile */

$this->title = Yii::$app->name . ' | Профиль пользователя ' . $model_user->username;
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile-create">

    <?= Html::a('Назад', ['/'], ['class' => 'btn btn-info']) ?>
    <h1><?= Html::encode('Профиль пользователя ' . $model_user->username) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
