<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;         

AppAsset::register($this);

Yii::$app->formatter->locale = 'ru-RU';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="header">
        <?php if (!empty(Yii::$app->user->identity->avatar)): ?>
            <div class="user-avatar pull-left">
                <?= Html::img('/uploads/avatars/' . Yii::$app->user->identity->avatar) ?>
            </div>
        <?php endif; ?>
        <div class="user-title pull-left">
            <?= Yii::$app->user->isGuest ?: Yii::$app->user->identity->roleRu ?>
        </div>
        <div class="system-datetime">
            <div class="system-date"><?= Yii::$app->formatter->asDate('now', 'd MMMM y') ?></div>
            <div class="system-time"><?= Yii::$app->formatter->asDate('now', 'E, HH:mm') ?></div>
        </div>
        <div class="system-buttons pull-right">
            <?= Html::a('Настройки', 'javascript:void();', ['class' => 'btn btn-default']) ?>
            <?= Html::a('Фотографии', 'javascript:void();', ['class' => 'btn btn-default']) ?>
            <?= Html::a('Заявки', 'javascript:void();', ['class' => 'btn btn-default']) ?>
            <?= Html::a('Сформировать отчёт', 'javascript:void();', ['class' => 'btn btn-default']) ?>
            <?= Html::a('Выйти из системы', ['/logout'], ['data-method' => 'post', 'class' => 'btn btn-default']) ?>
        </div>
    </div>

    <div class="container">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<!--<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>-->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
