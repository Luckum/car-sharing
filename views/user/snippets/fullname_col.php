<?php

use yii\helpers\Html;

?>

<table>
    <tbody>
        <tr class="tr-top">
            <td><?= $model->fullName ?></td>
        </tr>
        <tr>
            <td><?= Html::a('посмотреть карточку', ['/user/view', 'id' => $model->id], ['class' => 'btn btn-default']) ?></td>
        </tr>
    </tbody>
</table>