<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-tabs>
<ion-tab>
    <ion-header>
        <ion-toolbar color="bg">
            <ion-title color="action">个人中心</ion-title>
        </ion-toolbar>
    </ion-header>
    <ion-content color="bg">
        <ion-list>
            <ion-item lines="full">
                <ion-avatar>
                    <img alt="<?=$_user['nickname']?>" src="<?=$_user['headimg_url']?>" />
                </ion-avatar>
                <ion-label><?=$_user['nickname']?></ion-label>
                <ion-button id="edit-user-info" color="action">编辑个人资料</ion-button>
            </ion-item>
        </ion-list>
        <ion-item-group>
            <ion-item href="<?=url('mp/Ucenter/tests')?>" lines="full">
                <ion-icon name="star-outline" slot="start" size="small" color="action"></ion-icon>
                <ion-label>我的测评</ion-label>
            </ion-item>
            <ion-item href="<?=url('mp/Ucenter/collections')?>" lines="full">
                <ion-icon name="star-outline" slot="start" size="small" color="action"></ion-icon>
                <ion-label>收藏量表</ion-label>
            </ion-item>
            <ion-item href="<?=url('mp/Ucenter/appoints')?>" lines="full">
                <ion-icon name="star-outline" slot="start" size="small" color="action"></ion-icon>
                <ion-label>我的预约</ion-label>
            </ion-item>
            <ion-item href="<?=url('mp/Ucenter/survey')?>" lines="full">
                <ion-icon name="star-outline" slot="start" size="small" color="action"></ion-icon>
                <ion-label>普查测评</ion-label>
            </ion-item>
            <ion-item href="<?=url('mp/Ucenter/combination')?>" lines="full">
                <ion-icon name="star-outline" slot="start" size="small" color="action"></ion-icon>
                <ion-label>组合测评</ion-label>
            </ion-item>
            <ion-item href="<?=url('mp/Ucenter/aboutus')?>" lines="full">
                <ion-icon name="star-outline" slot="start" size="small" color="action"></ion-icon>
                <ion-label>关于我们</ion-label>
            </ion-item>
        </ion-item-group>
    </ion-content>
</ion-tab>
    <?php
    include APP_PATH . "mp/view/common/tabbar.php";
    ?>
</ion-tabs>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<?php
include APP_PATH . "mp/view/common/useredit.php";
?>
<script>
$(function() {
  $(document).on('click', '#edit-user-info', function(e) {
      popupUserInfo();
      return false;
  });
})
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>