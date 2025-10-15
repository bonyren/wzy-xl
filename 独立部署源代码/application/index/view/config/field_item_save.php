<form method="post">
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 100px;">名称:</td>
            <td class="field-input">
                <input class="easyui-textbox" name="infos[field_item]"
                       data-options="required:true,width:'100%',validType:['length[1,128]']"
                       value="<?=$infos['field_item']?>"/>
            </td>
        </tr>
    </table>
</form>