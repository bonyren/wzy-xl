<script src="/static/mp.ionic/js/jquery-2.1.4.js?<?=STATIC_VER?>" charset="UTF-8"></script>
<script src="/static/mp.ionic/js/jquery.cookie.js?<?=STATIC_VER?>" charset="UTF-8"></script>
<script src="/static/mp.ionic/js/jquery.md5.js?<?=STATIC_VER?>" charset="UTF-8"></script>
<script src="/static/mp.ionic/js/jquery.watermark.js?<?=STATIC_VER?>" charset="UTF-8"></script>
<script src="/static/mp.ionic/js/common.js?<?=STATIC_VER?>" charset="UTF-8"></script>

<script type="module" src="/static/mp.ionic/dist8/ionic/ionic.esm.js"></script>
<script nomodule src="/static/mp.ionic/dist8/ionic/ionic.js"></script>
<script type="module" src="/static/mp.ionic/dist8/ionicons/ionicons.esm.js"></script>
<script nomodule src="/static/mp.ionic/dist8/ionicons/ionicons.js"></script>
<script src="/static/mp.ionic/3rd/swiper-bundle.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="https://res.wx.qq.com/open/js/jweixin-1.6.0.js"></script>
<?php
if (\think\Env::get('production')) {
?>
<?php
    $controller = request()->controller();
    $action = request()->action();
    try{
        $wxConfig = '';
        $config = config('wx.official_account');
        $app = \EasyWeChat\Factory::officialAccount($config);
        $wxConfig = $app->jssdk->buildConfig([
            'updateAppMessageShareData',
            'updateTimelineShareData',
            'onMenuShareAppMessage',
            'onMenuShareTimeline',
            'checkJsApi',
            'hideAllNonBaseMenuItem',
            'hideOptionMenu',
            'hideMenuItems'
        ]);
    }catch(Exception $e){
        echo "<script>alert('微信公众号配置异常: " . $e->getMessage() . "')</script>";
    }
?>
<script type="text/javascript">
    wx.config(<?=$wxConfig?>);
    wx.ready(function(){
        //from error.php the $_current_url and $_home_url not set
        var _current_url = "<?=$_current_url??''?>";
        var _home_url = "<?=$_home_url??''?>";
        //隐藏微信分享, 只允许首页分享 /mp/xlgw
        <?php if(empty($_current_url) || empty($_home_url) || ($_current_url != $_home_url 
            && !in_array(strtolower(request()->controller() . '-' . request()->action()),[
                'subject-category',
                'health-category',
                'subject-detail',
                'subject-combination_test',
                'subject-survey_test',
                'subject-report',
                'subject-reportgroup',
                'expert-index',
                'expert-detail'
            ]))){ ?>
            //不允许分享
            wx.hideAllNonBaseMenuItem();
            //wx.hideOptionMenu();
        <?php }else{ ?>
            wx.hideMenuItems({
                menuList: ["menuItem:copyUrl","menuItem:openWithQQBrowser","menuItem:openWithSafari"]
            });
        <?php } ?>
    });
    wx.error(function(res){
        //TOAST.error('wx.config error');
    });
</script>
<?php } ?>
<script>
    $(window).on('load',function(){
        $("#loader-wrapper").fadeOut();
    });
</script>