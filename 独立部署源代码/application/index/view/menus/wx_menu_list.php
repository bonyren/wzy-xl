<?php
use app\Defs;
?>
<table id="wx_menus_treegrid" class="easyui-treegrid"
    data-options="
        url: '<?=$current_request_url?>',
        fit:true,
        fitColumns:<?=$loginMobile?'false':'true'?>,
        rownumbers: false,
        nowrap:false,
        border:false,
        lines:true,
        idField:'id',
        treeField:'name',
        onDblClickRow:function(row){
            //WX_MENUS.save(row.id)
        },
        rowStyler:WX_MENUS.convert,
        toolbar:'#wx_menus_toolbar'">
    <thead>
        <tr>
            <th align="center" field="btns" width="100">操作</th>
            <th align="left" field="name" width="100">标题</th>
            <th align="center" field="type_text" width="100">类型</th>
            <th align="center" field="sort" width="100">排序</th>
            <th field="url" width="400">链接地址</th>
        </tr>
    </thead>
</table>
<div id="wx_menus_toolbar" class="p-1">
    <div>
        <a class="easyui-linkbutton" iconCls="fa fa-plus-circle" onclick="WX_MENUS.save(0)">新增</a>
        <a class="easyui-linkbutton" iconCls="fa fa-refresh" onclick="WX_MENUS.sync(0)">同步到公众号</a>
    </div>
    <div class="line my-1"></div>
    <p class="text-red">
        注：一级菜单最多3个，每个一级菜单最多包含5个二级菜单。一级菜单最多4个汉字，二级菜单最多7个汉字。
    </p>
</div>
<script>
var WX_MENUS = {
    treegrid:'#wx_menus_treegrid',
    dialog:'#globel-dialog-div',
    convert:function(row){
        var that = WX_MENUS;
        var btns = [];
        btns.push('<a href="javascript:;" class="btn btn-outline-secondary size-MINI radius my-1" onclick="WX_MENUS.save(' + row.id + ')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
        btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="WX_MENUS.del(' + row.id + ')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
        row.btns = btns.join(' ');
        row.type_text = <?=json_encode(Defs::WX_MENU_TYPES, JSON_UNESCAPED_UNICODE)?>[row.type];
    },
    reload:function(){
        $(this.treegrid).treegrid('reload');
    },
    sync:function(){
        var that = this;
        $.messager.confirm('提示','确定同步到到公众号吗！',function(y){
            if(!y){
                return false;
            }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('Menus/wxMenuSync')?>', {}, function(res){
                $.messager.progress('close');
                if (res.code) {
                    $.app.method.tip('提示', res.msg);
                    $(that.treegrid).treegrid('reload');
                } else {
                    $.messager.alert('错误', res.msg, 'error');
                }
            }, 'json');
        });
    },
    del:function(id){
        var that = this;
        $.messager.confirm('提示','确定删除吗？',function(y){
            if(!y){
                return false;
            }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('Menus/wxMenuRemove')?>', {id:id}, function(res){
                $.messager.progress('close');
                if (res.code) {
                    $.app.method.tip('提示', res.msg);
                    $(that.treegrid).treegrid('reload');
                } else {
                    $.messager.alert('错误', res.msg, 'error');
                }
            }, 'json');
        });
    },
    save:function(id){
        var that = this;
        var id = id ? id : 0;
        var pid = 0;
        if (!id) {
            //新增
            var selected = $(this.treegrid).treegrid('getSelected');
            if (selected != null) {
                pid = selected.id;
                if(selected.pid){
                    $.app.method.alertWarning(null, "无法在该节点下添加，只支持二级菜单");
                    return;
                }
            }
        }
        var href = '<?=url('Menus/wxMenuSave')?>';
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        href = GLOBAL.func.addUrlParam(href, 'pid', pid);
        $(that.dialog).dialog({
            title: id ? '编辑菜单' : '添加菜单',
            iconCls: iconClsDefs.edit,
            width: <?=$loginMobile?"'100%'":900?>,
            height: '80%',
            cache: false,
            href: href,
            modal: true,
            onClose: $.noop,
            closable: true,
            buttons:[{
                text:'提交',
                iconCls:iconClsDefs.ok,
                handler: function(){
                    var form = $(that.dialog).find('form').eq(0);
                    if(form.length == 0){
                        $(that.dialog).dialog('close');
                        return;
                    }
                    if (!form.form('validate')) {
                        return;
                    }
                    $.messager.progress({text:'处理中，请稍候...'});
                    $.post(href, form.serialize(), function(res){
                        $.messager.progress('close');
                        if (res.code) {
                            $.app.method.tip('提示', res.msg);
                            $(that.dialog).dialog('close');
                            $(that.treegrid).treegrid('reload');
                        } else {
                            $.messager.alert('错误', res.msg, 'error');
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