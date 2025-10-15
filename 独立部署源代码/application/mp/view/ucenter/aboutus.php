<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action"><?=$pageTitle?></ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="ion-padding">
    <ion-card>
        <ion-img alt="<?=$_studio['store_name']?>" src="<?=systemSetting('general_organisation_logo')?>" 
            style="height:120px;"></ion-img>
        <ion-card-header>
            <ion-card-title>
                <?=$_studio['store_name']?>
            </ion-card-title>
            <ion-card-subtitle>
                <?=nl2br(preg_replace('/1[\d]{10}/', '<a href="tel:$0">$0</a>', $_studio['store_contact']))?>
            </ion-card-subtitle>
        </ion-card-header>
        <ion-card-content>
            <?=nl2br($_studio['store_desc'])?>
        </ion-card-content>
    </ion-card>
</ion-content>
<ion-footer>
<ion-toolbar class="text-center">
    <ion-label>©2024 <?=$_studio['store_name']?></ion-label>
</ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>