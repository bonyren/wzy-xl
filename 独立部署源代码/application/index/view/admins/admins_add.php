<?php
use app\index\model\Admins as AdminsModel;
?>
<form>
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 30%;">登录名</td>
            <td class="field-input"><input class="easyui-textbox" name="infos[login_name]" data-options="required:true,width:'100%',validType:['length[1,20]']" /></td>
        </tr>
        <tr>
            <td class="field-label">登录密码</td>
            <td class="field-input"><input class="easyui-passwordbox" name="infos[login_password]" data-options="required:true,width:'100%',validType:{length:[6,20]}" /></td>
        </tr>
        <tr>
            <td class="field-label">姓名</td>
            <td class="field-input"><input class="easyui-textbox" name="infos[realname]" data-options="required:true,width:'100%',validType:['length[1,20]']" /></td>
        </tr>
        <!--
        <tr>
            <td class="field-label">Email</td>
            <td class="field-input"><input class="easyui-textbox" name="infos[email]" data-options="required:false,width:'100%',validType:['length[1,60]', 'email', 'remote[\'<?=$urlHrefs['checkAdminEmail']?>\', \'email\']']" /></td>
        </tr>
        -->
        <tr>
            <td class="field-label">超级管理员</td>
            <td class="field-input">
                <input class="easyui-checkbox" name="infos[super_user]" value="<?=AdminsModel::eAdminSuperRole?>"
                       data-options="onChange:adminAddModule.onSuperUserChange"/>
            </td>
        </tr>
        <tr id="adminRoleRow">
            <td class="field-label">角色</td>
            <td class="field-input">
                <select id="adminRoleCombobox" class="easyui-combobox" name="infos[role_id]" data-options="required:true,editable:true,limitToList:true,panelHeight:'auto',width:200,value:''">
                    <?php foreach($bindValues['adminRolePairs'] as $key=>$value){ ?>
                        <option value="<?=$key?>"><?=$value?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    var adminAddModule = {
        onSuperUserChange:function(checked){
            if(checked){
                $('#adminRoleRow').hide();
                $("#adminRoleCombobox").combobox('disableValidation');
            }else{
                $('#adminRoleRow').show();
                $("#adminRoleCombobox").combobox('enableValidation');
            }
        }
    };
</script>