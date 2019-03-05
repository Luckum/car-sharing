<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\Brigade;
use app\models\User;

?>

<?php if ($model->role != User::ROLE_MANAGER): ?>
    <table>
        <tbody>
            <tr class="tr-top">
                <td>
                    <?= isset($model->brigadeHasUser->brigade) ? $model->brigadeHasUser->brigade->completedTicketsForDay : 0 ?>
                    &nbsp;/&nbsp;
                    <?= isset($model->brigadeHasUser->brigade) ? $model->brigadeHasUser->brigade->completedTotal : 0 ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= Html::dropDownList(
                        'brigade',
                        (isset($model->brigadeHasUser->brigade->id) ? $model->brigadeHasUser->brigade->id : 0),
                        ArrayHelper::merge(['0' => 'Нет привязки'], ArrayHelper::map(Brigade::find()->all(), 'id', 'title')),
                        ['onchange' => 'setBrigade(this)', 'data-user' => $model->id, 'class' => 'form-control']
                    )?>
                </td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>