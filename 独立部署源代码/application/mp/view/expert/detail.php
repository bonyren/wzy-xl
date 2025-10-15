<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">专家介绍</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <ion-card>
        <ion-img alt="<?=$infos['real_name']?>" src="<?=generateThumbnailUrl($infos['workimg_url'], 500)?>" style="height:180px;">
        </ion-img>
        <ion-card-header>
            <ion-card-title>
                <h3><?= $infos['real_name'] ?></h3>
            </ion-card-title>
            <ion-card-subtitle>
                <ion-chip color="action" style="font-size: 0.6rem;">
                    <ion-label>
                    从业：<ion-text color="primary"><?=$infos['job_years']?></ion-text>年
                    </ion-label>
                </ion-chip>
                <ion-chip color="action" style="font-size: 0.6rem;">
                    <ion-label> 
                    经验：<ion-text color="success"><?=$infos['consult_quantity']?></ion-text>+小时
                    </ion-label>
                </ion-chip>
            </ion-card-subtitle>
        </ion-card-header>
        <ion-card-content>
            <ion-list inset="false">
                <ion-item-group>
                    <ion-item-divider line="full" color="section" style="font-size: 0.6rem;">
                        从业资质
                    </ion-item-divider>
                    <ion-item lines="none" class="ion-padding-bottom">
                        <ion-text><?=nl2br($infos['expert_quality'])?></ion-text>
                    </ion-item>
                </ion-item-group>
                <!--------------------------------------------->
                <ion-item-group>
                    <ion-item-divider line="full" color="section" style="font-size: 0.6rem;">
                        个人介绍
                    </ion-item-divider>
                    <ion-item lines="none" class="ion-padding-bottom">
                        <ion-text><?=htmlspecialchars_decode($infos['expert_profile'])?></ion-text>
                    </ion-item>
                </ion-item-group>
                <!--------------------------------------------->
                <ion-item-group>
                    <ion-item-divider line="full" color="section" style="font-size: 0.6rem;">
                        擅长领域
                    </ion-item-divider>
                    <ion-item lines="none" class="ion-padding-bottom">
                        <ion-label>
                            <?php foreach($infos['field_items'] as $area){ ?>
                                <ion-badge color="action"><?=$area?></ion-badge>
                            <?php } ?>
                        </ion-label>
                    </ion-item>
                </ion-item-group>
                <!--------------------------------------------->
                <ion-item-group>
                    <ion-item-divider line="full" color="section" style="font-size: 0.6rem;">
                        服务人群
                    </ion-item-divider>
                    <ion-item lines="none" class="ion-padding-bottom">
                        <ion-label>
                            <?php foreach($infos['targets'] as $object){ ?>
                                <ion-badge color="action"><?=$object?></ion-badge>
                            <?php } ?>
                        </ion-label>
                    </ion-item>
                </ion-item-group>
            </ion-list>
        </ion-card-content>
    </ion-card>
</ion-content>
<ion-footer>
  <ion-toolbar>
    <ion-buttons slot="end">
        <ion-button id="bookBtn" color="action" fill="solid">立即预约</ion-button>
    </ion-buttons>
  </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
<?php
if (\think\Env::get('production')) {
?>
    wx.ready(function(){
        //分享好友
        wx.updateAppMessageShareData({ 
            title: "<?=sanitizeStringForJsVariable($infos['real_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($infos['expert_profile']??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($infos['workimg_url'], 300)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateAppMessageShareData success');
            }
        });
        //分享朋友圈
        wx.updateTimelineShareData({ 
            title: "<?=sanitizeStringForJsVariable($infos['real_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($infos['expert_profile']??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($infos['workimg_url'], 300)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateTimelineShareData success');
            }
        });
    });
<?php } ?>
    $(function () {
        $(document).on('click', '#bookBtn', function () {
            //立即预约
            var href = '<?=url('mp/Expert/appointTime', ['expertId'=>$infos['id']])?>';
            window.location.href = href;
        })
    })
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>