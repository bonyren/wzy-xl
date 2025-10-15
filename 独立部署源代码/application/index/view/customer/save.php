<form>
<table class="table-form" celllpadding="5">
    <tr>
        <td class="field-label" style="width: 100px;">真实姓名</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[real_name]" value="<?=$formData['real_name']?>"
                           data-options="width:'100%',validType:['length[1,16]']">
        </td>
    </tr>
    <tr>
        <td class="field-label">性别</td>
        <td class="field-input">
            <select class="easyui-combobox" name="formData[sex]" data-options="editable:false,width:'100%',panelHeight:'auto',value:'<?=$formData['sex']?>'">
                <option value="0">未知</option>
                <option value="1">男</option>
                <option value="2">女</option>
            </select>
        </td>
    </tr>
    <tr>
        <td class="field-label">身份证</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[idcard]" value="<?=$formData['idcard']?>"
                           data-options="width:'100%',validType:['length[15,18]', 'idcard']">
        </td>
    </tr>
    <tr>
        <td class="field-label">手机号码</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[cellphone]" value="<?=$formData['cellphone']?>"
                           data-options="width:'100%',validType:['length[11,11]', 'mobile']">
        </td>
    </tr>
    <tr>
        <td class="field-label">住址</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[address]" value="<?=$formData['address']?>"
                           data-options="width:'100%',validType:['length[1,100]']">
        </td>
    </tr>
    <tr>
        <td class="field-label">年龄</td>
        <td class="field-input">
            <input class="easyui-numberbox" name="formData[age]" value="<?=$formData['age']?>"
                           data-options="width:'100%',min:0,precision:0">
        </td>
    </tr>
    <tr>
        <td class="field-label">职业</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[profession]" value="<?=$formData['profession']?>"
                           data-options="width:'100%',validType:['length[1,32]']">
        </td>
    </tr>
    <tr>
        <td class="field-label">所在组织</td>
        <td class="field-input">
            <input class="easyui-combotree" name="formData[organization_id]" value="<?=$formData['organization_id']?>"
                           data-options="width:'100%',editable:false,url:'<?=url('index/Organization/index')?>'">
        </td>
    </tr
    <tr>
        <td class="field-label">工作单位</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[company]" value="<?=$formData['company']?>"
                           data-options="width:'100%',validType:['length[1,32]']">
        </td>
    </tr>
    <tr>
        <td class="field-label">工作岗位</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[job]" value="<?=$formData['job']?>"
                           data-options="width:'100%',validType:['length[1,32]']">
        </td>
    </tr>
    <tr>
        <td class="field-label">工作年限</td>
        <td class="field-input">
            <input class="easyui-numberbox" name="formData[work_age]" value="<?=$formData['work_age']?>"
                           data-options="width:'100%',min:0,precision:0">
        </td>
    </tr>
    <tr>
        <td class="field-label">疾病史</td>
        <td class="field-input">
            <input class="easyui-textbox" name="formData[disease]" value="<?=$formData['disease']?>"
                           data-options="width:'100%',validType:['length[1,100]']">
        </td>
    </tr>
    <tr>
        <td class="field-label">备注</td>
        <td class="field-input">
            <textarea class="easyui-textbox" name="formData[remark]"
                     data-options="width:'100%',height:100,multiline:true,validType:['length[1,256]']"><?=$formData['remark']?></textarea>
        </td>
    </tr>
</table>
</form>