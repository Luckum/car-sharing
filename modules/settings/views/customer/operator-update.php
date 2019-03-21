<?php

use yii\helpers\Html;

$title = 'Настройки: компания каршеринга - ' . $model->title . ' - редактировать оператора - ' . $model_user->fullName;
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="customer-create">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', Yii::$app->request->referrer, ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form_operator', [
        'model' => $model,
        'model_user' => $model_user,
    ]) ?>

</div>
