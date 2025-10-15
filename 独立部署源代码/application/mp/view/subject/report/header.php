<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<script>
    var initFuncs = [];
</script>
<?php if(!$internalView){ ?>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">测评报告</ion-title>
        <ion-buttons slot="end">
            <!--
            <ion-button href="<?=$pdfUrl?>"><ion-icon slot="start" name="download-outline"></ion-icon>pdf</ion-button>
            -->
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
    </ion-buttons>
    </ion-toolbar>
</ion-header>
<?php } ?>
<ion-content class="ion-padding" color="<?=$theme?>">
<ion-card class="report_wrapper ion-no-margin ion-margin-vertical">
    <div class="user user_fullbox user_color">
        <?php if(!empty($user['headimg_url'])){ ?>
            <img src="<?=$user['headimg_url']?>" class="img-thumbnail">
        <?php } ?>
        <h3><?=$user['nickname']??''?></h3>
        <p>测评时间：<span><?=$order['finish_time']?></span></p>
    </div>
</ion-card>