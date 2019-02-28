<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$title = 'Задать логин/пароль: ' . $model->fullName;
$this->title = Yii::$app->name . ' | ' . $title;
?>

<div class="user-access">

    <h1><?= Html::encode($title) ?></h1>
    
    <p><?= Html::a('Назад', ['/'], ['class' => 'btn btn-info']) ?></p>
    
    <?php $form = ActiveForm::begin(); ?>
    
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    
        <p>Введите пароль только если хотите сменить его. Оставьте поля пустыми, если не хотите изменений.</p>
        <div class="form-group">
            <label class="control-label" for="user-new-password">Пароль</label>
            <input id="user-new-password" class="form-control" name="new_password" type="password" maxlength="255">
        </div>
        
        <div class="form-group">
            <label class="control-label" for="user-new-password-repeat">Пароль еще раз</label>
            <input id="user-new-password-repeat" class="form-control" name="new_password_repeat" type="password" maxlength="255">
        </div>
        
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
        
    <?php ActiveForm::end(); ?>
    
</div>