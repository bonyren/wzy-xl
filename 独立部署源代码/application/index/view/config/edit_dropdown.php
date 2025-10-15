<form id="F<?=UNIQID?>">
	<table class="table-form" cellpadding="5">
		<tr>
			<td width="100" class="field-label">字段</td>
			<td><input class="easyui-textbox" required="true" name="field" value="<?=$row['field']?>" style="width:100%"></td>
		</tr>
		<tr>
			<td class="field-label">描述</td>
			<td><input class="easyui-textbox" name="description" value="<?=$row['description']?>" style="width:100%"></td>
		</tr>
        <tr>
            <td colspan="4" class="form-tip">选项</td>
        </tr>
        <tr>
            <td colspan="2">
                <table id="T<?=UNIQID?>" class="table-form" cellpadding="5">
                    <thead>
                    <tr class="field-label">
                        <td width="33.3%">选项名</td>
                        <td width="33.3%">选项值</td>
                        <td width="33.3%"></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($row['items'] as $i=>$item):  ?>
                        <tr>
                            <td><input type="text" name="items[<?=$i?>][label]" value="<?=$item['label']?>" style="width:95%;"></td>
                            <td><input type="text" name="items[<?=$i?>][value]" value="<?=$item['value']?>" style="width:95%;"></td>
                            <td>
                                <?php if (empty($i)): ?>
                                    <a href="javascript:void(0)" onclick="GLOBAL.HelperDialog.addRow()" class="action">添加</a>
                                <?php else: ?>
                                    <a href="javascript:void(0)" onclick="GLOBAL.HelperDialog.delRow(this)" class="action">删除</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
	</table>
</form>
<script type="text/javascript">
GLOBAL.HelperDialog = {
    submit:function(url,$dialog,success){
        var $form = $('#F<?=UNIQID?>');
        if(!$form.form('validate')){
            return false;
        }
        $.messager.progress({text:'处理中，请稍候...'});
        $.post(url, $form.serialize(), function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                    $dialog.dialog('close');
                    if (success) {
                        success();
                    }
                }
            }, 'json'
        );
    },
    addRow:function(){
        var id = QT.util.uuid();
        var row = '<tr>'+
                '<td><input type="text" name="items['+id+'][label]" style="width:95%;"></td>'+
                '<td><input type="text" name="items['+id+'][value]" style="width:95%;"></td>'+
                '<td><a href="javascript:void(0)" onclick="GLOBAL.HelperDialog.delRow(this)" class="action">删除</a></td>'+
                '</tr>';
        $('#T<?=UNIQID?> tbody').append(row);
    },
    delRow: function (clkobj) {
        $(clkobj).parent().parent().remove();
    }
}
</script>