<form method="post">
    <table class="table-form" cellpadding="5">
        <tr>
            <td width="120" class="field-label">发生日期</td>
            <td class="field-input">
                <input class="easyui-datebox" name="infos[occur_date]" data-options="editable:false" value="<?=$row['occur_date']?>" />
            </td>
        </tr>
        <tr>
            <td class="field-label">标题</td>
            <td class="field-input">
                <input class="easyui-textbox" name="infos[title]" value="<?=$row['title']?>" data-options="
                            width:'95%',
                            required:true,
                            prompt:'不超过100个字',
                            validType:['length[1,100]']" />
            </td>
        </tr>
        <tr>
            <td class="field-label" valign="top">内容</td>
            <td class="field-input">
                <textarea class="easyui-textbox auto-height" name="infos[entry]" data-options="
                    width:'95%',
                    multiline:true,
                    validType:['length[1,60000]']"><?=$row['entry']?></textarea>
            </td>
        </tr>
        <tr>
            <td class="field-label">附件</td>
            <td>
                <div style="width:100%" class="easyui-panel" data-options="
                    border:false,
                    minimizable:false,
                    maximizable:false,
                    href:'<?=$attachment_url?>'">
                </div>
            </td>
        </tr>
    </table>
</form>
<script>
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