<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">发现上次未完成的测评</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="ion-padding">
  <ion-card>
    <ion-card-header>
      <ion-card-title><?=$title?></ion-card-title>
      <ion-card-subtitle>发现上次未完成的</ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
      <p class="text-center">
        您可以选择继续完成上次未完成的测评答题，或者启动全新的测评。
      </p>
      <ion-toolbar>
          <ion-button fill="solid" color="success" onclick="continueLast.last(); return false;" slot="start">继续上次的<?=$title?></ion-button>
          <ion-button fill="solid" color="primary" onclick="continueLast.new(); return false;" slot="end">开启新<?=$title?></ion-button>
      </ion-toolbar>
    </ion-card-content>
  </ion-card>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
<?php
if (\think\Env::get('production')) {
?>
wx.ready(function(){
  wx.hideAllNonBaseMenuItem();
  //wx.hideOptionMenu();
});
<?php } ?>
var continueLast = {
    last:function(){
        var url = '<?=$continue_url?>';
        window.location.assign(url);
    },
    new:function(){
        var url = '<?=$new_url?>';
        window.location.assign(url);
    }
};
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>