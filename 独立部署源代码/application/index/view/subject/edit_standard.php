<form>
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 100px;">维度因子</td>
            <td class="field-input">
                <input class="easyui-textbox" name="formData[latitude]" value="<?=$formData['latitude']?>" data-options="required:true,width:'95%',validType:['length[1,128]']">
            </td>
        </tr>
        <tr>
            <td class="field-label">备注</td>
            <td class="field-input">
                <textarea class="easyui-textbox" name="formData[remark]"
                       data-options="width:'95%',height:100,multiline:true,validType:['length[1,256]']"><?=$formData['remark']?></textarea>
            </td>
        </tr>
    </table>
</form>