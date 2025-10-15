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
        <ion-segment id="tab-switch-segment" value="appointed" disabled="false" class="center-block">
            <ion-segment-button value="appointed">
                <ion-label>已预约</ion-label>
            </ion-segment-button>
            <ion-segment-button value="done">
                <ion-label>已完成</ion-label>
            </ion-segment-button>
            <ion-segment-button value="pending">
                <ion-label>未确认</ion-label>
            </ion-segment-button>
        </ion-segment>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
<!-- 已预约订单 -->
<div id="tab-appointed">
    <ul class="wzy-expert-list">
    <?php foreach($appointedOrders as $appointedOrder){ ?>
        <ion-card class="wzy-expert-item" onclick="showOrder('<?=urlencode(json_encode($appointedOrder))?>')">
            <div class="wzy-expert-brief">
                <div class="wzy-expert-left">
                    <img class="wzy-expert-img img-thumbnail" src="<?=$appointedOrder['workimg_url']?>">
                </div>
                <div class="wzy-expert-right">
                    <div class="wzy-expert-name"><?=$appointedOrder['expert_name']?></div>
                    <div class="wzy-expert-appoint">
                        <p>预约时间：<strong><?=$appointedOrder['appointTimeFull']?></strong></p>
                        <p>下单时间：<span><?=$appointedOrder['order_time']?></span></p>
                        <!--
                        <p>预约方式：<strong><?=\app\index\logic\Defs::$appointModeDefs[$appointedOrder['appoint_mode']]?></strong></p>
                        -->
                    </div>
                </div>
            </div>
            <div class="wzy-expert-opt">
                <ion-button href="javascript:;" fill="outline" size="small" color="action">
                    查看详情<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>
                </ion-button>
            </div>
        </ion-card>    
    <?php } ?>
    </ul>
</div>
<!-- 已完成订单 -->
<div id="tab-done" style="display: none;">
    <ul class="wzy-expert-list">
        <?php foreach($finishedOrders as $finishedOrder){ ?>
        <ion-card class="wzy-expert-item" onclick="showOrder('<?=urlencode(json_encode($finishedOrder))?>')">
            <div class="wzy-expert-brief">
                <div class="wzy-expert-left">
                    <img class="wzy-expert-img img-thumbnail" src="<?=$finishedOrder['workimg_url']?>">
                </div>
                <div class="wzy-expert-right">
                    <div class="wzy-expert-name"><?=$finishedOrder['expert_name']?></div>
                    <div class="wzy-expert-appoint">
                        <p>预约时间：<strong><?=$finishedOrder['appointTimeFull']?></strong></p>
                        <p>下单时间：<span><?=$finishedOrder['order_time']?></span></p>
                        <!--
                        <p>预约方式：<span><?=\app\index\logic\Defs::$appointModeDefs[$finishedOrder['appoint_mode']]?></span></p>
                        -->
                    </div>
                </div>
            </div>
            <div class="wzy-expert-opt">
                <ion-button href="javascript:;" fill="outline" size="small" color="action">
                    查看详情<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>
                </ion-button>
            </div>
        </ion-card>  
        <?php } ?>
    </ul>
</div>
<!-- 未支付订单 -->
<div id="tab-pending" style="display: none;">
    <ul class="wzy-expert-list">
        <?php foreach($pendingOrders as $pendingOrder){ ?>
        <ion-card class="wzy-expert-item" onclick="showOrder('<?=urlencode(json_encode($pendingOrder))?>')">
            <div class="wzy-expert-brief">
                <div class="wzy-expert-left">
                    <img class="wzy-expert-img img-thumbnail" src="<?=$pendingOrder['workimg_url']?>">
                </div>
                <div class="wzy-expert-right">
                    <div class="wzy-expert-name"><?=$pendingOrder['expert_name']?></div>
                    <div class="wzy-expert-appoint">
                        <p>预约时间：<strong><?=$pendingOrder['appointTimeFull']?></strong></p>
                        <p>下单时间：<span><?=$pendingOrder['order_time']?></span></p>
                        <!--
                        <p>预约方式：<span><?=\app\index\logic\Defs::$appointModeDefs[$pendingOrder['appoint_mode']]?></span></p>
                        -->
                    </div>
                </div>
            </div>
            <div class="wzy-expert-opt">
                <ion-button href="javascript:;" fill="outline" size="small" color="action">
                    查看和确认<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>
                </ion-button>
            </div>
        </ion-card>
        <?php } ?>
    </ul>
</div>
</ion-content>

<ion-modal id="appoint-order-tip" is-open="false">
    <ion-header>
      <ion-toolbar color="bg">
        <ion-title color="action">查看预约订单</ion-title>
        <ion-buttons slot="end">
          <ion-button size="small" onclick="document.getElementById('appoint-order-tip').dismiss()" strong="true" fill="solid" color="medium">关闭</ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <ion-content id="appoint-order-tip-content">
    </ion-content>
</ion-modal>
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
    // 展示弹窗
    function showOrder(res){
        res = eval('(' + decodeURIComponent(res) + ')');
        var content = `
            <ion-list inset="true" mode="md" lines="full">
                <ion-item-divider color="section" mode="md">
                    <ion-label>订单</ion-label>
                </ion-item-divider>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">编号</ion-note>
                    <ion-label>${res.order_no}</ion-label>
                </ion-item>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">咨询专家</ion-note>
                    <ion-label>${res.expert_name}</ion-label>
                    <ion-button href="tel:${res.expert_cellphone}" slot="end" fill="outline" color="secondary"><ion-icon name="call-outline"></ion-icon>${res.expert_cellphone}</ion-button>
                </ion-item>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">预约时间</ion-note>
                    <ion-label class="ion-text-wrap">
                        <h2><ion-text color="medium">${res.appoint_date} ${res.appoint_time}</ion-text></h2>
                        <p><ion-badge color="action">${res.appoint_duration}分钟</ion-badge></p>
                    </ion-label>
                </ion-item>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">咨询地点</ion-note>
                    <ion-label>${res.appointAddress}</ion-label>
                </ion-item>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">备注</ion-note>
                    <ion-label>${res.remark}</ion-label>
                </ion-item>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">状态</ion-note>
                    <ion-label>${<?=json_encode(\app\index\logic\Defs::$orderStatusMpHtmlDefs)?>[res.status]}</ion-label>
                </ion-item>
                <ion-item-divider color="section" mode="md">
                    <ion-label>预约人</ion-label>
                </ion-item-divider>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">姓名</ion-note>
                    <ion-label>${res.linkman || ''}</ion-label>
                </ion-item>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">电话</ion-note>
                    <ion-label>${res.cellphone || ''}</ion-label>
                </ion-item>
                <ion-item>
                    <ion-note slot="start" style="width: 18%">下单时间</ion-note>
                    <ion-label>${res.order_time}</ion-label>
                </ion-item>
            <ion-item color="section">`;
        if(res.status == <?=\app\index\logic\Defs::ORDER_PENDING_STATUS?> ||
            res.status == <?=\app\index\logic\Defs::ORDER_APPOINTED_STATUS?>){
            content += `<ion-button onclick="cancelOrder(${res.id})" color="warning" slot="start" strong>取消预约</ion-button>`;
        }
        if(res.status == <?=\app\index\logic\Defs::ORDER_PENDING_STATUS?>){
            content += `<ion-button onclick="payOrder(${res.id})" color="action" slot="end" strong>确认预约</ion-button>`;
        }
        content += `</ion-item></ion-list>`;
        
        $('#appoint-order-tip-content').html(content);
        var modal = document.getElementById('appoint-order-tip');
        modal.present();
    }
    
    function cancelOrder(id){
        ALERT.confirm("确定取消本次预约吗？", function(result){
            if(result != 'confirm'){
                return;
            }
            LOADING.show('数据处理中').then(()=>{
                $.ajax({
                    type: 'POST',
                    url: '<?=url('mp/Expert/appointCancel')?>',
                    data: {id:id},
                    dataType: 'json',
                    success: function (res) {
                        LOADING.hide();
                        if (res.code == 1) {
                            TOAST.success('取消预约成功');
                            //刷新当前页面
                            window.location.reload();
                        } else {
                            TOAST.error(res.msg);
                        }
                    },
                    error: function (error) {
                        LOADING.hide();
                        TOAST.error('当前网络不可用，请检查网络！');
                    }
                });
            });
        });
    }
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
                        window.location.href = redirectUrl;
                    }else{
                        //未支付完成
                    }
                }, 'json');
            }, 1000);
        });
    }
    function payOrder(id){
        ALERT.confirm("确定确认本次预约吗？", function(result){
            if(result != 'confirm'){
                return;
            }
            LOADING.show('数据处理中').then(()=>{
                $.post('<?=url('mp/Expert/uCenterPay')?>', {id:id}, function(res) {
                    LOADING.hide();
                    if (!res.code) {
                        TOAST.error(res.msg);
                    } else {
                        if (res.data.need_pay) {
                            WeixinJSBridge.invoke('getBrandWCPayRequest', res.data.config, function(data) {
                                if (data.err_msg == "get_brand_wcpay_request:ok") {
                                    //orderNo可能被更新
                                    checkPayStatus(res.data.orderNo, res.data.url);
                                } else {
                                    TOAST.error('支付失败');
                                }
                            });
                        } else {
                            window.location.href = res.data.url;
                        }
                    }
                }, 'json');
            });
        });
    }
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>