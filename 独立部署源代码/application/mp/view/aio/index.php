<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-tabs>
<ion-tab>
    <ion-header>
        <ion-toolbar color="bg">
            <ion-title color="action">首页</ion-title>
        </ion-toolbar>
        <!--
        <ion-toolbar>
            <ion-searchbar id="searchInput" placeholder="请输入测评关键词"></ion-searchbar>
        </ion-toolbar>
        -->
    </ion-header>
    <ion-content color="bg">
        <!-- 头部轮播 -->
        <div id="swiper-top-banner" class="swiper">
            <ul class="swiper-wrapper">
                <?php
                foreach ($banners as $index=>$banner){
                ?>
                <li class="swiper-slide">
                    <p>
                        <a href="<?=url('mp/Aio/detail',['id'=>$banner['id']])?>">
                            <img src="<?=generateThumbnailUrl($banner['banner_img'], 500)?>" alt="<?=$banner['name']?>">
                        </a>
                    </p>
                </li>
                <?php } ?>
            </ul>
            <div class="swiper-pagination"></div>
        </div>
        <!--热门测评-->
        <?php
            $slideCount = count($populars);
        ?>
        <div id="popular-subjects">
            <div class="index-section-title"><span>热门测评</span></div>
            <div id="swiper-popular-subjects" class="swiper">
                <div class="swiper-wrapper">
                    <?php $i=0;
                    while($i<$slideCount){
                        $popular = $populars[$i];
                        if($i%2 == 0){
                            echo '<div class="swiper-slide">';
                        }
                    ?>
                        <ion-card class="subject-card" data-subject-id="<?=$popular['id']?>">
                            <img src="<?=$popular['image_url']?>">
                            <div class="subject-card-name">
                                <ion-text color="medium"><?=$popular['name']?></ion-text>
                            </div>
                            <!--
                            <div class="subject-card-subtitle">
                                <?=$popular['subtitle']?>
                            </div>
                            -->
                            <div class="subject-card-text">
                                <p>
                                    <ion-text color="success"><?=$popular['items']?></ion-text>道题目
                                </p>
                                <p>
                                    <ion-text color="secondary"><?=formatTimes($popular['participants'])?></ion-text>人已测
                                </p>
                            </div>
                            <div class="subject-card-opt">
                                <ion-button href="<?=url('mp/Aio/detail',['id'=>$popular['id']])?>" color="action" size="small" shape="round" fill="solid">
                                    测评<!--<ion-icon name="arrow-forward-outline"></ion-icon>-->
                                </ion-button>
                            </div>
                        </ion-card>
                    <?php 
                        $i++;
                        if($i%2 == 0){
                            echo '</div>';
                        }else if($i==$slideCount){
                            echo '</div>';
                        }
                    }
                    ?>    
                </div>
                <!-- 分页器 -->
                <div class="swiper-pagination"></div>    
            </div>
        </div>
        <!--精选量表-->
        <div id="featured-subjects">
            <div class="index-section-title"><span>精选测评</span></div>
            <ul class="wzy-subject-list">
                <?php foreach ($featureds as $featured): ?>
                <ion-card class="wzy-subject-item ion-margin" data-subject-id="<?=$featured['id']?>">
                    <div class="wzy-subject-brief">
                        <div class="wzy-subject-left">
                            <ion-img class="wzy-subject-img img-thumbnail" src="<?=$featured['image_url']?>"></ion-img>
                        </div>
                        <div class="wzy-subject-right">
                            <div class="wzy-subject-name"><a href="<?=url('mp/Aio/detail',['id'=>$featured['id']])?>"><?=$featured['name']?></a></div>
                            <div class="wzy-subject-subtitle"><?=$featured['subtitle']?></div>
                            <div class="wzy-subject-label">
                                <?php foreach($featured['category_names'] as $categoryName){ ?>
                                    <span><?=$categoryName?></span>
                                <?php } ?>
                            </div>
                            <div class="wzy-subject-text">
                                <p>
                                    <ion-text color="success"><?=$featured['items']?></ion-text>道题目
                                </p>
                                <p><ion-text color="secondary"><?=formatTimes($featured['participants'])?></ion-text>人已测</p>
                            </div>
                        </div>
                    </div>
                    <div class="wzy-subject-opt">
                        <ion-button size="small" href="<?=url('mp/Aio/detail',['id'=>$featured['id']])?>" color="action" size="default" fill="solid">
                        <ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>查看详情
                        </ion-button>
                    </div>
                </ion-card>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="boundry">
            <span>我是有底线的</span>
        </div>
    </ion-content>
</ion-tab>
<?php
$_current_tab = 'index';
include APP_PATH . "mp/view/aio/tabbar.php";
?>
</ion-tabs>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script_no_weixin.php";
?>
<script type="text/javascript">

//搜索
/*
function startSearch() {
    setTimeout(function(){
        var keyword = $.trim($('#searchInput').val());
        if(keyword == ''){
            return;
        }
        window.location.href = '<?=url('mp/Aio/category')?>&name='+encodeURIComponent(keyword);
    }, 200);
}*/
//轮播
    var bannerSwiper = new Swiper('#swiper-top-banner', {
        direction: 'horizontal',
        loop: true,
        autoplay: {
            delay: 3000
        },
        // 如果需要分页器
        pagination: {
            el: '.swiper-pagination',
        },
        paginationType: 'bullets'
    });
//热门
    var popularSubjectsSwiper = new Swiper('#swiper-popular-subjects', {
        direction: 'horizontal',
        loop: false,
        autoplay: {
            delay: 3000
        },
        // 如果需要分页器
        pagination: {
            el: '.swiper-pagination',
        },
        paginationType: 'bullets'
    });
    $(document).on('click', '.swiper-wrapper .subject-card', function(e){
        var subjectId = $(this).data('subjectId');
        var subjectDetailUrl = '<?=url('mp/Aio/detail')?>';
        subjectDetailUrl = GLOBAL.func.addUrlParam(subjectDetailUrl, 'id', subjectId);
        window.location.assign(subjectDetailUrl);
        return false;
    });
//精选
    $(document).on('click', '.wzy-subject-list .wzy-subject-item', function(e){
        var subjectId = $(this).data('subjectId');
        var subjectDetailUrl = '<?=url('mp/Aio/detail')?>';
        subjectDetailUrl = GLOBAL.func.addUrlParam(subjectDetailUrl, 'id', subjectId);
        window.location.assign(subjectDetailUrl);
        return false;
    });
/******************************************处理搜索*************************************************/
/*
$(document).on('ionChange', '#searchInput', function(evt) {
    startSearch();
});
*/
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>