<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Brigade;

$title = 'Назначить заявку №' . $model->id;
$this->title = Yii::$app->name . ' | ' . $title;

$selected = null;
$brigades = ArrayHelper::map(Brigade::find()->all(), 'id', 'descriptor');
?>

<div class="ticket-attach">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', Yii::$app->request->referrer, ['class' => 'btn btn-info']) ?>
    </p>
    
    <div class="ticket-attach-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'brigade_id')->dropDownList($brigades, ['options' => [$selected => ['selected' => true]]]) ?>
        
        <div class="form-group">
            <?= Html::submitButton('Назначить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>