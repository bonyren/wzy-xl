<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-tabs>
<ion-tab>
    <ion-header>
        <ion-toolbar color="bg">
            <ion-title color="action">个人中心</ion-title>
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
                                    <ion-text color="success"><?=$row['items']?></ion-text>道题目
                                </p>
                            </div>
                            <div class="wzy-subject-text">
                                <p><?=date('Y-m-d H:i', strtotime($row['order_time']))?>创建</p>
                            </div>
                        </div>
                    </div>
                    <div class="wzy-subject-opt">
                        <ion-button href="<?=url('mp/Aio/test',['order_no'=>$row['order_no']])?>" fill="outline" size="small" color="action">
                            继续测评<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>
                        </ion-button>
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
                    <div class="wzy-subject-opt">
                        <ion-button href="<?=url('mp/Aio/report',['order_no'=>$rowCompleted['order_no']])?>" fill="outline" size="small" color="action">
                            查看报告<ion-icon name="reader-outline" slot="end"></ion-icon>
                        </ion-button>
                    </div>
                </ion-card>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    </ion-content>
</ion-tab>
<?php
$_current_tab = 'ucenter';
include APP_PATH . "mp/view/aio/tabbar.php";
?>
</ion-tabs>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script_no_weixin.php";
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
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>