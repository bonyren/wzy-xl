<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">继续测评</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="ion-padding">
  <ion-card>
    <ion-card-header>
      <ion-card-title><?=$title?></ion-card-title>
      <ion-card-subtitle></ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
      <p class="text-center">
        <?=$msg?>
      </p>
      <p class="text-center">
        <ion-button color="primary" fill="solid" strong="true" onclick="continueTest.next(); return false;">继续下一量表的测评</ion-button>
      </p>
    </ion-card-content>
  </ion-card>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
var continueTest = {
    next:function(){
        var url = '<?=$next_url?>';
        window.location.assign(url);
    }
};
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>