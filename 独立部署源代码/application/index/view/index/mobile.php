<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="szsupernan">
    <meta name="keywords" content="<?=systemSetting('general_site_keywords')?>">
    <meta name="description" content="<?=systemSetting('general_site_description')?>">
    <title><?=systemSetting('general_site_title')?></title>
    <?php
    include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "head.php";
    include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "js.php";
    include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "mobile.php";
    ?>
</head>
<body>
<div class="easyui-navpanel">
    <header>
        <div class="m-toolbar">
            <div class="m-left">
                <a href="javascript:void(0)" class="easyui-linkbutton"
                    onclick="baseModule.toggleMenu()" data-options="plain:true,iconCls:'fa fa-bars'">
                </a>
            </div>
            <div class="m-title"><?=systemSetting('general_organisation_name')?></div>
            <div class="m-right">
                <a href="javascript:;" class="easyui-splitbutton" data-options="menu:'#toparea-user-info-box',
                	iconCls:'fa fa-user-circle'">
                </a>
                <div id="toparea-user-info-box">
                    <div data-options="iconCls:'fa fa-key'" onclick="baseModule.password()">修改密码</div>
                    <div class="menu-sep"></div>
                    <div data-options="iconCls:'fa fa-sign-out'" onclick="baseModule.logout()">登出</div>
                </div>
            </div>
        </div>
    </header>
    <div id="m-content" class="easyui-navpanel m-content-box" data-options="href:'<?=$urlHrefs['main']?>',title:''"></div>
</div>


<div id="mm">
    <div id="m-left-menu" class="easyui-accordion" data-options="border:false,selected:0">
        <?php foreach ($menus as $v): if(empty($v['children'])){ continue; } ?>
        <div title="<?=$v['name']?>">
            <ul class="easyui-tree" data-options='
                data:<?=json_encode($v['children'],JSON_UNESCAPED_UNICODE)?>,
                animate:true,
                lines:true,
                onClick:baseModule.openUrl'></ul>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- 公共部分 -->
<div id="m-menu-mask" class="window-mask"></div>

<div id="globel-dialog-div" class="word-wrap" style="line-height:1.5"></div>
<div id="globel-dialog2-div" class="word-wrap" style="line-height:1.5"></div> <!-- 特殊情况可能需要弹出第2个弹出层 -->
<div id="globel-dialog3-div" class="word-wrap" style="line-height:1.5"></div> <!-- 特殊情况可能需要弹出第3个弹出层 -->

<script type="text/javascript">
var baseModule = {
	dialog: '#globel-dialog-div',
	dialog2: '#globel-dialog2-div',
	//初始化
	init:function(){
	    $('#mm').hide().css('opacity',1);
	    $('#mm').click(function(){
            baseModule.toggleMenu();
            return false;
        });
        baseModule.updateCss();
        this.sessionLife();
	},
    toggleMenu:function(){
        if($('#mm').is(':visible')){
            $('#m-menu-mask').hide();
            $('#mm').hide();
        } else {
            $('#m-menu-mask').show();
            $('#mm').slideDown();
        }
    },
	openUrl:function(node){
		if (undefined === node.url) {
			return false;
		}
		$('#m-content').panel({
			title: node.attributes.breadcrumb,
			href: node.url,
			// iconCls: node.iconCls
		});
		baseModule.toggleMenu();
		baseModule.updateCss();
	},
    updateCss:function(){
        $('#m-content').prev().css({background:'#EFF5FF ',paddingLeft:'10px'}).children('.panel-title').css({fontWeight:'normal',color:'#666'});
    },
    //退出登录
    logout: function(){
        $.messager.confirm('提示信息', '确定要退出登录吗？', function(y){
            if (!y) {
                return;
            }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post("<?=$urlHrefs['logout']?>", function(data){
                $.messager.progress('close');
                if(data.code){
                    window.location.href = data.data;
                }
            }, 'json');
        });
    },
    //防止登录超时
    sessionLife: function(){
        setInterval(function(){
            $.post("<?=$urlHrefs['sessionLife']?>", function(data){
                if(data.code == 0){
                    $.messager.show({
                        title: '系统提示',
                        msg: data.msg,
                        timeout:3000,
                        showType:'slide'
                    });
                    setTimeout(function(){
                        window.location.href = data.data;
                    }, 3000);
                }
            }, 'json');
        }, 15000);
    },
    //修改密码
    password: function(){
        var that = this;
        $(that.dialog).dialog({
            title: '修改登录密码',
            iconCls: iconClsDefs.edit,
            width: <?=$loginMobile?"'100%'":"'90%'"?>,
            height: '50%',
            cache: false,
            href: "<?=$urlHrefs['modifyPwd']?>",
            modal: true,
            collapsible: false,
            minimizable: false,
            resizable: false,
            maximizable: false,
            onClose: $.noop,
            closable: true,
            buttons:[{
                text: '确定',
                iconCls: iconClsDefs.ok,
                handler: function(){
                    $(that.dialog).find('form').eq(0).form('submit', {
                        onSubmit: function(){
                            var isValid = $(this).form('validate');
                            if (!isValid) return false;

                            $.messager.progress({text:'处理中，请稍候...'});
                            $.post('<?=$urlHrefs['modifyPwd']?>', $(this).serialize(), function(res){
                                $.messager.progress('close');
                                if(!res.code){
                                    $.app.method.alertError(null, res.msg);
                                }else{
                                    $.messager.confirm('提示', res.msg, function(result){
                                        if(result) window.location.href = res.data;
                                    });
                                }
                            }, 'json');

                            return false;
                        }
                    });
                }
            },{
                text: '取消',
                iconCls: iconClsDefs.cancel,
                handler: function(){
                    $(that.dialog).dialog('close');
                }
            }]
        });
        $(that.dialog).dialog('center');
    }
};
$(function(){
	baseModule.init();
});
</script>
<?php
include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "foot.php";
?>
</body>
</html>