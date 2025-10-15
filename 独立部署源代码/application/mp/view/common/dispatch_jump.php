<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-title color="action">跳转提示</ion-title>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="ion-padding">
    <ion-card>
        <!--
        <ion-card-header>
            <ion-card-title>
                <?php
                /* 
                    switch ($code){
                        case 1:
                            echo '<ion-icon name="happy" size="large" color="success"></ion-icon>';
                            break;
                        case 0:
                            echo '<ion-icon name="alert-circle" size="large" color="danger"></ion-icon>';
                            break;
                        default:
                    }
                */      
                ?><br/>
            </ion-card-title>
        </ion-card-header>
        -->
        <ion-card-content>
            <?php 
                switch ($code){
                    case 1:
                        echo '<ion-img src="/static/mp.ionic/img/success.png" style="height:80px;"></ion-img>';
                        break;
                    case 0:
                        echo '<ion-img src="/static/mp.ionic/img/warning.png" style="height:80px;"></ion-img>';
                        break;
                    default:
                }      
            ?>
            <h2 class="text-center"><?php echo(strip_tags($msg));?></h2>
        </ion-card-content>
        <ion-card-content id="dispatch-content">
            <p class="text-center">
            页面自动跳转，等待时间： <b id="wait"><?php echo($wait);?></b>
            </p>
            <p class="text-center ion-padding">
                <ion-button id="href" href="<?php echo($url);?>" color="action" fill="solid" size="small">直接跳转</ion-button>
            </p>
        </ion-card-content>
        
    </ion-card>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait');
        var href = '<?php echo($url);?>';
        if(href == 'javascript:history.back(-1);' && window.history.length == 1){
            $('#dispatch-content').hide();
            return;
        }
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                window.location.href = href;
                clearInterval(interval);
            }
        }, 1000);
    })();
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>