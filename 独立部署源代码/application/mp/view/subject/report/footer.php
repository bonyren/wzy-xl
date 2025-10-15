</ion-content>
<ion-footer>
<ion-toolbar class="text-center">
    <ion-label>©2023 <?=$_studio['store_name']?></ion-label>
</ion-toolbar>
</ion-footer>
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
            title: "测评报告 - <?=sanitizeStringForJsVariable($subject['name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($user['nickname'] . ' ' . $order['finish_time'])?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateAppMessageShareData success');
            }
        });
        //分享朋友圈
        wx.updateTimelineShareData({ 
            title: "测评报告 - <?=sanitizeStringForJsVariable($subject['name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($user['nickname'] . ' ' . $order['finish_time'])?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
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
    /*
    $('.report_wrapper,.user').watermark({
        texts : ["<?=$_studio['store_name']?>"],
        textColor : "#d2d2d2",
        textFont : '12px 微软雅黑',
        width : 100, //水印文字的水平间距
        height : 100,  //水印文字的高度间距（低于文字高度会被替代）
        textRotate : -30 //-90到0， 负数值，不包含-90
    });*/
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
