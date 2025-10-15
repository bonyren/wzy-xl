<form>
    <table class="table-form" cellpadding="5">
        <tr>
            <td width="120" class="field-label">用户</td>
            <td><?=$info['username']?></td>
        </tr>
        <tr>
            <td class="field-label">旧登录密码</td>
            <td><input class="easyui-passwordbox" name="old_password" data-options="required:true,
                validType:['length[6,20]'],
                width:'100%'" />
            </td>
        </tr>
        <tr>
            <td class="field-label">新登录密码</td>
            <td><input id="admin_public_editpwd_form_password" class="easyui-passwordbox" name="new_password" data-options="required:true,
                validType:{length:[6,20]},
                width:'100%'" />
            </td>
        </tr>
        <tr>
            <td class="field-label">重复登录密码</td>
            <td><input class="easyui-passwordbox" data-options="required:true,
                validType:'equals[\'#admin_public_editpwd_form_password\']',
                width:'100%'" />
            </td>
        </tr>
    </table>
</form>