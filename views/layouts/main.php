<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;
use yii\bootstrap\ButtonDropdown;

use app\models\User;         
use app\models\Brigade;         

AppAsset::register($this);

Yii::$app->formatter->locale = 'ru-RU';

if (Yii::$app->getSession()->getAllFlashes()) {
    $this->registerJs("$('#system-messages').fadeIn().animate({opacity: 1.0}, 4000). fadeOut('slow');");
}
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
        <?php if (!empty(Yii::$app->user->identity->customerHasUser->customer->logo)): ?>
            <div class="user-avatar pull-left">
                <?= Html::img('/uploads/logos/' . Yii::$app->user->identity->customerHasUser->customer->logo) ?>
            </div>
        <?php endif; ?>
        
        <div class="user-title pull-left">
            <?= Yii::$app->user->identity->headerTitle ?>
        </div>
        <div class="system-datetime">
            <div class="system-date"></div>
            <div class="system-time"></div>
        </div>
        <div class="system-buttons pull-right">
            <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN): ?>
                <?= ButtonDropdown::widget([
                        'label' => 'Настройки',
                        'options' => [
                            'class' => 'btn btn-default',
                        ],
                        'dropdown' => [
                            'items' => [
                                [
                                    'label' => 'Квадранты',
                                    'url' => Url::to(['/settings/area'])
                                ],
                                [
                                    'label' => 'Компании каршеринга',
                                    'url' => Url::to(['/settings/customer'])
                                ],
                                [
                                    'label' => 'Виды работ',
                                    'url' => Url::to(['/settings/job-type'])
                                ],
                            ]
                        ]
                ]); ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN || Yii::$app->user->identity->role == User::ROLE_MANAGER): ?>
                <?= Html::a('Фотографии', 'javascript:void();', ['class' => 'btn btn-default']) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->identity->role !== User::ROLE_BRIGADIER && Yii::$app->user->identity->role !== User::ROLE_WORKER): ?>
                <?php if (Yii::$app->controller->id != 'ticket'): ?>
                    <?= Html::a('Заявки', ['/ticket/index'], ['class' => 'btn btn-default']) ?>
                <?php else: ?>
                    <?php if (Yii::$app->user->identity->role == User::ROLE_MANAGER): ?>
                        <?= Html::a('Бригады', ['/brigade/index'], ['class' => 'btn btn-default']) ?>
                    <?php elseif (Yii::$app->user->identity->role == User::ROLE_OPERATOR): ?>
                        <?= Html::a('Карта', ['/'], ['class' => 'btn btn-default']) ?>
                    <?php endif; ?>
                <?php endif; ?>
                <?= Html::a('Сформировать отчёт', 'javascript:void();', ['class' => 'btn btn-default']) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->identity->role == User::ROLE_BRIGADIER): ?>
                <?= Html::a('Позвонить в офис', 'javascript:void();', ['class' => 'btn btn-default']) ?>
                <?php if (Yii::$app->user->identity->brigadeHasUser->brigade->status == Brigade::STATUS_ONLINE): ?>
                    <?= Html::a('Уйти с линии', ['/brigade/set-pause', 'id' => Yii::$app->user->identity->brigadeHasUser->brigade_id], ['class' => 'btn btn-default']) ?>
                <?php elseif (Yii::$app->user->identity->brigadeHasUser->brigade->status == Brigade::STATUS_PAUSE): ?>
                    <?= Html::a('Вернуться на линию', ['/brigade/set-online', 'id' => Yii::$app->user->identity->brigadeHasUser->brigade_id], ['class' => 'btn btn-default']) ?>
                <?php endif; ?>
            <?php endif; ?>
            <?= Html::a('Выйти из системы', ['/logout'], ['data-method' => 'post', 'class' => 'btn btn-default']) ?>
            
            <div id="system-messages" style="opacity: 1; display: none">
                <?= Alert::widget() ?>
            </div>
        </div>
    </div>

    <div class="container">
        
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
