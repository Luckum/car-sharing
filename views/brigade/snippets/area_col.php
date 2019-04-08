<table>
    <tbody>
        <tr class="tr-top">
            <td>
                <?php foreach ($model->brigadeHasAreas as $area): ?>
                    <?= $area->area->titleWithZip ?>
                    <br />
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>