<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<script>
    var initFuncs = [];
</script>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">查看测评报告</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content class="ion-padding" color="bg">
<ion-card class="report_wrapper ion-no-margin ion-margin-vertical">
    <div class="user user_fullbox user_color">
        <?php if(!empty($user['headimg_url'])){ ?>
            <img src="<?=$user['headimg_url']?>" class="img-thumbnail">
        <?php } ?>
        <h3><?=$user['nickname']??''?></h3>
        <p>测评时间：<span><?=$orderTime?></span></p>
    </div>
</ion-card>
<?php if(!empty($personalDataItems)){ ?>
    <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
        <ion-card-header color="light">
            <ion-card-subtitle>个人信息</ion-card-subtitle>
        </ion-card-header>
        <ion-card-content class="ion-no-padding">
            <ion-list>
                <?php foreach($personalDataItems as $personalDataItem){ ?>
                <ion-item>
                    <ion-note><?=$personalDataItem['title']?>：<ion-text color="medium"><?=$personalDataItem['value']?></ion-text></ion-note>
                </ion-item>
                <?php } ?>
            </ion-list>
        </ion-card-content>
    </ion-card>
<?php } ?>
<?php
foreach($orderNos as $orderNo){
    echo action('mp/Subject/report', ['order_no'=>$orderNo, 'source'=>'group']);
}
?>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script>
<?php
if (\think\Env::get('production')) {
?>
    wx.ready(function(){
        //分享好友
        wx.updateAppMessageShareData({ 
            title: "测评报告 - <?=sanitizeStringForJsVariable($groupOrder['name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($user['nickname'] . ' ' . $orderTime)?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($groupOrder['banner'], 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateAppMessageShareData success');
            }
        });
        //分享朋友圈
        wx.updateTimelineShareData({ 
            title: "测评报告 - <?=sanitizeStringForJsVariable($groupOrder['name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($user['nickname'] . ' ' . $orderTime)?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($groupOrder['banner'], 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateTimelineShareData success');
            }
        });
    });
<?php } ?>
</script>
<script defer="defer" src="/static/mp.ionic/js/echarts.min.js?<?=STATIC_VER?>"></script>
<script>
    $('.report_wrapper,.user').watermark({
        texts : ["<?=$_studio['store_name']?>"],
        textColor : "#d2d2d2",
        textFont : '12px 微软雅黑',
        width : 100, //水印文字的水平间距
        height : 100,  //水印文字的高度间距（低于文字高度会被替代）
        textRotate : -30 //-90到0， 负数值，不包含-90
    });
    $(function(){
        LOADING.show('正在生成').then(()=>{
            setTimeout(function(){
                initFuncs.forEach(function(func){
                    func();
                });
                LOADING.hide();
            }, 1000);
        });
    });
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>