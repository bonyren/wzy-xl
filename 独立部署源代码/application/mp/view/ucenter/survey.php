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
                        <img class="wzy-subject-img img-thumbnail" src="<?=$row['banner']?>">
                    </div>
                    <div class="wzy-subject-right">
                        <div class="wzy-subject-name"><?=$row['name']?></div>
                        <div class="wzy-subject-text">
                            <p><?=$row['subject_count']?>个量表</p>
                        </div>
                        <div class="wzy-subject-text">
                            <p><?=date('Y-m-d H:i', strtotime($row['ctime']))?>开始</p>
                        </div>
                    </div>
                </div>
                <div class="wzy-subject-opt">
                    <ion-button href="<?=url('mp/Subject/survey_result',['survey_order_id'=>$row['id']])?>" fill="outline" size="small" color="action">
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
                        <img class="wzy-subject-img img-thumbnail" src="<?=$rowCompleted['banner']?>">
                    </div>
                    <div class="wzy-subject-right">
                        <div class="wzy-subject-name"><?=$rowCompleted['name']?></div>
                        <div class="wzy-subject-text">
                            <p><?=$rowCompleted['subject_count']?>个量表</p>
                        </div>
                        <div class="wzy-subject-text">
                            <p><?=date('Y-m-d H:i', strtotime($rowCompleted['ctime']))?>开始</p>
                        </div>
                    </div>
                </div>
                <?php if($rowCompleted['cfg_view_report']){ ?>
                <div class="wzy-subject-opt">
                    <ion-button href="<?=url('mp/Subject/reportGroup',['survey_order_id'=>$rowCompleted['id']])?>" fill="outline" size="small" color="action">
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
<script>
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