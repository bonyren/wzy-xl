<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<script>
    var initFuncs = [];
</script>
<ion-header>
    <ion-toolbar color="bg">
        <?php if(!empty($_home_url)){ ?>
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <?php } ?>
        <ion-title color="action">测评报告</ion-title>
    </ion-toolbar>
</ion-header>
<ion-content class="ion-padding" color="<?=$theme?>">
<ion-card class="report_wrapper ion-no-margin ion-margin-vertical">
    <div class="user_color ion-padding">
        <p>测评时间：<span><?=$order['finish_time']?></span></p>
    </div>
</ion-card>