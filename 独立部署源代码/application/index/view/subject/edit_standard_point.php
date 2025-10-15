<form>
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="form-caption" colspan="2">标准分区间</td>
        </tr>
        <tr>
            <td class="field-label" style="width: 100px;">最小值</td>
            <td class="field-input">
                <input id="standard_weight_min" class="easyui-numberbox" name="formData[standard_weight_min]" value="<?=$formData['standard_weight_min']?>" data-options="required:true,width:'95%',min:0,
                            precision:2">
            </td>
        </tr>
        <tr>
            <td class="field-label">最大值</td>
            <td class="field-input">
                <input class="easyui-numberbox" name="formData[standard_weight_max]" value="<?=$formData['standard_weight_max']?>" data-options="required:true,width:'95%',min:0,
                            precision:2,validType:{greater:['#standard_weight_min']}">
            </td>
        </tr>
    </table>
</form>