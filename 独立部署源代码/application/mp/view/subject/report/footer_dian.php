</ion-content>
<ion-footer>
<ion-toolbar class="text-center">
    <ion-label>©2023 <?=$_studio['store_name']?></ion-label>
</ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script_no_weixin.php";
?>
<!--5.4.3-->
<script defer="defer" src="/static/mp.ionic/js/echarts.min.js?<?=STATIC_VER?>"></script>
<script>
    $(function(){
        LOADING.show('正在生成').then(()=>{
            setTimeout(function(){
                initFuncs.forEach(function(func){
                    func();
                });
                LOADING.hide();
            }, 1000);
        });
    });
</script>
<?php 
include APP_PATH . "mp/view/common/footer.php";
?>
