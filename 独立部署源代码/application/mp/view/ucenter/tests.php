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
    <ion-toolbar color="bg">
        <ion-segment id="tab-switch-segment" value="pending" disabled="false" class="center-block">
            <ion-segment-button value="pending">
                <ion-label>未完成</ion-label>
            </ion-segment-button>
            <ion-segment-button value="done">
                <ion-label>已完成</ion-label>
            </ion-segment-button>
        </ion-segment>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
<div>
    <div id="tab-pending">
        <ul class="wzy-subject-list">
            <?php foreach ($rows as $row): ?>
            <ion-card class="wzy-subject-item ion-margin">
                <div class="wzy-subject-brief">
                    <div class="wzy-subject-left">
                        <img class="wzy-subject-img img-thumbnail" src="<?=$row['image_url']?>">
                    </div>
                    <div class="wzy-subject-right">
                        <div class="wzy-subject-name"><?=$row['name']?></div>
                        <div class="wzy-subject-text">
                            <p>
                            <?php if(systemSetting('subject_show_price') == 'yes'){ ?>
                                <span class="wzy-subject-price">￥<?=$row['order_amount']?></span> | 
                            <?php } ?>
                                <ion-text color="success"><?=$row['items']?></ion-text>道题目
                            </p>
                        </div>
                        <div class="wzy-subject-text">
                            <p><?=date('Y-m-d H:i', strtotime($row['order_time']))?>创建</p>
                        </div>
                    </div>
                </div>
                <div class="wzy-subject-opt">
                    <?php if($row['pay_status'] == \app\Defs::PAY_SUCCESS){ ?>
                        <ion-button href="<?=url('mp/Subject/test',['order_no'=>$row['order_no']])?>" fill="outline" size="small" color="action">
                            继续测评<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>
                        </ion-button>
                    <?php }else if($row['pay_status'] == \app\Defs::PAY_WAIT){ ?>
                        <ion-button onclick="payOrder('<?=$row['id']?>')" fill="outline" size="small" color="action">
                            <ion-icon name="logo-yen" slot="start"></ion-icon>购买并测评
                        </ion-button>
                    <?php } ?>
                </div>
            </ion-card>
            <?php endforeach; ?>
        </ul>
    </div>
    <div id="tab-done" style="display: none;">
        <ul class="wzy-subject-list">
            <?php foreach ($rowsCompleted as $rowCompleted): ?>
            <ion-card class="wzy-subject-item ion-margin">
                <div class="wzy-subject-brief">
                    <div class="wzy-subject-left">
                        <img class="wzy-subject-img img-thumbnail" src="<?=$rowCompleted['image_url']?>">
                    </div>
                    <div class="wzy-subject-right">
                        <div class="wzy-subject-name"><?=$rowCompleted['name']?></div>
                        <div class="wzy-subject-text">
                        <p>
                            <?php if(systemSetting('subject_show_price') == 'yes'){ ?>
                                <span class="wzy-subject-price">￥<?=$rowCompleted['order_amount']?></span> | 
                            <?php } ?>
                                <ion-text color="success"><?=$rowCompleted['items']?></ion-text>道题目
                            </p>
                        </div>
                        <div class="wzy-subject-text">
                            <p><?=date('Y-m-d H:i', strtotime($rowCompleted['order_time']))?>开始</p>
                        </div>
                        <div class="wzy-subject-text">
                            <p><?=date('Y-m-d H:i', strtotime($rowCompleted['finish_time']))?>完成</p>
                        </div>
                    </div>
                </div>
                <?php if($rowCompleted['test_allow_view_report']){ ?>
                    <div class="wzy-subject-opt">
                        <ion-button href="<?=url('mp/Subject/report',['order_no'=>$rowCompleted['order_no']])?>" fill="outline" size="small" color="action">
                            查看报告<ion-icon name="reader-outline" slot="end"></ion-icon>
                        </ion-button>
                    </div>
                <?php } ?>
            </ion-card>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
$(function(){
    $('#tab-switch-segment').on('ionChange', function(evt){
        var value = evt.target.value;
        $('#tab-' + value).show();
        $('#tab-' + value).siblings().hide();
        return false;
    });
});
function checkPayStatus(order_no, redirectUrl) {
    LOADING.show('支付确认中').then(()=>{
        var itv = setInterval(function(){
            $.post('<?=url('mp/Subject/checkOrderPaid')?>',{order_no:order_no},function(res){
                if (!res.code) {
                    clearInterval(itv);
                    LOADING.hide();
                    TOAST.error(res.msg);
                } else if (res.data) {
                    clearInterval(itv);
                    LOADING.hide();
                    window.location.href = redirectUrl;
                }else{
                    //未支付完成
                }
            }, 'json');
        }, 1000);
    });
}
function payOrder(id){
    var test_url = '<?=url('mp/Subject/test')?>';
    LOADING.show('数据处理中').then(()=>{
        $.post('<?=url('mp/Subject/uCenterBuy')?>', {id:id}, function(res) {
            LOADING.hide();
            if (!res.code) {
                TOAST.error(res.msg);
            } else {
                if (res.data.need_pay) {
                    WeixinJSBridge.invoke('getBrandWCPayRequest', res.data.config, function(data) {
                        if (data.err_msg == "get_brand_wcpay_request:ok") {
                            //order_no可能被更新
                            test_url = GLOBAL.func.addUrlParam(test_url, 'order_no', res.data.order_no);
                            checkPayStatus(res.data.order_no, test_url);
                        } else {
                            TOAST.error('支付失败');
                            //订单编号已刷新，需要重置
                            window.location.reload();
                        }
                    });
                } else {
                    test_url = GLOBAL.func.addUrlParam(test_url, 'order_no', order_no);
                    window.location.href = test_url;
                }
            }
        }, 'json');
    });
}
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>