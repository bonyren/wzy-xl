<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-title color="action"><?php echo \think\Lang::get('System Error'); ?></ion-title>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="ion-padding">
<ion-card>
  <ion-img src="/static/mp.ionic/img/error.png" style="height:80px;"></ion-img>
  <ion-card-content>
    <h2 class="ion-text-center"><?php echo $echo;?></h2>
    <h3 class="ion-text-center"><?php echo htmlentities($message); ?></h3>
  </ion-card-content>
</ion-card>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>