<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="Blink|webkit">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
<meta name="author" content="szsupernan">
<!--
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
-->
<meta name="keywords" content="<?=systemSetting('general_site_keywords')?>">
<meta name="description" content="<?=systemSetting('general_site_description')?>">
<title><?=systemSetting('general_site_title')?></title>
<?php
include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "head.php";
include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "js.php";
?>
</head>
<body class="easyui-layout">

<div class="loader"></div>

<!-- 头部 -->
<div id="toparea" data-options="region:'north',border:false,height:48">
	<div id="topmenu" class="d-flex flex-row justify-content-between align-items-center">
        <div class="nav-left d-flex flex-row align-items-center">
            <div class="logo"><img src="<?=systemSetting('general_organisation_logo')?>" height="40" /></div>
            <div class="pl-1"><h3><?=systemSetting('general_organisation_name')?></h3></div>
			<div class="pl-1"><i>v<?=VERSION?></i></div>
        </div>
        <div class="nav-right d-flex flex-row align-items-center">
            <!------------------------------------------------------------------------------------------>
            <a href="javascript:;" onclick="baseModule.openUrl({'url':'<?=$urlHrefs['main']?>'})"
               class="easyui-linkbutton" data-options="plain:true,iconCls:'fa fa-home'">首页
            </a>
            <!------------------------------------------------------------------------------------------>
            <a href="javascript:;" class="easyui-linkbutton" data-options="plain:true,iconCls:'fa fa-bell',onClick:function(){baseModule.messages();}">
                <span class="badge badge-warning"><?=$loginUserInfos['unreadMessageCount']?></span>
            </a>
            <!------------------------------------------------------------------------------------------>
            <a href="javascript:;" class="easyui-splitbutton" data-options="menu:'#toparea-user-info-box',iconCls:'fa fa-user-circle'">
                <?=$loginUserInfos['realname']?>
            </a>
            <div id="toparea-user-info-box">
                <div data-options="iconCls:'fa fa-key'" onclick="baseModule.password()">修改密码</div>
                <div class="menu-sep"></div>
                <div data-options="iconCls:'fa fa-sign-out'" onclick="baseModule.logout()">登出</div>
            </div>
            <!------------------------------------------------------------------------------------------>
            <a href="javascript:;" class="easyui-splitbutton" data-options="menu:'#toparea-help-box',
                iconCls:'fa fa-question-circle'">帮助
            </a>
            <div id="toparea-help-box">
				<div data-options="iconCls:'fa fa-diamond'"
                     onclick="baseModule.license()">授权
                </div>
                <div data-options="iconCls:'fa fa-paper-plane'"
                     onclick="$.messager.alert('反馈', '请邮件: wzycoding@qq.com, 微信: wzyer_com联系，谢谢!', 'info');">反馈
                </div>
                <div data-options="iconCls:'fa fa-flag'"
                     onclick="$.messager.alert('提示', 'ver: <?=VERSION?>', 'info');">版本
                </div>
                <div data-options="iconCls:'fa fa-eraser'"
                     onclick="baseModule.clearCache()">清理缓存
                </div>
            </div>
        </div>
	</div>
</div>

<!-- 左侧菜单 -->
<div id="leftarea" data-options="iconCls:'fa fa-compass',
	region:'west',
	title:'导航',
	split:true,
	width:200">
	<div id="leftmenu" class="easyui-accordion" data-options="fit:true,border:false"></div>
</div>
<!-- 内容 -->
<div id="mainarea" data-options="region:'center',split:true,href:'<?=$urlHrefs['main']?>',title:'首页',iconCls:'fa fa-home'"></div>

<!-- 公共部分 -->
<div id="globel-dialog-div" class="word-wrap" style="line-height:1.5"></div>
<div id="globel-dialog2-div" class="word-wrap" style="line-height:1.5"></div> <!-- 特殊情况可能需要弹出第2个弹出层 -->
<div id="globel-dialog3-div" class="word-wrap" style="line-height:1.5"></div> <!-- 特殊情况可能需要弹出第3个弹出层 -->
<div id="dialog-uuid-replace"></div>
<?php
include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "foot.php";
?>
<script type="text/javascript">
window.baseModule = {
	dialog: '#globel-dialog-div',
	dialog2: '#globel-dialog2-div',
	leftNav: '#leftnav',
	//初始化
	init: function(){
		this.loadLeft();
		this.sessionLife();
		this.tip();
	},
	
	//登录默认提示
	tip: function(){
		$.messager.show({
			title:'登录提示',
			msg:'您好！<?=$loginUserInfos['username']?> 欢迎回来！<br/>最后登录时间：<?=$loginUserInfos['lastlogintime']?><br/>最后登录IP：<?=$loginUserInfos['lastloginip']?>',
			timeout:5000,
			showType:'slide'
		});	
	},
	//load the command menu according to the user
	loadLeft: function(){
		var that = this;
		//开始获取左侧栏目
		$.ajax({
			type: 'POST',
			url: "<?=$urlHrefs['loadLeftMenu']?>",
			data: {},
			cache: false,
			beforeSend: function(){
				that.removeLeft();
				//更新标题名称
				var loading = '<div class="panel-loading">Loading...</div>';
				$("#leftmenu").accordion("add", {content: loading});
			},
			success: function(data){
				that.removeLeft();
				//左侧内容更新
				$.each(data, function(i, menu) {
					var content = '';
					if(menu.children){
						var treedata = $.toJSON(menu.children);
						content = '<ul class="easyui-tree" data-options=\'data:' + treedata + ',animate:true,lines:true,onClick:function(node){baseModule.openUrl(node)}\'></ul>';
					}
					$("#leftmenu").accordion("add", {title: menu.name, content: content, iconCls: menu.iconCls, selected:false});
				});
				$("#leftmenu").accordion("select", 0);
			}
		});
		//如果左侧隐藏则进行展开
		if($('body').layout('panel', 'west').panel("options").collapsed){
			$('body').layout('expand', 'west');
		}
	},
	//移除左侧栏目,remove twice
	removeLeft: function(stop, titles){
		var pp = $("#leftmenu").accordion("panels");
		$.each(pp, function(i, p) {
			if(p){
				var t = p.panel("options").title;
				if(titles && titles.length){
					for(var k = 0; k < titles.length; k++){
						if(titles[k] == t) $("#leftmenu").accordion("remove", t);
					}
				}else{
					$("#leftmenu").accordion("remove", t);
				}
			}
		});
		var p = $('#leftmenu').accordion('getSelected');
		if(p) {
			var t = p.panel('options').title;
			if(titles && titles.length){
				for(var k = 0; k < titles.length; k++){
					if(titles[k] == t) $("#leftmenu").accordion("remove", t);
				}
			}else{
				$("#leftmenu").accordion("remove", t);
			}
		}
		if(!stop){
			this.removeLeft(true, titles);
		}
	},
	//显示打开内容
	openUrl: function(node){
		if (undefined === node.url) {
			return false;
		}
		/*the following can work well, but can't change the panel icon dynamically
		$('#mainarea').panel('setTitle', node.attributes.breadcrumb);
		$('#mainarea').panel('refresh', node.url);
		*/
        $('body').layout('panel','center').panel({
			title: node.attributes ? node.attributes.breadcrumb : '首页',
			href: node.url,
			iconCls: node.iconCls ? node.iconCls : 'fa fa-home'
		});
	},
	//退出登录
	logout: function(){
		/*
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
		});*/
		$.messager.confirm('提示信息', '确定要退出登录吗？', function(result){
			if(result) window.location.href = '<?=$urlHrefs['logout']?>';
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
	license:function(){
		var that = this;
		$(that.dialog).dialog({
			title: '授权',
			iconCls: iconClsDefs.edit,
			width: <?=$loginMobile?"'100%'":"'30%'"?>,
			height: '50%',
			cache: false,
			href: "<?=$urlHrefs['license']?>",
			modal: true,
			collapsible: false,
			minimizable: false,
			resizable: false,
			maximizable: false,
			onClose: $.noop,
			closable: true,
			buttons:[{
				text: '关闭',
				iconCls: iconClsDefs.cancel,
				handler: function(){
					$(that.dialog).dialog('close');
				}
			}]
		});
		$(that.dialog).dialog('center');
	},
	clearCache: function(){
		$.post("<?=$urlHrefs['clearCache']?>", function(data){
			$.messager.show({
				title: '系统提示',
				msg: data.msg,
				timeout: 3000,
				showType: 'slide'
			});
		}, 'json');
	},
	//修改密码
	password: function(){
		var that = this;
		$(that.dialog).dialog({
			title: '修改登录密码',
			iconCls: iconClsDefs.edit,
			width: <?=$loginMobile?"'100%'":"'30%'"?>,
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
									//将保存密码的cookie设置清除
									$.messager.alert({
										title:'提示',
										msg:'修改密码成功，请重新登录。',
										icon:'info',
										fn:function(){
											window.location.href = res.data;
										}
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
	},
	help: function(topicId){
		var that = this;
		var href = '<?=url('index/Help/help')?>';
		href += href.indexOf('?') != -1 ? '&topicId=' + topicId : '?topicId='+topicId;
		$(that.dialog2).dialog({
			title: '帮助',
			iconCls: 'fa fa-question-circle-o',
			width: <?=$loginMobile?"'100%'":"'60%'"?>,
			height: '100%',
			cache: false,
			href: href,
			modal: true,
			collapsible: false,
			minimizable: false,
			resizable: false,
			maximizable: false,
			buttons:[
				{
					text:'关闭',
					iconCls:iconClsDefs.cancel,
					handler: function(){
						$(that.dialog2).dialog('close');
					}
				}
			]
		});
		$(that.dialog2).dialog('center');
		return false;
	},
	messages: function(){
		var that = this;
		var href = '<?=url('index/Messages/index')?>';
		$(that.dialog).dialog({
			title: '消息中心',
			iconCls: 'fa fa-bell',
			width: <?=$loginMobile?"'100%'":"'60%'"?>,
			height: '100%',
			cache: false,
			href: href,
			modal: true,
			collapsible: false,
			minimizable: false,
			resizable: false,
			maximizable: false,
			onClose: $.noop,
			closable: true,
			buttons:[
				{
					text:'关闭',
					iconCls:iconClsDefs.cancel,
					handler: function(){
						$(that.dialog).dialog('close');
					}
				}
			]
		});
		$(that.dialog).dialog('center');
		return false;
	}
};
$(function(){
	baseModule.init();
	$('.loader').hide();
});
</script>
<?php
include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "foot.php";
?>
</body>
</html>