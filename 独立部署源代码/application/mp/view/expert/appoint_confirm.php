<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>

<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">预约确认</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
    <ion-toolbar color="bg" class="book-steps">
        <ion-backdrop visible="true"></ion-backdrop>
        <ion-segment value="pay" disabled="false" class="center-block">
            <ion-segment-button value="time">
                <ion-label>选择时间</ion-label>
            </ion-segment-button>
            <ion-segment-button value="info">
                <ion-label>填写资料</ion-label>
            </ion-segment-button>
            <ion-segment-button value="pay">
                <ion-label>预约确认</ion-label>
            </ion-segment-button>
            <ion-segment-button value="success">
                <ion-label>预约成功</ion-label>
            </ion-segment-button>
        </ion-segment>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <ion-list>
        <ion-item-group>
            <ion-item-divider color="section" mode="md">
                <ion-label>咨询专家</ion-label>
            </ion-item-divider>
            <ion-item>
                <!--
                <ion-avatar slot="start">
                    <img alt="<?=$infos['expert_name']?>" src="<?=generateThumbnailUrl($infos['workimg_url'], 150)?>" />
                </ion-avatar>
                <ion-title><?=$infos['expert_name']?></ion-title>
                -->
                <ion-label><ion-text color="medium"><?=$infos['expert_name']?></ion-text></ion-label>
                <ion-button href="tel:<?=$infos['expert_cellphone']?>" slot="end" fill="clear" color="secondary"><ion-icon name="call-outline"></ion-icon><?=$infos['expert_cellphone']?></ion-button>
            </ion-item>
        </ion-item-group>
        <ion-item-group>
            <ion-item-divider color="section" mode="md">
                <ion-label>咨询地点</ion-label>
            </ion-item-divider>
            <ion-item>
                <ion-label><ion-text color="medium"><?=$infos['appoint_address']?></ion-text></ion-label>
            </ion-item>
        </ion-item-group>
        <ion-item-group>
            <ion-item-divider color="section" mode="md">
                <ion-label>预约时间</ion-label>
            </ion-item-divider>
            <ion-item>
                <ion-label class="ion-text-wrap">
                    <ion-text color="medium"><?=$infos['appoint_date']?> <?=$infos['appoint_time']?></ion-text>
                </ion-label>
                <ion-badge color="action" slot="end"><?=$infos['appoint_duration']?>分钟</ion-badge>
            </ion-item>
        </ion-item-group>
        <ion-item-group>
            <ion-item-divider color="section" mode="md">
                <ion-label>咨询方式</ion-label>
            </ion-item-divider>
            <ion-item>
                <ion-label>
                    <ion-text color="medium"><?=\app\index\logic\Defs::$appointModeDefs[$infos['appoint_mode']]?></ion-text>
                </ion-label>
            </ion-item>
        </ion-item-group>
        <ion-item-group>
            <ion-item-divider color="section" mode="md">
                <ion-label>预约人</ion-label>
            </ion-item-divider>
            <ion-item>
                <ion-label><ion-text color="medium"><?=$infos['linkman']?></ion-text></ion-label>
                <ion-button href="tel:<?=$infos['cellphone']?>" slot="end" fill="clear" color="secondary"><ion-icon name="call-outline"></ion-icon><?=$infos['cellphone']?></ion-button>
            </ion-item>
        </ion-item-group>
        <ion-item-group>
            <ion-item-divider color="section" mode="md">
                <ion-label>备注</ion-label>
            </ion-item-divider>
            <ion-item>
                <ion-label><ion-text color="medium" class="ion-text-wrap"><?=nl2br($infos['remark'])?></ion-text></ion-label>
            </ion-item>
        </ion-item-group>
    </ion-list>
</ion-content>
<ion-footer>
  <ion-toolbar>
    <ion-buttons slot="end">
        <?php if(systemSetting('appoint_order_pay_model') == '在线支付' && floatval($infos['order_amount'])){ ?>
            <ion-button color="action" id="payBtn" fill="solid">￥<i><?=$infos['order_amount']?></i> 立即支付</ion-button>
        <?php }else{ ?>
            <ion-button color="action" id="payBtn" fill="solid">确认并继续</ion-button>
        <?php } ?>
    </ion-buttons>
  </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
    function checkPayStatus(orderNo, redirectUrl) {
        LOADING.show('支付确认中').then(()=>{
            var itv = setInterval(function(){
                $.post('<?=url('mp/Expert/checkOrderPaid')?>',{orderNo:orderNo},function(res){
                    if (!res.code) {
                        clearInterval(itv);
                        LOADING.hide();
                        TOAST.error(res.msg);
                    } else if (res.data) {
                        clearInterval(itv);
                        LOADING.hide();
                        window.location.replace(redirectUrl);
                    }else{
                        //未支付完成
                    }
                }, 'json');
            }, 1000);
        });
    }
    $(document).on('click', '#payBtn', function () {
        LOADING.show('数据处理中').then(()=>{
            $.post('<?=url('mp/Expert/appointPay')?>', {orderNo:'<?=$infos['order_no']?>'}, function(res) {
                LOADING.hide();
                if (!res.code) {
                    TOAST.error(res.msg);
                } else {
                    if (res.data.need_pay) {
                        WeixinJSBridge.invoke('getBrandWCPayRequest', res.data.config, function(data) {
                            if (data.err_msg == "get_brand_wcpay_request:ok") {
                                checkPayStatus('<?=$infos['order_no']?>', res.data.url);
                            } else {
                                TOAST.error('支付失败');
                            }
                        });
                    } else {
                        window.location.replace(res.data.url);
                    }
                }
            }, 'json');
        });

    })
    .on('click', '#bookTips', function () {
        document.getElementById('appoint-book-tip').present();
        return false;
    });
</script>
<?php
include APP_PATH . "mp/view/common/booktip.php";
include APP_PATH . "mp/view/common/footer.php";
?>