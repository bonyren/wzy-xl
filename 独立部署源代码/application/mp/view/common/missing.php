<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-header>
    <ion-toolbar color="bg">
        <ion-title color="action">系统提示</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="ion-padding">
<ion-card>
  <ion-img src="/static/mp.ionic/img/warning.png" style="height:80px;">
  </ion-img>
  <ion-card-content>
    <h2 class="ion-text-center"><?=isset($msg)?$msg:'无法找到内容'?></h2>
  </ion-card-content>
</ion-card>
</ion-content>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>