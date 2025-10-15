<?php foreach($bindValues['attaches'] as $attach){ ?>
    <button type="button" class="btn btn-link" title="<?=$attach['original_name']?>" onclick="QT.filePreview(<?=$attach['attachment_id']?>)"><?=$attach['original_name']?></button>
<?php } ?>