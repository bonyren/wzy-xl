<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">组合测评介绍</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <ion-card>
        <ion-img alt="<?=$name?>" src="<?=generateThumbnailUrl($banner, 500, '/static/mp.ionic/img/empty-default.jpg')?>" 
            style="width: 100%;"></ion-img>
        <ion-card-header>
            <ion-card-title>
                <?=$name?>
            </ion-card-title>
            <ion-card-subtitle>
                <ion-chip>
                    <ion-icon name="alarm-outline"></ion-icon>
                    <ion-label><?=$costTime?>分钟</ion-label>
                </ion-chip>
                <ion-chip>
                    <ion-icon name="list-outline"></ion-icon>
                    <ion-label><?=$subjectCount?>个量表</ion-label>
                </ion-chip>
            </ion-card-subtitle>
        </ion-card-header>
        <ion-card-content class="subject-detail-content">
            <?=nl2br($description)?>
        </ion-card-content>
    </ion-card>
</ion-content>
<ion-footer>
  <ion-toolbar>
    <ion-buttons slot="end">
        <ion-button href="<?=$beginTestUrl?>" color="primary" fill="solid" size="large" strong="true">开始组合测评</ion-button>
    </ion-buttons>
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
            title: "<?=sanitizeStringForJsVariable($name??'')?>", // 分享标题
            desc: "<?=generateShareDesc($description??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($banner, 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateAppMessageShareData success');
            }
        });
        //分享朋友圈
        wx.updateTimelineShareData({ 
            title: "<?=sanitizeStringForJsVariable($name??'')?>", // 分享标题
            desc: "<?=generateShareDesc($description??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($banner, 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateTimelineShareData success');
            }
        });
    });
<?php } ?>
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>