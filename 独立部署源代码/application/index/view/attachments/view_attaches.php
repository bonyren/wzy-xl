<div id="attaches_<?=$uniqid?>">
    <?php foreach($bindValues['attaches'] as $attach){ ?>
        <div id="attach_file_<?=$uniqid?>_<?=$attach['attachment_id']?>" class="attach-box">
            <div class="text-center">
                <a title="<?=$attach['original_name']?>" href="javascript:void(0)" onclick="QT.filePreview(<?=$attach['attachment_id']?>)">
                    <img class="avatar size-XXL" src="<?=$attach['thumbnail_url']?>" />
                </a>
            </div>
            <div class="text-center attach-name">
                <?=$attach['original_name']?>
            </div>
            <div class="text-center attach-size">
                <?=number_format($attach['size'])?> Bytes
            </div>
            <div class="text-center attach-buttons">
                <a class="btn btn-secondary size-MINI fa fa-download" href="<?=$attach['download_url']?>" target="_blank">&nbsp;</a>
            </div>
        </div>
    <?php } ?>
</div>