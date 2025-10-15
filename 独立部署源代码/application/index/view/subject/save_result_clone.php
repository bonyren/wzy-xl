<form>
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="form-caption" colspan="2">克隆结果定义</td>
        </tr>
        <tr>
            <td class="field-label" style="width: 100px;">来源维度因子</td>
            <td class="field-input">
                <select class="easyui-combobox" name="standard_from" prompt="" data-options="required:true,editable:false,panelHeight:'auto',value:''" style="width:200px;">
                    <?php foreach($standards as $key=>$text){ ?>
                    <option value="<?=$key?>"><?=$text?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table>
</form>