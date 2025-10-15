<?php
use app\index\model\Admins as AdminsModel;
use app\Defs;
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['admins']?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    border:false,
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    title:'',
    rowStyler:<?=JVAR?>.rowStyler.bind(<?=JVAR?>)">
    <thead>
    <tr>
    <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
        <th data-options="field:'operate',width:200,align:'center',formatter:<?=JVAR?>.operate.bind(<?=JVAR?>),hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
    <?php } ?>
        <th data-options="field:'realname',width:200,align:'center'">姓名</th>
        <th data-options="field:'login_name',width:200,align:'center'">登录名</th>
        <!--
        <th data-options="field:'email',width:200,align:'center'">邮箱</th>
        -->
        <th data-options="field:'super_user',width:200,align:'center',formatter:<?=JVAR?>.formatSuper.bind(<?=JVAR?>)">类型</th>
        <th data-options="field:'role_name',width:100,align:'center'">角色</th>
        <th data-options="field:'disabled',width:200,align:'center',sortable:true,formatter:<?=JVAR?>.formatDisabled.bind(<?=JVAR?>)">状态</th>
        <th data-options="field:'last_login_time',width:200,align:'center',sortable:true">最后登录</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
<?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ <?=JVAR?>.add(); },iconCls:iconClsDefs.add">添加用户</a>
    </div>
    <div class="line my-1"></div>
<?php } ?>
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[login_name]" class="easyui-textbox" data-options="width:120,validType:['length[1,20]']" prompt="登录名" />
        </div>
        <div class="datagrid-search-form-box">
            <input name="search[realname]" class="easyui-textbox" data-options="width:120,validType:['length[1,20]']" prompt="姓名" />
        </div>
        <div class="datagrid-search-form-box">
            <a class="easyui-linkbutton search-submit" data-options="iconCls:'fa fa-search',
                            onClick:function(){ <?=JVAR?>.search(); }">搜索
            </a>
        </div>
        <div class="datagrid-search-form-box">
            <a class="easyui-linkbutton search-reset" data-options="iconCls:'fa fa-rotate-left',
                            onClick:function(){ <?=JVAR?>.reset(); }">重置
            </a>
        </div>
    </form>
</div>
<script>
    var <?=JVAR?> = {
        dialog:'#globel-dialog-div',
        datagrid:'#<?=DATAGRID_ID?>',
        searchForm:'#<?=FORM_ID?>',
        operate:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="<?=JVAR?>.edit(' + row.admin_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.realname) + '\')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="<?=JVAR?>.delete(' + row.admin_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.realname) + '\')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-success size-MINI radius my-1" onclick="<?=JVAR?>.changePwd(' + row.admin_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.realname) + '\')" title="修改密码"><i class="fa fa-key fa-lg">修改密码</i></a>');
            return btns.join(' ');
        },
        rowStyler:function (index, row) {
            //每一行会被调用两次
            if(row.disabled == <?=Defs::eDisabledStatus?>){
                return DG_ROW_CSS.rowGray;
            }
        },
        formatSuper:function(val, row){
            if(val == <?=AdminsModel::eAdminSuperRole?>){
                return '<span class="badge badge-success radius">超级管理员</span>';
            }else{
                return '<span class="badge badge-default radius">普通管理员</span>';
            }
        },
        formatDisabled:function(val, row){
            if(val == <?=Defs::eEnableStatus?>){
                return '<span class="badge badge-success radius">有效</span>';
            }else{
                return '<span class="badge badge-default radius">无效</span>';
            }
        },
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        reset:function(){
            var that = <?=JVAR?>;
            $(that.searchForm).form('reset');
            $(that.datagrid).datagrid('load', {});
        },
        search:function(){
            var that = <?=JVAR?>;
            var isValid = $(that.searchForm).form('validate');
            if(!isValid){
                return;
            }
            var paramObj = {};
            //reset the query parameter
            $.each($(that.searchForm).serializeArray(), function() {
                paramObj[this['name']] = this['value'];
            });
            $(that.datagrid).datagrid('load', paramObj);
        },
        add:function(){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['adminsAdd']?>';
            $(that.dialog).dialog({
                title: '添加管理员',
                iconCls: 'fa fa-plus-circle',
                width: <?=$loginMobile?"'100%'":450?>,
                height: 300,
                cache: false,
                href: href,
                modal: true,
                collapsible: false,
                minimizable: false,
                resizable: false,
                maximizable: false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        var $form = $(that.dialog).find('form').eq(0);
                        if($form.length == 0){
                            $(that.dialog).dialog('close');
                            return;
                        }
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, $form.serialize(), function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reload();
                            }
                        }, 'json');
                    }
                },{
                    text:'取消',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        edit:function(adminId, title){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['adminsEdit']?>';
            href = GLOBAL.func.addUrlParam(href, 'adminId', adminId);
            $(that.dialog).dialog({
                title: '修改管理员 - ' + title,
                iconCls: iconClsDefs.edit,
                width: <?=$loginMobile?"'100%'":450?>,
                height: 300,
                cache: false,
                href: href,
                modal: true,
                collapsible: false,
                minimizable: false,
                resizable: false,
                maximizable: false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        var $form = $(that.dialog).find('form').eq(0);
                        if($form.length == 0){
                            $(that.dialog).dialog('close');
                            return;
                        }
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, $form.serialize(), function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reload();
                            }
                        }, 'json');
                    }
                },{
                    text:'取消',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        delete:function(adminId, title){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['adminsDelete']?>';
            href = GLOBAL.func.addUrlParam(href, 'adminId', adminId);
            $.messager.confirm('提示', '确认删除"'+title+'"吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    }
                }, 'json');
            });
        },
        changePwd:function(adminId, title){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['adminsChangePwd']?>';
            href = GLOBAL.func.addUrlParam(href, 'adminId', adminId);
            $(that.dialog).dialog({
                title: '修改管理员"' + title +'"密码',
                iconCls: iconClsDefs.edit,
                width: <?=$loginMobile?"'100%'":450?>,
                height: 300,
                cache: false,
                href: href,
                modal: true,
                collapsible: false,
                minimizable: false,
                resizable: false,
                maximizable: false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        var $form = $(that.dialog).find('form').eq(0);
                        if($form.length == 0){
                            $(that.dialog).dialog('close');
                            return;
                        }
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, $form.serialize(), function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reload();
                            }
                        }, 'json');
                    }
                },{
                    text:'取消',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        }
    };

</script>