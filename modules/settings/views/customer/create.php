<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */

$title = 'Настройки: добавить компанию каршеринга';
$this->title = Yii::$app->name . ' | ' . $title;
?>
<div class="customer-create">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', ['/settings/customer'], ['class' => 'btn btn-info']) ?></p>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
