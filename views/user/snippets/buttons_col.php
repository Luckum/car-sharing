<?php 

use yii\helpers\Html;
use app\models\User;

?>
<table>
    <tbody>
        <tr class="tr-bottom">
            <td><?= Html::a('задать логин/пароль', ['/user/access', 'id' => $model->id], ['class' => 'btn btn-default']) ?></td>
        </tr>
        <tr>
            <td>
                <?= Html::a(
                    $model->active == User::STATUS_ACTIVE ? 'отключить доступ к системе' : 'включить доступ к системе',
                    ['/user/toggle-active', 'id' => $model->id],
                    [
                        'class' => 'btn btn-default',
                        'data' => [
                            'confirm' => 'Вы уверены?',
                            'method' => 'post',
                        ]
                    ]
                )?>
            </td>
        </tr>
    </tbody>
</table>