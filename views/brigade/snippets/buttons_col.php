<?php 

use yii\helpers\Html;
use app\models\Brigade;

?>
<table>
    <tbody>
        <tr class="tr-bottom">
            <td></td>
        </tr>
        <tr>
            <td>
            <?php if ($model->status == Brigade::STATUS_ONLINE || $model->status == Brigade::STATUS_PAUSE): ?>
                <?= Html::a(
                    'снять с линии',
                    ['/brigade/set-offline', 'id' => $model->id],
                    [
                        'class' => 'btn btn-default',
                        'data' => [
                            'confirm' => 'Вы уверены?',
                            'method' => 'post',
                        ]
                    ]
                ) ?>
            <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>