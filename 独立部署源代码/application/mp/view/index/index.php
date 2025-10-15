<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
use app\index\logic\Defs as IndexDefs;
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
        <?php
        if(in_array(IndexDefs::STORE_INDEX_SECTION_BANNER, $_studio['store_index_sections'])){ 
        ?>
        <div id="swiper-top-banner" class="swiper">
            <ul class="swiper-wrapper">
                <?php
                foreach ($banners as $index=>$banner){
                ?>
                <li class="swiper-slide">
                    <p>
                        <a href="<?=url('mp/Subject/detail',['id'=>$banner['id']])?>">
                            <img src="<?=generateThumbnailUrl($banner['banner_img'], 500)?>" alt="<?=$banner['name']?>">
                        </a>
                    </p>
                    <!--
                    <p class="subject-name" style="text-shadow:0px 0px #FFF, 1px 1px #000;">
                        <?=$banner['name']?>
                    </p>
                    -->
                </li>
                <?php } ?>
            </ul>
            <div class="swiper-pagination"></div>
        </div>
        <?php } ?>
        <!--热门测评-->
        <?php
        if(in_array(IndexDefs::STORE_INDEX_SECTION_POPULAR, $_studio['store_index_sections'])){ 
        ?>
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
                                    <?php if(systemSetting('subject_show_price') == 'yes'){ ?>
                                       <span class="subject-card-price">￥<?=$popular['current_price']?></span> | 
                                    <?php } ?>
                                    <ion-text color="success"><?=$popular['items']?></ion-text>道题目
                                </p>
                                <p>
                                    <ion-text color="secondary"><?=formatTimes($popular['participants'])?></ion-text>人已测
                                </p>
                            </div>
                            <div class="subject-card-opt">
                                <ion-button href="<?=url('mp/Subject/detail',['id'=>$popular['id']])?>" color="action" size="small" shape="round" fill="solid">
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
        <?php } ?>
        <!--精选量表-->
        <?php
        if(in_array(IndexDefs::STORE_INDEX_SECTION_FEATURED, $_studio['store_index_sections'])){ 
        ?>
        <div id="featured-subjects">
            <div class="index-section-title"><span>精选测评</span></div>
            <ul class="wzy-subject-list">
                <?php foreach ($featureds as $featured): ?>
                <ion-card class="wzy-subject-item ion-margin" data-subject-id="<?=$featured['id']?>">
                    <div class="wzy-subject-brief">
                        <div class="wzy-subject-left">
                            <?php if($featured['current_price'] <= 0){ ?>
                            <ion-badge color="success" class="wzy-tag-label free">免费</ion-badge>
                            <?php } ?>
                            <ion-img class="wzy-subject-img img-thumbnail" src="<?=$featured['image_url']?>"></ion-img>
                        </div>
                        <div class="wzy-subject-right">
                            <div class="wzy-subject-name"><a href="<?=url('mp/Subject/detail',['id'=>$featured['id']])?>"><?=$featured['name']?></a></div>
                            <div class="wzy-subject-subtitle"><?=$featured['subtitle']?></div>
                            <div class="wzy-subject-label">
                                <?php foreach($featured['category_names'] as $categoryName){ ?>
                                    <span><?=$categoryName?></span>
                                <?php } ?>
                            </div>
                            <div class="wzy-subject-text">
                                <p>
                                    <?php if(systemSetting('subject_show_price') == 'yes'){ ?>
                                       <span class="wzy-subject-price">￥<?=$featured['current_price']?></span> | 
                                    <?php } ?>
                                    <ion-text color="success"><?=$featured['items']?></ion-text>道题目
                                </p>
                                <p><ion-text color="secondary"><?=formatTimes($featured['participants'])?></ion-text>人已测</p>
                            </div>
                        </div>
                    </div>
                    <div class="wzy-subject-opt">
                        <ion-button size="small" href="<?=url('mp/Subject/detail',['id'=>$featured['id']])?>" color="action" size="default" fill="solid">
                        <ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon>查看详情
                        </ion-button>
                    </div>
                </ion-card>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php } ?>
        <div class="boundry">
            <span>我是有底线的</span>
        </div>
    </ion-content>
</ion-tab>
<?php
include APP_PATH . "mp/view/common/tabbar.php";
?>
</ion-tabs>
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
            title: "<?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($_studio['store_desc']??'')?>", // 分享描述
            link: "<?=SITE_URL . $_home_url?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateAppMessageShareData success');
            }
        });
        //分享朋友圈
        wx.updateTimelineShareData({ 
            title: "<?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($_studio['store_desc']??'')?>", // 分享描述
            link: "<?=SITE_URL. $_home_url?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateTimelineShareData success');
            }
        });
        wx.onMenuShareAppMessage({ 
            title: "<?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($_studio['store_desc']??'')?>", // 分享描述
            link: "<?=SITE_URL . $_home_url?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('onMenuShareAppMessage success');
            }
        });
        wx.onMenuShareTimeline({ 
            title: "<?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($_studio['store_desc']??'')?>", // 分享描述
            link: "<?=SITE_URL . $_home_url?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('onMenuShareTimeline success');
            }
        });
    });
<?php } ?>
//搜索
/*
function startSearch() {
    setTimeout(function(){
        var keyword = $.trim($('#searchInput').val());
        if(keyword == ''){
            return;
        }
        window.location.href = '<?=url('mp/Subject/category')?>&name='+encodeURIComponent(keyword);
    }, 200);
}*/
//轮播
<?php
if(in_array(IndexDefs::STORE_INDEX_SECTION_BANNER, $_studio['store_index_sections'])){ 
?>
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
<?php } ?>
//热门
<?php
if(in_array(IndexDefs::STORE_INDEX_SECTION_POPULAR, $_studio['store_index_sections'])){ 
?>
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
        var subjectDetailUrl = '<?=url('mp/Subject/detail')?>';
        subjectDetailUrl = GLOBAL.func.addUrlParam(subjectDetailUrl, 'id', subjectId);
        window.location.assign(subjectDetailUrl);
        return false;
    });
<?php } ?>
//精选
<?php
if(in_array(IndexDefs::STORE_INDEX_SECTION_FEATURED, $_studio['store_index_sections'])){ 
?>
    $(document).on('click', '.wzy-subject-list .wzy-subject-item', function(e){
        var subjectId = $(this).data('subjectId');
        var subjectDetailUrl = '<?=url('mp/Subject/detail')?>';
        subjectDetailUrl = GLOBAL.func.addUrlParam(subjectDetailUrl, 'id', subjectId);
        window.location.assign(subjectDetailUrl);
        return false;
    });
<?php } ?>
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