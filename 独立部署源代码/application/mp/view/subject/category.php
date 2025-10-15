<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-tabs>
<ion-tab>
    <ion-header>
        <ion-toolbar color="bg">
            <ion-title color="action"><?=$pageTitle?></ion-title>
        </ion-toolbar>
        <ion-toolbar color="bg">
            <ion-searchbar id="subject-searchbar" placeholder="请输入测评关键词" value="<?=$name?>"></ion-searchbar>
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
                <div id="category_item_list" class="wzy-subject-list"></div>
                <ion-infinite-scroll id="category_item_list_scroll">
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
            title: "<?=sanitizeStringForJsVariable($pageTitle??'')?> - <?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
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
            title: "<?=sanitizeStringForJsVariable($pageTitle??'')?> - <?=sanitizeStringForJsVariable($_studio['store_name']??'')?>", // 分享标题
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
    var subjectCategory = (function($){
        function createItemListBox(itemList) {
            var _html = ''
            for(var i = 0, len = itemList.length; i < len; i++) {
                _html += '<ion-card class="wzy-subject-item" data-subject-id="' + itemList[i].id +'">' +
                        '<div class="wzy-subject-brief">' + 
                            '<div class="wzy-subject-left">' +
                                ((itemList[i].currentPrice <= 0)?'<ion-badge color="success" class="wzy-tag-label free">免费</ion-badge>':'') + 
                                '<ion-img class="wzy-subject-img img-thumbnail" src="' + itemList[i].imageUrl + '"></ion-img>' +
                            '</div>' +
                            '<div class="wzy-subject-right">' +
                                '<div class="wzy-subject-name"><a href="<?=url('mp/Subject/detail')?>?id=' + itemList[i].id + '">' + itemList[i].title + '</a></div>' +
                                '<div class="wzy-subject-subtitle">' + itemList[i].subTitle + '</div>' +
                                '<div class="wzy-subject-label">' +
                                    itemList[i].category_names.map(function(name){return '<span>' + name + '</span>';}).join('') +
                                '</div>' +
                                '<div class="wzy-subject-text">' +
                                    '<p>' <?php if(systemSetting('subject_show_price') == 'yes'){ ?>+ '<span class="wzy-subject-price">￥' + itemList[i].currentPrice + '</span> | '<?php } ?> + '<ion-text color="success">' + itemList[i].items + '</ion-text>道题目</p>' +
                                    '<p><ion-text color="secondary">' + itemList[i].participants + '</ion-text>人已测</p>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="wzy-subject-opt">' +
                            '<ion-button size="small" href="<?=url('mp/Subject/detail')?>?id=' + itemList[i].id + '" color="action" size="default" fill="solid">查看详情<ion-icon name="arrow-forward-circle-outline" slot="end"></ion-icon></ion-button>' +
                        '</div>' +
                    '</ion-card>';
            }
            return _html;
        }
        var _subjectCategoryId = 0;
        var _subjectName = '';
        var _curPage = 1;
        var _pageRows = 100;//5->100
        var _pageEnd = false;

        function getItemList(callback) {
            var itemList = [];
            $.ajax({
                type: 'POST',
                url: '<?=$category_url?>',
                data: {
                    categoryId: _subjectCategoryId,
                    name: _subjectName,
                    page: _curPage,
                    rows: _pageRows
                },
                dataType: 'json',
                success: function(result){
                    var rows = result.rows;
                    var pageEnd = result.page_end;
                    rows.forEach(function(v){
                        itemList.push({
                            id:v.id,
                            title:v.name,
                            subTitle:v.subtitle,
                            items:v.items,
                            currentPrice:v.current_price,
                            participants:v.participants,
                            imageUrl:v.image_url,
                            category_names:v.category_names
                        });
                    });
                    var _itemListBox_html = createItemListBox(itemList);
                    $('#category_item_list').append(_itemListBox_html);
                    callback && callback(pageEnd);
                },
                error:function(){
                    TOAST.error("当前网络不可用，请检查网络！");
                    callback && callback(pageEnd);
                }
            });
        }
        function nomore(){
            //$('#category_item_list').append('<div class="text-center"><ion-note></ion-note></div>');
        }
        return {
            searchSubject(name){
                if(LOADING.isLoading()){
                    return;
                }
                _subjectName = name;
                _curPage = 1;
                _pageEnd = false;
                LOADING.show('正在加载').then(()=>{
                    $('#category_item_list').empty();
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
                _subjectCategoryId = id;
                _curPage = 1;
                _pageEnd = false;
                LOADING.show('正在加载').then(()=>{
                    $('#category_item_list').empty();
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
        $(document).on('ionChange', '#subject-searchbar', function(evt) {
            var name = evt.target.value;
            subjectCategory.searchSubject(name);
        });
        $(document).on('ionClear', '#subject-searchbar', function(evt) {
            subjectCategory.searchSubject('');
        });
        $('#category-switch-segment').on('ionChange', function(evt){
            var id = evt.target.value;
            subjectCategory.searchCategory(id);
            return false;
        });
        $('#category_item_list_scroll').on('ionInfinite', function(evt){
            console.log('ionInfinite');
            //滚动加载，第一次进入才有效，切换类别无法后续触发该事件
            subjectCategory.loadMore(function(){
                evt.target.complete();
            });
        });
        /***************************************************************************************************/
        $(document).on('click', '.wzy-subject-list .wzy-subject-item', function(e){
            var subjectId = $(this).data('subjectId');
            if(!subjectId){
                return false;
            }
            var subjectDetailUrl = '<?=url('mp/Subject/detail')?>';
            subjectDetailUrl = GLOBAL.func.addUrlParam(subjectDetailUrl, 'id', subjectId);
            window.location.assign(subjectDetailUrl);
            return false;
        });
        //////////////////////////////////////////////////////
        subjectCategory.searchSubject('<?=$name?>');
    });
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>