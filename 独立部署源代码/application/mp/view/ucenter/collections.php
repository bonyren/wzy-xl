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
</ion-header>
<ion-content color="bg">
    <?php if (empty($rows)): ?>
        <div class="todo_empty">
            <img src="/static/mp.ionic/img/nodata_todo.png">
            <h5>没有任何收藏</h5>
            <p>快去完成测评，了解自己！</p>
        </div>
        <?php else: ?>
        <ul class="wzy-subject-list">
            <?php foreach ($rows as $row): ?>
            <ion-card class="wzy-subject-item ion-margin">
                <div class="wzy-subject-brief">
                    <div class="wzy-subject-left">
                        <img class="wzy-subject-img img-thumbnail" src="<?=$row['image_url']?>">
                    </div>
                    <div class="wzy-subject-right">
                        <div class="wzy-subject-name"><?=$row['name']?></div>
                        <div class="wzy-subject-label">
                            <?php foreach($row['category_names'] as $categoryName){ ?>
                                <span><?=$categoryName?></span>
                            <?php } ?>
                        </div>
                        <div class="wzy-subject-text">
                            <p><?=$row['items']?>道题目</p>
                            <p><?=formatTimes($row['participants'])?>人已测</p>
                        </div>
                    </div>
                </div>
                <div class="wzy-subject-opt">
                    <ion-button href="<?=url('mp/Subject/detail',['id'=>$row['id']])?>" fill="outline" size="small" color="action">
                        查看详情<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>
                    </ion-button>
                </div>
            </ion-card>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
</ion-content>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>