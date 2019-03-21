<table>
    <tbody>
        <tr class="tr-top">
            <td>
                <?php foreach ($model->ticketHasJobTypes as $k => $job_type): ?>
                    <?= ++ $k ?>. <?= $job_type->jobType->value ?><br />
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td><a class="btn btn-default" href="javascript:void(0)">посмотреть заявку</a></td>
        </tr>
    </tbody>
</table>