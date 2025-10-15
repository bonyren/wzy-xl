<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">支付查看报告</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content class="ion-padding" color="bg">
  <ion-card>
    <img src="<?=generateThumbnailUrl($subject['image_url'], 280, '/static/mp.ionic/img/empty-default.jpg')?>" 
                style="width: 100%;height:180px;"></img>
    <ion-card-header>
      <ion-card-title><?=$subject['name']?></ion-card-title>
      <ion-card-subtitle></ion-card-subtitle>
    </ion-card-header>
    <ion-card-content>
        <p class="text-center">
            <ion-label>金额</ion-label><ion-text>&nbsp;￥<i><?=$order['order_amount']?></i></ion-text>
        </p>
        <p class="text-center">
            <ion-button id="buyBtn" color="primary" fill="solid" strong="true">支付查看报告</ion-button>
        </p>
    </ion-card-content>
  </ion-card>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
    var orderNo = '<?=$order['order_no']?>';
    var report_url = '<?=url('mp/Subject/report')?>';
    function checkPayStatus(orderNo) {
        LOADING.show('支付确认中').then(()=>{
            var itv = setInterval(function(){
                $.post('<?=url('mp/Subject/checkOrderPaid')?>',{order_no:orderNo},function(res){
                    if (!res.code) {
                        clearInterval(itv);
                        LOADING.hide();
                        TOAST.error(res.msg);
                    } else if (res.data) {
                        clearInterval(itv);
                        LOADING.hide();
                        //不保存到window.history
                        window.location.replace(GLOBAL.func.addUrlParam(report_url, 'order_no', orderNo));
                    }else{
                        //未支付完成
                    }
                }, 'json');
            }, 1000);
        });
    }
    function buy() {
        LOADING.show('处理中').then(()=>{
            $.post('<?=url('mp/Subject/buy')?>',{order_no:orderNo},function(res){
                LOADING.hide();
                if (!res.code) {
                    TOAST.error(res.msg);
                } else {
                    WeixinJSBridge.invoke('getBrandWCPayRequest', res.data.config, function(data){
                        if(data.err_msg == "get_brand_wcpay_request:ok" ){
                            checkPayStatus(orderNo);
                        } else {
                            TOAST.error('支付失败');
                        }
                    });
                }
            },'json');
        });
    }
    $('#buyBtn').click(function(e){
        buy();
        return false;
    });
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>