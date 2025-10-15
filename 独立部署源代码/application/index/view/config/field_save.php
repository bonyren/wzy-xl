<form method="post" style="width: 100%;height: 100%;">
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 100px;">名称:</td>
            <td class="field-input">
                <input class="easyui-textbox" name="infos[field]"
                       data-options="required:true,width:'100%',validType:['length[1,128]']"
                    value="<?=$infos['field']?>"/>
            </td>
        </tr>
    </table>
</form>