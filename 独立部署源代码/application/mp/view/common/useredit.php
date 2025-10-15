<?php
use app\index\logic\Organization as OrganizationLogic;
?>
<ion-modal id="popupCustomerInfo" is-open="false">
    <ion-header>
      <ion-toolbar color="bg">
        <ion-buttons slot="start">
          <ion-button size="small" color="medium" fill="solid" onclick="document.getElementById('popupCustomerInfo').dismiss()" strong="true">取消</ion-button>
        </ion-buttons>
        <ion-title color="action">个人信息</ion-title>
        <ion-buttons slot="end">
          <ion-button size="small" color="primary" fill="solid" onclick="saveUserInfo()" strong="true">保存</ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <ion-content color="bg" class="ion-padding">
        <form id="form-user-popup" method="POST" enctype="application/x-www-form-urlencoded">
            <ion-list>
                <ion-item>
                    <ion-input name="real_name" label="姓名" type="text" placeholder="请输入真实姓名" maxlength="16" required value="<?=$user['real_name']?>"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-input name="age" label="年龄" type="number" placeholder="请输入您的年龄" min="1" max="120" required value="<?=$user['age']?>"></ion-input>
                </ion-item>
                <?php
                if(!OrganizationLogic::I()->isEmpty()){
                ?>
                <ion-item>
                    <ion-select name="organization_id" label="所在组织" placeholder="请选择所在的组织部门" value="<?=$user['organization_id']?>">
                        <?php foreach($organizationDatas as $key=>$text){ ?>
                            <ion-select-option value="<?=$key?>"><?=$text?></ion-select-option>
                        <?php } ?>
                    </ion-select>
                </ion-item>
                <?php
                }
                ?>
                <ion-item>
                    <ion-input name="profession" label="职业" type="text" placeholder="请输入您的职业" maxlength="32" value="<?=$user['profession']?>"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-input name="company" label="工作单位" type="text" placeholder="请输入您的工作单位" maxlength="32" value="<?=$user['company']?>"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-input name="job" label="工作岗位" type="text" placeholder="请输入您的工作岗位" maxlength="32" value="<?=$user['job']?>"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-input name="work_age" label="工作年限" type="number" placeholder="请输入您的工作年限" min="1" max="100" value="<?=$user['work_age']?>"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-input name="disease" label="疾病史" type="text" placeholder="请输入您的疾病史" maxlength="100" value="<?=$user['disease']?>"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-input name="idcard" label="身份证" type="text" placeholder="请输入您的身份证" maxlength="18" value="<?=$user['idcard']?>"></ion-input>
                </ion-item>
                <ion-item>
                    <ion-select name="sex" label="性别" placeholder="请选择" value="<?=$user['sex']?>">
                        <ion-select-option value="1">男</ion-select-option>
                        <ion-select-option value="2">女</ion-select-option>
                    </ion-select>
                </ion-item>
            </ion-list>
        </form>
    </ion-content>
</ion-modal>
<script>
function popupUserInfo() {
    document.getElementById('popupCustomerInfo').present();
}
function closePopupUserInfo() {
    document.getElementById('popupCustomerInfo').dismiss();
}
function saveUserInfo() {
    var fields = $('#form-user-popup').serializeArray();
    var data = {};
    fields.forEach(function(v){
        data[v.name] = $.trim(v.value);
    });
    $.post('<?=url('mp/Ucenter/saveInfo')?>',data,function(res){
        if(!res.code){
            TOAST.error('保存失败 -  ' + res.msg);
        }else{
            TOAST.success('保存成功').then(()=>{
                closePopupUserInfo();
            });
        }
    },'json');
}
$(function() {
    $('#form-user-popup').submit(function(){
        saveUserInfo();
        return false;
    });
});
</script>