<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">测评量表介绍</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <ion-card mode='md'>
        <img alt="<?=$subject['name']?>" src="<?=generateThumbnailUrl($subject['image_url'], 500, '/static/mp.ionic/img/banner-default.jpg')?>" 
            style="width: 100%;"></img>
        <ion-card-header>
            <ion-card-title>
                <h5><?php echo $subject['name']; ?><?php if(systemSetting('subject_show_price') == 'yes'){ echo '<ion-text color="success">￥' . $subject['current_price'] . '</ion-text>'; } ?></h5>
            </ion-card-title>
            <ion-card-subtitle>
                <!--
                <ion-chip>
                    <ion-icon name="alarm-outline"></ion-icon>
                    <ion-text color="primary"><?=$subject['expect_finish_time']?></ion-text>分钟
                </ion-chip>
                -->
                <ion-chip style="font-size: 0.6rem;">
                    <ion-icon name="list-circle-outline"></ion-icon>
                    <ion-text color="success"><?=$subject['items']?></ion-text>道题目
                </ion-chip>
                <ion-chip style="font-size: 0.6rem;">
                    <ion-icon name="people-outline"></ion-icon>
                    <ion-text color="secondary"><?=formatTimes($subject['participants_show'])?></ion-text>人已测</span>
                </ion-chip>
            </ion-card-subtitle>
        </ion-card-header>
        <ion-card-content class="subject-detail-content">
            <?=htmlspecialchars_decode($subject['subject_desc'])?>
        </ion-card-content>
    </ion-card>
</ion-content>
<ion-footer>
    <ion-toolbar>
        <ion-buttons slot="start">
        <?php 
            if($subject['is_collected']){ 
                $buttonText = '已收藏';
                $color = 'action';
            }else{
                $buttonText = '收藏';
                $color = 'light';
            }
            ?>
            <ion-button id="collectBtn" color="<?=$color?>" fill="solid">
                <ion-icon slot="start" name="star"></ion-icon><?=$buttonText?>
            </ion-button>
        </ion-buttons>
        <ion-buttons slot="end">
            <ion-button id="testBtn" color="action" fill="solid">
                <ion-icon name="caret-forward-outline" slot="start"></ion-icon><span id="testBtnText">开始测评</span>
            </ion-button>
            <ion-button id="buyBtn" style="display: none;" color="action" fill="solid">
                <ion-icon name="logo-yen" slot="start"></ion-icon><i><?=$subject['current_price']?></i> 购买并测评
            </ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
<?php
if (\think\Env::get('production')) {
?>
    wx.ready(function(){
        //分享好友
        wx.updateAppMessageShareData({ 
            title: "<?=sanitizeStringForJsVariable($subject['name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($subject['subject_desc']??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($subject['image_url'], 300)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateAppMessageShareData success');
            }
        });
        //分享朋友圈
        wx.updateTimelineShareData({ 
            title: "<?=sanitizeStringForJsVariable($subject['name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($subject['subject_desc']??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl($subject['image_url'], 300)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateTimelineShareData success');
            }
        });
    });
<?php } ?>
    /*********************************************************************/
    var subjectId = <?=$subject['id']?>;
    var subjectTip = "<?=sanitizeStringForJsVariable($subject['subject_tip'])?>";
    var lastOrderNo = '<?=empty($unfinished) ? '' : $unfinished['order_no']?>';
    var test_url = '<?=url('mp/Subject/test')?>';
    
    if(lastOrderNo) {
        $('#testBtnText').text("继续测试");
        $('#testBtn').show();
    }else{
        <?php if(floatval($subject['current_price'])){ ?>
            //先购买再测评
            $('#testBtn').hide();
            $('#buyBtn').show();
        <?php } ?>
    }
    //收藏
    $('#collectBtn').click(function(evt){
        var that = this;
        $.post('<?=url('mp/Subject/collect', ['id'=>$subject['id'], 'type'=>$subject['is_collected']?0:1])?>',function(res){
            if (res.code) {
                window.location.reload();
            } else {
                TOAST.error(res.msg);
            }
        },'json');
        return false;
    });
    //开始测试
    $('#testBtn').click(function(evt) {
        if(lastOrderNo) {
            //继续上次的测试
            var href = GLOBAL.func.addUrlParam(test_url, 'order_no', lastOrderNo);
            window.location.assign(href);
        }else{
                LOADING.show('处理中').then(()=>{
                    $.post('<?=url('mp/Subject/genOrder')?>',{subject_id:'<?=$subject['id']?>', cb_order_id:'<?=$cb_order_id?>', survey_order_id:'<?=$survey_order_id?>'}, function(res){
                    LOADING.hide();
                    if (!res.code) {
                        TOAST.error(res.msg);
                    } else {
                        var href = GLOBAL.func.addUrlParam(test_url, 'order_no', res.data.order_no);
                        if(subjectTip){
                            ALERT.tip(subjectTip, function(){
                                window.location.assign(href);
                            }, '我明白了，开始答题！');
                        }else{
                            window.location.assign(href);
                        }
                    }
                },'json');
            });
        }
        return false;
    });
    function checkPayStatus(orderNo) {
        LOADING.show('处理中').then(()=>{
            var itv = setInterval(function(){
                $.post('<?=url('mp/Subject/checkOrderPaid')?>',{order_no:orderNo},function(res){
                    if (!res.code) {
                        clearInterval(itv);
                        LOADING.hide();
                        TOAST.error(res.msg);
                    } else if (res.data) {
                        clearInterval(itv);
                        LOADING.hide();
                        var href = GLOBAL.func.addUrlParam(test_url, 'order_no', orderNo);
                        if(subjectTip){
                            ALERT.tip(subjectTip, function(){
                                window.location.assign(href);
                            }, '我明白了，开始答题！');
                        }else{
                            window.location.href = href;
                        }
                    }else{
                        //未支付完成
                    }
                }, 'json');
            }, 1000);
        });
    }
    $('#buyBtn').click(function(evt){
        LOADING.show('处理中').then(()=>{
            $.post('<?=url('mp/Subject/genOrder')?>',{subject_id:'<?=$subject['id']?>', cb_order_id:'<?=$cb_order_id?>', survey_order_id:'<?=$survey_order_id?>'}, function(res){
                if (!res.code) {
                    LOADING.hide();
                    TOAST.error(res.msg);
                    return;
                }
                var orderNo = res.data.order_no;
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
            },'json');
        });
        return false;
    });
    
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>