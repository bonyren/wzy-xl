<form method="post">
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 100px;">对象:</td>
            <td class="field-input">
                <input class="easyui-textbox" name="infos[target]"
                       data-options="required:true,width:'100%',validType:['length[1,64]']"
                       value="<?=$infos['target']?>"/>
            </td>
        </tr>
    </table>
</form>