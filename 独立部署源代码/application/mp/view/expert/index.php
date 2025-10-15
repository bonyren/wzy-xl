<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-tabs>
<ion-tab>
    <ion-header>
        <ion-toolbar color="bg">
            <ion-title color="action">专家预约</ion-title>
        </ion-toolbar>
        <ion-toolbar color="bg">
            <ion-searchbar id="expert-searchbar" placeholder="请输入专家关键词"></ion-searchbar>
        </ion-toolbar>
    </ion-header>
    <ion-content color="bg">
        <div class="category-content">
            <div class="category-tab">
                <ion-segment id="category-switch-segment" value="0" disabled="false" scrollable="true" class="center-block" mode="md">
                    <ion-segment-button value="0">
                        <ion-label>全部</ion-label>
                    </ion-segment-button>
                    <?php foreach ($categories as $id=>$v): ?>
                        <ion-segment-button value="<?=$id?>">
                            <ion-label><?=$v['name']?></ion-label>
                        </ion-segment-button>
                    <?php endforeach; ?>
                </ion-segment>
            </div>
            <div class="category-subject">
                <div id="expert_item_list" class="wzy-expert-list">
                </div>
                <ion-infinite-scroll id="expert_item_list_scroll">
                    <ion-infinite-scroll-content></ion-infinite-scroll-content>
                </ion-infinite-scroll>
            </div>
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
            title: "专家预约 - <?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($_studio['store_desc']??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateAppMessageShareData success');
            }
        });
        //分享朋友圈
        wx.updateTimelineShareData({ 
            title: "专家预约 - <?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
            desc: "<?=generateShareDesc($_studio['store_desc']??'')?>", // 分享描述
            link: window.location.href, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?=SITE_URL . generateThumbnailUrl(systemSetting('general_organisation_logo'), 150)?>', // 分享图标
            success: function () {
                // 设置成功
                console.log('updateTimelineShareData success');
            }
        });
    });
<?php } ?>
    var expertCategory = (function($){
        function createItemListBox(itemList) {
            let _html = ''
            for (let i = 0, l = itemList.length; i < l; i++) {
                _html += '<ion-card class="wzy-expert-item" data-expert-id="' + itemList[i].id + '">' +
                    '<div class="wzy-expert-brief">' +
                    '<div class="wzy-expert-left">' +
                    '<ion-img class="wzy-expert-img img-thumbnail" src="' + itemList[i].workimg_url + '"></ion-img>' +
                    '</div>' +
                    '<div class="wzy-expert-right">' +
                    '<div class="wzy-expert-name"><a href="<?=url('mp/Expert/detail')?>?expertId=' + itemList[i].id + '">' + itemList[i].real_name + '</a></div>' +
                    '<div class="wzy-expert-quality">' + itemList[i].expert_quality + '</div>' +
                    '<div class="wzy-expert-label">' +
                    itemList[i].target_names.map(function(name){return '<span>' + name + '</span>';}).join('') +
                    '</div>' +
                    '<div class="wzy-expert-text">' +
                    '<p>￥<ion-text color="danger">' + itemList[i].appoint_fee + '</ion-text>元\/45分钟</p>' +
                    '<p><ion-text color="secondary">' + itemList[i].consult_quantity + '</ion-text>小时咨询经验</p>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="wzy-expert-field">' +
                    itemList[i].field_names.map(function(name){return '<span>#' + name + '; </span>';}).join('') +
                    '</div>' +
                    '<div class="wzy-expert-opt">' +
                    '<ion-button size="small" href="<?=url('mp/Expert/detail')?>?expertId=' + itemList[i].id + '" color="light" size="default" fill="solid">查看详情<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon></ion-button>&nbsp;' +
                    '<ion-button size="small" href="<?=url('mp/Expert/appointTime')?>?expertId=' + itemList[i].id + '" color="action" size="default" fill="solid">立即预约<ion-icon name="time-outline" slot="end"></ion-icon></ion-button>' +
                    '</div>' +
                    '</ion-card>';

            }
            return _html;
        }
        var _expertCategoryId = 0;
        var _expertName = '';
        var _curPage = 1;
        var _pageRows = 5;
        var _pageEnd = false;

        function getItemList(callback) {
            var itemList = [];
            $.ajax({
                type: 'POST',
                url: '<?=url('mp/Expert/expert')?>',
                data: {
                    categoryId: _expertCategoryId,
                    name: _expertName,
                    page: _curPage,
                    rows: _pageRows
                },
                dataType: 'json',
                success: function(result){
                    var itemList = result.rows;
                    var pageEnd = result.page_end;
                    var _itemListBox_html = createItemListBox(itemList);
                    $('#expert_item_list').append(_itemListBox_html);
                    callback && callback(pageEnd);
                },
                error:function(){
                    TOAST.error("当前网络不可用，请检查网络！");
                    callback && callback(pageEnd);
                }
            });
        }
        function nomore(){
            $('#expert_item_list').append('<div class="text-center"><ion-note></ion-note></div>');
        }
        return {
            searchExpert(name){
                if(LOADING.isLoading()){
                    return;
                }
                _expertName = name;
                _curPage = 1;
                _pageEnd = false;
                LOADING.show('正在加载').then(()=>{
                    $('#expert_item_list').empty();
                    getItemList(function(pageEnd){
                        _pageEnd = pageEnd;
                        if(_pageEnd){
                            nomore();
                        }
                        LOADING.hide();
                    });
                });
            },
            searchCategory(id){
                if(LOADING.isLoading()){
                    return;
                }
                _expertCategoryId = id;
                _curPage = 1;
                _pageEnd = false;
                LOADING.show('正在加载').then(()=>{
                    $('#expert_item_list').empty();
                    getItemList(function(pageEnd){
                        _pageEnd = pageEnd;
                        if(_pageEnd){
                            nomore();
                        }
                        LOADING.hide();
                    });
                });
            },
            loadMore(callback){
                if(_pageEnd) {
                    callback && callback();
                    return;
                }
                if(LOADING.isLoading()){
                    return;
                }
                _curPage++;
                LOADING.show('正在加载').then(()=>{
                    getItemList(function(pageEnd){
                        _pageEnd = pageEnd;
                        if(_pageEnd){
                            nomore();
                        }
                        LOADING.hide();
                        callback && callback();
                    });
                });
            }
        };
    })(jQuery);

    $(function() {
        $(document).on('ionChange', '#expert-searchbar', function(evt) {
            var name = evt.target.value;
            expertCategory.searchExpert(name);
        });
        $(document).on('ionClear', '#expert-searchbar', function(evt) {
            expertCategory.searchExpert('');
        });
        $('#category-switch-segment').on('ionChange', function(evt){
            var id = evt.target.value;
            expertCategory.searchCategory(id);
            return false;
        });
        $('#expert_item_list_scroll').on('ionInfinite', function(evt){
            expertCategory.loadMore(function(){
                evt.target.complete();
            });
        });
        /***************************************************************************************************/
        //////////////////////////////////////////////////////
        expertCategory.searchExpert('');
    });
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>