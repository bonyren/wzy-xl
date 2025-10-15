<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['adminRole']?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    border:false,
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    title:''
    ">
    <thead>
    <tr>
    <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
        <th data-options="field:'operate',width:200,fixed:true,align:'center',formatter:<?=JVAR?>.operate,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
    <?php } ?>
        <th data-options="field:'role_name',width:200,fixed:true,align:'center'">角色名</th>
        <th data-options="field:'privileges',width:200,align:'center'">权限</th>
        <th data-options="field:'description',width:100,align:'center'">描述</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
<?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ <?=JVAR?>.save(0); },iconCls:iconClsDefs.add">添加新角色</a>
    </div>
    <div class="line my-1"></div>
<?php } ?>
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[role_name]" class="easyui-textbox" data-options="width:120,validType:['length[1,50]']" prompt="角色名"/>
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
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="<?=JVAR?>.save(' + row.role_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.role_name) + '\')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="<?=JVAR?>.delete(' + row.role_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.role_name) + '\')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-success size-MINI radius my-1" onclick="<?=JVAR?>.authorize(' + row.role_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.role_name) + '\')" title="授权"><i class="fa fa-hand-o-right fa-lg">授权</i></a>');
            return btns.join(' ');
        },
        reload:function(){
            var that = <?=JVAR?>;
            $(that.datagrid).datagrid('reload');
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
        save:function(roleId, title){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['save']?>';
            href = GLOBAL.func.addUrlParam(href, 'roleId', roleId);
            if(roleId){
                title = "修改角色 - " + title;
            }else{
                title = "新增角色";
            }
            $(that.dialog).dialog({
                title: title,
                iconCls: roleId?iconClsDefs.edit:iconClsDefs.add,
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
        delete:function(roleId, title){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['delete']?>';
            href += href.indexOf('?') != -1 ? '&roleId=' + roleId : '?roleId='+roleId;
            $.messager.confirm('提示', sprintf('确认删除"%s"吗?', title), function(result){
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
        authorize:function(roleId, title){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['authorize']?>';
            href += href.indexOf('?') != -1 ? '&roleId='+roleId : '?roleId='+roleId;
            $(that.dialog).dialog({
                title: "角色授权 - " + title,
                iconCls: 'fa fa-hand-o-right',
                width: <?=$loginMobile?"'100%'":600?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler:function(){
                        var nodes = $(that.dialog).find('#authUserNodeTree').tree('getChecked');
                        if (!nodes.length) {
                            $.app.method.alertError(null, '请选择访问权限');
                            return false;
                        }
                        var nodeIds = [];
                        for (var i in nodes) {
                            if (nodes[i]['pid'] !== '0') {
                                nodeIds.push(nodes[i]['id']);
                            }
                        }
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, {nodeIds:nodeIds}, function(res){
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
</script>