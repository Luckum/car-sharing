<?php

use yii\helpers\Html;

$title = 'Настройки: компания каршеринга - ' . $model->title . ' - добавить оператора';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="customer-create">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', ['operator', 'id' => $model->id], ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form_operator', [
        'model' => $model,
        'model_user' => $model_user,
    ]) ?>

</div>
