<table class="daohe-table daohe-table-border daohe-table-bg">
    <thead>
    <tr>
        <th>文件名</th>
        <!--
        <th width="140">大小(KB)</th>
        -->
        <th width="150">时间</th>
        <th width="120">下载</th>
    </tr>
    </thead>
    <tbody id="attaches__<?=$uniqid?>">
    <?php foreach($bindValues['attaches'] as $attach){ ?>
        <tr id="attach_file_<?=$uniqid?>_<?=$attach['attachment_id']?>">
            <td>
                <a title="<?=$attach['original_name']?>" href="javascript:void(0)" onclick="QT.filePreview(<?=$attach['attachment_id']?>)">
                    <?=$attach['original_name']?>
                </a>
            </td>
            <!--
            <td>
                <?=round($attach['size']/1024,2)?>
            </td>
            -->
            <td>
                <?=substr($attach['entered'],0,16)?>
            </td>
            <td>
                <a class="text-secondary size-MINI fa fa-download" href="<?=$attach['download_url']?>" target="_blank">&nbsp;</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>