<form method="post">
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 20%;">问题</td>
            <td class="field-input">
                <textarea class="easyui-textbox" name="formData[question]"
                    data-options="width:'100%',height:120,validType:['length[2,256]'],multiline:true,"><?=$formData['question']?></textarea>
            </td>
        </tr>
        <tr>
            <td class="field-label">答案(markdown)</td>
            <td class="field-input">
                <textarea class="easyui-textbox" name="formData[answer]"
                    data-options="width:'100%',height:300,validType:['length[2,2048]'],multiline:true,"><?=$formData['answer']?></textarea>
            </td>
        </tr>
        <tr>
            <td class="field-label">排序</td>
            <td class="field-input">
                <input class="easyui-numberbox" name="formData[order]" value="<?=$formData['order']?>" style="width:350px;"
                        data-options="required:true,min:0,max:100000000">
            </td>
        </tr>
    </table>
</form>