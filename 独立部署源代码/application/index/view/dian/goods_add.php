
<form method="post">
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 10%">量表</td>
            <td class="field-input" style="width: 20%">
                <select class="easyui-combobox" name="subject_id" style="width:100%;" value=""
                    data-options="required:true,limitToList:true,valueField:'id',textField:'name',url:'<?=url('index/Subject/getSubjectComboData')?>'">
                </select>
            </td>
            <td class="field-label" style="width: 10%;">数量</td>
            <td class="field-input" style="width: 10%">
                <input class="easyui-numberbox" name="quantity" value="10"
                    data-options="required:true,
                        min:1,
                        max:1000,
                        width:'100%',
                        disabled:false">
            </td>
            <td>
                <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:goodsAddModule.new">生成商品</a>
            </td>
        </tr>
    </table>
</form>
<br />
<table id="goods-urls-section" class="table-form" cellpadding="5">
    <tr>
        <td class="field-label">
            量表测评商品生成结果，请将下面商品链接配置到您所用电商平台的自动发货系统。
        </td>
    </tr>
    <tr>
        <td>
            <textarea id="goods-urls-textbox" class="easyui-textbox" name="formData[goods_urls]"
                data-options="required:true,
                width:'100%',
                height:300,
                multiline:true,
                disabled:true,
                prompt:'量表测评商品生成结果'"></textarea>
        </td>
    </tr>
</table>

<script>
    var goodsAddModule = {
        new:function(){
            var $form = $(this).closest('form');
            var isValid = $form.form('validate');
            if(!isValid){
                return;
            }
            $.messager.confirm('提示','确定生成此批测评商品链接吗？',function(y){
                if(!y) { return; }
                $.post('<?=url('index/Dian/goodsAdd')?>', $form.serialize(),function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        $('#goods-urls-section').show();
                        $('#goods-urls-textbox').textbox('setValue', res.data);
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        }
    };
</script>