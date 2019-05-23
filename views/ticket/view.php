<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use app\models\User;
use app\models\Ticket;
use app\models\Brigade;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Ticket */

$title = 'Заявка №' . $model->id;
$this->title = Yii::$app->name . ' | ' . $title;

\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

    <h1><?= Html::encode($title) ?></h1>

    <p>
        <?= Html::a('Назад', ['/ticket/index'], ['class' => 'btn btn-info']) ?>
        <?php if (Yii::$app->user->identity->role == User::ROLE_OPERATOR): ?>
            <?php if ($model->status == Ticket::STATUS_ASAP || $model->status == Ticket::STATUS_COMMON): ?>
                <?= Html::a('Редактировать', ['/ticket/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['/ticket/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить эту заявку?',
                        'method' => 'post',
                    ],
                ]) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (Yii::$app->user->identity->role == User::ROLE_ADMIN || Yii::$app->user->identity->role == User::ROLE_MANAGER): ?>
            <?= Html::a('Назначить', ['/ticket/attach', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'formatter' => [
            'class' => 'yii\i18n\Formatter', 
            'dateFormat' => 'dd/MM/yy в HH:mm',
            'locale' => 'ru'
        ],
        'attributes' => [
            'id',
            'created_at:date',
            [
                'attribute' => 'status',
                'value' => $model->status == Ticket::STATUS_ASAP ? '<span style="color: red;">' . $model->statusRu . '</span>' : $model->statusRu,
                'format' => 'raw',
            ],
            [
                'attribute' => 'rejected_at',
                'format' => 'date',
                'visible' => $model->status == Ticket::STATUS_REJECTED,
            ],
            [
                'attribute' => 'comment',
                'label' => 'Причина отклонения заявки',
                'format' => 'raw',
                'visible' => $model->status == Ticket::STATUS_REJECTED,
            ],
            'carDetailsColumnHtmlFormatted:raw',
            'locationColumnHtmlFormatted:raw',
            [
                'attribute' => 'rent_type',
                'value' => $model->rentType,
            ],
            [
                'attribute' => 'jobsColumnHtmlFormatted',
                'format' => 'raw',
                'value' => function ($data) {
                    $ret = '';
                    foreach ($data->ticketHasJobTypes as $k => $job_type) {
                        $ret .= ++ $k . '. ' . $job_type->jobType->value . '<br />';
                    }
                    
                    return $ret;
                }
            ],
            [
                'label' => 'Время на выполнение работ, часы',
                'value' => $model->jobsTimeFormatted,
            ],
            [
                'attribute' => 'brigade_id',
                'visible' => Yii::$app->user->identity->role == User::ROLE_ADMIN || Yii::$app->user->identity->role == User::ROLE_MANAGER,
                'value' => isset($model->brigade) ? $model->brigade->title . ' (' . $model->brigade->masterName . ')' : 'Не назначена',
            ],
            [
                'attribute' => 'customer_id',
                'visible' => Yii::$app->user->identity->role == User::ROLE_ADMIN || Yii::$app->user->identity->role == User::ROLE_MANAGER,
                'value' => $model->customer->title
            ],
            [
                'attribute' => 'started_at',
                'format' => 'date',
                'visible' => $model->status == Ticket::STATUS_IN_WORK || $model->status == Ticket::STATUS_COMPLETED,
            ],
            [
                'attribute' => 'finished_at',
                'format' => 'date',
                'visible' => $model->status == Ticket::STATUS_COMPLETED,
            ],
            [
                'label' => 'Затраченное время',
                'value' => $model->spentTime,
                'visible' => $model->status == Ticket::STATUS_IN_WORK || $model->status == Ticket::STATUS_COMPLETED,
            ],
            [
                'label' => 'Исполнитель',
                'value' => isset($model->brigade) ? 'Бригада "' . $model->brigade->title . '"' : '',
                'visible' => $model->status == Ticket::STATUS_IN_WORK || $model->status == Ticket::STATUS_COMPLETED,
            ],
            [
                'label' => 'Пробег автомобиля',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::input('text', 'milage', '', ['id' => 'milage', 'placeholder' => 'км', 'class' => 'form-control']);
                },
                'visible' => $model->status == Ticket::STATUS_IN_WORK && Yii::$app->user->identity->role == User::ROLE_BRIGADIER,
            ],
            [
                'label' => 'Уровень топлива в баке',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::input('text', 'fuel', '', ['id' => 'fuel', 'placeholder' => 'литры', 'class' => 'form-control']);
                },
                'visible' => $model->status == Ticket::STATUS_IN_WORK && Yii::$app->user->identity->role == User::ROLE_BRIGADIER,
            ],
        ],
    ]) ?>

</div>

<p>
    <?php if (Yii::$app->user->identity->role == User::ROLE_BRIGADIER): ?>
        <div id="reject-comment-container">
            <?php $form = ActiveForm::begin(['id' => 'reject-comment-frm', 'action' => Url::to(['/ticket/reject', 'id' => $model->id])]); ?>
                
                <?= $form->field($model, 'comment')->textarea(['rows' => 6, 'placeholder' => 'Укажите причину, по которой вы отклонили заявку'])->label(false) ?>
                
            <?php ActiveForm::end(); ?>
        </div>
        <?php if ($model->status == Ticket::STATUS_COMMON || $model->status == Ticket::STATUS_ASAP): ?>
            <?php if (Yii::$app->user->identity->brigadeHasUser->brigade->status == Brigade::STATUS_ONLINE): ?>
                <?= Html::a('Принять заявку', ['/ticket/accept', 'id' => $model->id], ['class' => 'btn btn-success', 'id' => 'accept-btn']) ?>
            <?php endif; ?>
            <?php if (Yii::$app->user->identity->brigadeHasUser->brigade->status != Brigade::STATUS_OFFLINE): ?>
                <?= Html::a('Отклонить', 'javascript:void(0)', ['class' => 'btn btn-warning', 'id' => 'reject-btn']) ?>
            <?php endif; ?>
        <?php endif; ?>
        <?= Html::a('Отправить', 'javascript:void(0)', ['class' => 'btn btn-warning', 'id' => 'send-reject-btn']) ?>
        <?= Html::a('Отмена', 'javascript:void(0)', ['class' => 'btn btn-default', 'id' => 'cancel-reject-btn']) ?>
        <?php if ($model->status == Ticket::STATUS_IN_WORK): ?>
            <?php $form = ActiveForm::begin(['id' => 'close-ticket-frm', 'action' => Url::to(['/ticket/close', 'id' => $model->id]), 'options' => ['enctype' => 'multipart/form-data']]); ?>
        
                <?= $form->field($model, 'milage')->hiddenInput()->label(false) ?>
                
                <?= $form->field($model, 'fuel')->hiddenInput()->label(false) ?>
                
            
                <div class="ticket-photo-upload">
                    <?= $form->field($model_photo, 'photo_file[]')->widget(FileInput::classname(), [
                        'language' => 'ru',
                        'options' => [
                            'multiple' => true,
                            'accept' => 'image/*'
                        ],
                        'pluginOptions' => [
                            'maxFileCount' => 10,
                            'showRemove' => true,
                            'showUpload' => false
                        ]
                    ]); ?>
                </div>
            <?php ActiveForm::end(); ?>
            
            <?= Html::a('Разблокировать авто', 'javascript:void(0)', ['class' => 'btn btn-danger', 'id' => 'unblock-auto-btn']) ?>
            <?= Html::a('Завершить заявку', 'javascript:void(0)', ['class' => 'btn btn-success', 'id' => 'close-ticket-btn']) ?>
        <?php endif; ?>
    <?php endif; ?>
    
    
</p>

<?php $script = <<<JS
$(document).ready(function () {
    $('#car-location-map').on('shown.bs.modal', function (e) {
        $("#map-container").html('');
        ymaps.ready(init);
        function init(){ 
            var myMap = new ymaps.Map("map-container", {
                center: [$(e.relatedTarget).attr("data-lat"), $(e.relatedTarget).attr("data-lon")],
                zoom: 19
            });
            myMap.geoObjects.add(new ymaps.GeoObject(
                {
                    geometry: {
                        type: "Point",
                        coordinates: [$(e.relatedTarget).attr("data-lat"), $(e.relatedTarget).attr("data-lon")]
                    },
                    properties: {
                        iconContent: $(e.relatedTarget).attr("data-model") + " (" + $(e.relatedTarget).attr("data-number") + ")"
                    }
                }, 
                {
                    preset: 'islands#redStretchyIcon'
                }
            ));
        }
    });
    
    $('#reject-btn').click(function () {
        $('#accept-btn').hide();
        $('#reject-btn').hide();
        $('#send-reject-btn').show();
        $('#cancel-reject-btn').show();
        $('#reject-comment-container').show();
    });
    
    $('#cancel-reject-btn').click(function () {
        $('#accept-btn').show();
        $('#reject-btn').show();
        $('#send-reject-btn').hide();
        $('#cancel-reject-btn').hide();
        $('#reject-comment-container').hide();
    });
    
    $('#send-reject-btn').click(function () {
        $('#reject-comment-frm').submit();
    });
    
    $('#close-ticket-btn').click(function () {
        $('#ticket-milage').val($('#milage').val());
        $('#ticket-fuel').val($('#fuel').val());
        $('#close-ticket-frm').submit();
    });
})
JS;
$this->registerJs($script, $this::POS_END);
?>
<?php Modal::begin([
    'id' => 'car-location-map',
    'options' => ['tabindex' => false],
    'size' => 'modal-lg',
    'footer' => '<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">Закрыть</a>'
]); ?>

    <div id="map-container" style="width: 100%; height: 640px;"></div>

<?php Modal::end(); ?>


