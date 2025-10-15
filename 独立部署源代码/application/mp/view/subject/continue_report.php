<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">查看报告</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="ion-padding">
  <ion-card>
    <ion-card-header>
      <ion-card-title><?=$msg?></ion-card-title>
      <ion-card-subtitle></ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
      <p class="text-center">
        <ion-button color="success" fill="solid" strong="true" onclick="continueReport.report(); return false;">
          查看报告<ion-icon name="reader-outline" slot="end"></ion-icon>
        </ion-button>
      </p>
    </ion-card-content>
  </ion-card>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
var continueReport = {
    report:function(){
        var url = '<?=$report_url?>';
        window.location.replace(url);
    }
};
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>