<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">预约成功</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="book_wrapper">
    <div class="book_suc">
        <img src="/static/mp.ionic/img/book_suc.png" alt="" />
        <p class="text-center">预约成功<br />快去查看您的预约吧！</p>
        <p class="text-center">
            <ion-button href="<?=url('mp/Ucenter/appoints')?>" color="action" fill="solid" size="large" strong="true">查看我的预约</ion-button>
        </p>
    </div>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>