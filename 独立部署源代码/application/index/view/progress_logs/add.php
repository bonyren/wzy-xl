<form id="progressLogsAddForm_<?=$uniqid?>" method="post">
    <table class="table-form" cellpadding="5">
        <tr>
            <td width="120" class="field-label">发生日期</td>
            <td class="field-input">
                <input class="easyui-datebox" name="infos[occur_date]" data-options="editable:false" value="<?=dateFilter($bindValues['curDate'])?>" />
            </td>
        </tr>
        <tr>
            <td class="field-label">标题</td>
            <td class="field-input">
                <input class="easyui-textbox" name="infos[title]" data-options="
                            width:'95%',
                            required:true,
                            prompt:'不超过100个字',
                            validType:['length[1,100]']" />
            </td>
        </tr>
        <tr>
            <td class="field-label">内容</td>
            <td class="field-input">
                <textarea class="easyui-textbox auto-height" name="infos[entry]" data-options="
                    width:'95%',
                    multiline:true,
                    validType:['length[1,60000]']"></textarea>
            </td>
        </tr>
        <tr>
            <td class="field-label">附件</td>
            <td>
                <input type="hidden" id="progressLogsAttacheIds_<?=$uniqid?>" name="infos[attaches]" value=""/>
                <div id="progressLogsAttachsPanel_<?=$uniqid?>" style="width:100%" class="easyui-panel" data-options="border:false,
                    minimizable:false,
                    maximizable:false,
                    href:'<?=$urlHrefs['attachments']?>'">
                </div>
            </td>
        </tr>
    </table>
</form>
<script>
var progressLogsModuleAdd_<?=$uniqid?> = {
    progressLogsAttacheIds:[],
    onAttachmentsUploaded:function(files){
        $.each(files, function(i,v){
            progressLogsModuleAdd_<?=$uniqid?>.progressLogsAttacheIds.push(v.attachment_id);
        });
        $('#progressLogsAttacheIds_<?=$uniqid?>').val(progressLogsModuleAdd_<?=$uniqid?>.progressLogsAttacheIds.join(','));
    }
};
$.parser.onComplete = function(context){
    var txtbox = $(".auto-height");
    if (txtbox.length) {
        $.each(txtbox, function(i,v){
            $(v).textbox('autoHeight');
        })
    }
    $.parser.onComplete = $.noop;
}
</script>