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
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <ion-card mode='md'>
        <img alt="<?=$subject['name']?>" src="<?=generateThumbnailUrl($subject['image_url'], 500, '/static/mp.ionic/img/banner-default.jpg')?>" 
            style="width: 100%;"></img>
        <ion-card-header>
            <ion-card-title>
                <h5><?php echo $subject['name']; ?></h5>
            </ion-card-title>
            <ion-card-subtitle>
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
    <ion-toolbar color="bg">
        <ion-buttons slot="end">
            <ion-button id="testBtn" color="action" fill="solid">
                <ion-icon name="caret-forward-outline" slot="start"></ion-icon><span id="testBtnText">开始测评</span>
            </ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script_no_weixin.php";
?>
<script>
    /*********************************************************************/
    var subjectId = <?=$subject['id']?>;
    var subjectTip = "<?=sanitizeStringForJsVariable($subject['subject_tip'])?>";
    var lastOrderNo = '<?=empty($unfinished) ? '' : $unfinished['order_no']?>';
    var test_url = '<?=$test_url?>';
    if(lastOrderNo) {
        $('#testBtnText').text("继续测试");
        $('#testBtn').show();
    }
    //开始测试
    $('#testBtn').click(function(evt) {
        if(lastOrderNo) {
            //继续上次的测试
            var href = GLOBAL.func.addUrlParam(test_url, 'order_no', lastOrderNo);
            window.location.assign(href);
        }else{
                LOADING.show('处理中').then(()=>{
                    $.post('<?=$gen_order_url?>',function(res){
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
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>