<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\AppAsset;
use yii\bootstrap\ActiveForm;
use app\widgets\Alert;
$this->title = Yii::$app->name;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="login-page">
<?php $this->beginBody() ?>

<div class="login-box">
    <div class="login-box-body">
        <div id="system-messages" style="opacity: 1;">
            <?= Alert::widget() ?>
        </div>
        <div class="login-logo">
            <?= Yii::$app->name ?>
        </div>
        <p class="login-box-msg">Вход в панель управления</p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>

            <?= $form->field($model, 'username', [
                        'options' => ['class' => 'form-group has-feedback'], 
                        'inputTemplate' => "{input}<span class='glyphicon glyphicon-user form-control-feedback'></span>"
                    ])
                    ->label(false)
                    ->textInput(['autofocus' => true, 'placeholder' => 'Пользователь'])
            ?>

            <?= $form->field($model, 'password', [
                        'options' => ['class' => 'form-group has-feedback'],
                        'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
                    ])
                    ->label(false)
                    ->passwordInput(['placeholder' => 'Пароль'])
            ?>
            
            <div class="form-group text-right">
                <?= Html::a('Забыли пароль?', ['site/password-reset'], ['style' => 'float: left;']) ?>.
                <?= Html::submitButton('Войти', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>