<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">选择预约时间</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
    <ion-toolbar color="bg" class="book-steps">
        <ion-backdrop visible="true"></ion-backdrop>
        <ion-segment value="info" disabled="false" class="center-block">
            <ion-segment-button value="time">
                <ion-label>选择时间</ion-label>
            </ion-segment-button>
            <ion-segment-button value="info">
                <ion-label>填写资料</ion-label>
            </ion-segment-button>
            <ion-segment-button value="pay">
                <ion-label>预约确认</ion-label>
            </ion-segment-button>
            <ion-segment-button value="success">
                <ion-label>预约成功</ion-label>
            </ion-segment-button>
        </ion-segment>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <form id="bookForm" method="POST">
        <input type="hidden" name="expertId" id="expertId" value="<?=$infos['expertId']?>"/>
        <input type="hidden" name="appointDate" id="appointDate" value="<?=$infos['appointDate']?>"/>
        <input type="hidden" name="appointDuration" id="appointDuration" value="<?=$infos['appointDuration']?>"/>
        <input type="hidden" name="appointTime" id="appointTime" value="<?=$infos['appointTime']?>"/>
        <input type="hidden" name="appointMode" id="appointMode" value="<?=$infos['appointMode']?>"/>
        <ion-list>
            <ion-item color="section" lines="none">
                <ion-title>请填写预约信息</ion-title>
                <ion-button id="bookTips" slot="end" color="light" fill="solid" size="small">预约说明</ion-button>
            </ion-item>
            <ion-item>
                <ion-label color="primary"><?=$infos['appointTimeFull']?></ion-label>
            </ion-item>
            <ion-item>
                <ion-input id="name_input" name="realName" label="姓名" label-placement="stacked" type="text" 
                    placeholder="请输入真实姓名" maxlength="16" required value="<?=$infos['realName']?>">
                </ion-input>
            </ion-item>
            <ion-item>
                <ion-input id="cellphone_input" name="cellphone" label="手机" label-placement="stacked" type="text" 
                    placeholder="请输入手机号码" maxlength="11" required value="<?=$infos['cellphone']?>">
                </ion-input>
                <ion-button id="getVerify" color="action" slot="end">发送验证码</ion-button>
            </ion-item>
            <ion-item>
                <ion-input id="sms_input" name="sms" label="验证码" label-placement="stacked" type="text" 
                    placeholder="请输入短信验证码" maxlength="6" required>
                </ion-input>
            </ion-item>
            <ion-item>
                <ion-textarea id="remark_textarea" name="remark" label="备注" label-placement="stacked" placeholder="请输入" 
                    helper-text="请备注一些特别的预约要求。" maxlength="100" counter="true" auto-grow="true"></ion-textarea>
            </ion-item>
        </ion-list>
    </form>
</ion-content>
<ion-footer>
  <ion-toolbar>
    <ion-buttons slot="start">
        <ion-button id="prevBtn" color="medium" fill="solid">上一步</ion-button>
    </ion-buttons>
    <ion-buttons slot="end">
        <ion-button id="submitBtn" color="action" fill="solid">提交预约</ion-button>
    </ion-buttons>
  </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
function sendSMS(cellphone) {
    return new Promise((resolve, reject)=>{
        $.ajax({
            type: 'POST',
            url: '<?=url('mp/Ucenter/captchaSms')?>',
            data: {
                cellphone: cellphone
            },
            dataType: 'json',
            success: function (res) {
                if (res.code == 0) {
                    TOAST.error(res.msg).then(()=>{
                        reject();
                    });
                    return;
                }
                TOAST.success("发送验证码成功").then(()=>{
                    resolve();
                });
            },
            error: function () {
                TOAST.error('当前网络不可用，请检查网络！').then(()=>{
                    reject();
                });
            }
        });
    });
}
function runCaptchaCountdown(that){
    var countDown = 60;//60 seconds
    that.disabled = true;
    $(that).text(String(countDown) + 's');
    var timerId = setInterval(function(){
        countDown--;
        $(that).text(String(countDown) + 's');
        if(countDown == 0){
            clearInterval(timerId);
            that.disabled = false;
            $(that).text('重新获取');
        }
    }, 1000);
}
$(document).on('click', '#getVerify', function () {
    var that = this;
    var cellphone = document.getElementById('cellphone_input').value;
    if (!(/^1[345789]\d{9}$/.test(cellphone))) {
        TOAST.warning('请输入合法手机号码');
        document.getElementById('cellphone_input').setFocus();
        return false;
    }
    LOADING.show('发送请求中').then(()=>{
        sendSMS(cellphone).then(()=>{
            LOADING.hide();
            runCaptchaCountdown(that);
        },()=>{
            LOADING.hide();
        });
    });
    return false;
}).on('click', '#submitBtn', function(){
    let _realname = $('input[name=realName]').val();
    let _cellphone = $('input[name=cellphone]').val();
    let _sms = $('input[name=sms]').val();
    let _remark = document.getElementById('remark_textarea').value;        
    var regtel = /^0{0,1}(13|15|18|14|17|16|19)[0-9]{9}$/, regsms = /^\d{6}$/;
    if (!_realname) {
        TOAST.warning('请输入真实姓名');
        document.getElementById('name_input').setFocus();
        return false;
    } else if (!regtel.test(_cellphone)) {
        TOAST.warning('请输入合法手机号码');
        document.getElementById('cellphone_input').setFocus();
        return false;
    } else if (!regsms.test(_sms)) {
        TOAST.warning('请输入正确短信验证码');
        document.getElementById('sms_input').setFocus();
        return false;
    } else if(_remark.length > 100){
        TOAST.warning('备注长度不能超过100字符');
        document.getElementById('remark_textarea').setFocus();
        return false;
    }
    LOADING.show('数据处理中').then(()=>{
        var url = '<?=url('mp/Expert/appointInfo')?>';
        $.post(url, $('#bookForm').serialize(), function(res){
            LOADING.hide();
            if (!res.code) {
                TOAST.error(res.msg);
            } else {
                //不允许后退
                window.location.replace(res.data.url);
            }
        }, 'json');
    });
    return false;
}).on('click', '#prevBtn', function(){
    window.history.back();
    return false;
}).on('click', '#bookTips', function () {
    document.getElementById('appoint-book-tip').present();
    return false;
});
</script>
<?php
include APP_PATH . "mp/view/common/booktip.php";
include APP_PATH . "mp/view/common/footer.php";
?>