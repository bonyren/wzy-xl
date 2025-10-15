<table id="fieldsDatagrid" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('index/Config/fieldItems')?>',
    method:'post',
    toolbar:'#fieldsToolbar',
    pagination:false,
    border:false,
    fit:true,
    title:'',
    view:groupview,
    groupField:'group_field',
    groupFormatter:fieldsModule.formatGroup,
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:100,fixed:true,formatter:fieldsModule.operate,align:'center'">操作</th>
        <th data-options="field:'field_item',width:200,align:'center'">名称</th>
    </tr>
    </thead>
</table>
<div id="fieldsToolbar" class="p-1">
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ fieldsModule.saveField(); },iconCls:iconClsDefs.add">新增擅长类别</a>
    </div>
</div>
<script>
    var fieldsModule = {
        dialog:'#globel-dialog-div',
        datagrid:'#fieldsDatagrid',
        formatGroup:function(val, rows){
            var sections = val.split('@');
            var btns = [];
            btns.push(sections[1] + ' - ' + rows.length + ' field(s)');
            btns.push('<a href="javascript:;" class="btn btn-default size-MINI radius" onclick="fieldsModule.saveFieldItem(0, ' + sections[0] + ',\'' + GLOBAL.func.escapeALinkStringParam(sections[1]) + '\')" title="新增擅长领域"><i class="fa fa-plus"></i></a>');
            btns.push('<a href="javascript:;" class="btn btn-default size-MINI radius" onclick="fieldsModule.saveField(' + sections[0] + ',\'' + GLOBAL.func.escapeALinkStringParam(sections[1]) + '\')" title="修改类别"><i class="fa fa-pencil-square-o"></i></a>');
            btns.push('<a href="javascript:;" class="btn btn-default size-MINI radius" onclick="fieldsModule.deleteField(' + sections[0] + ')" title="删除类别"><i class="fa fa-trash-o"></i></a>');
            return btns.join(' ');
        },
        operate:function(val, row){
            if(row.id == 0){
                return '';
            }
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-default size-MINI radius" onclick="fieldsModule.saveFieldItem(' + row.id + ',0,\'' + GLOBAL.func.escapeALinkStringParam(row.field_item) + '\')" title="编辑"><i class="fa fa-pencil-square-o fa-lg"></i></a>');
            btns.push('<a href="javascript:;" class="btn btn-default size-MINI radius" onclick="fieldsModule.deleteFieldItem(' + row.id + ')" title="删除"><i class="fa fa-trash-o fa-lg"></i></a>');
            return btns.join(' ');
        },
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        saveFieldItem:function(id=0, fieldId=0, title=''){
            var that = this;
            if(id == 0){
                //新增
                var href = '<?=url('index/Config/fieldItemSave')?>';
                href += href.indexOf('?') != -1 ? '&fieldId=' + fieldId : '?fieldId='+fieldId;
                var dialogTitle = '新增擅长领域 - ' + title;
                var iconCls = 'fa fa-plus-circle';
            }else{
                //修改
                var href = '<?=url('index/Config/fieldItemSave')?>';
                href += href.indexOf('?') != -1 ? '&id=' + id : '?id='+id;
                var dialogTitle = '修改擅长领域 - ' + title;
                var iconCls = 'fa fa-pencil-square';
            }
            $(that.dialog).dialog({
                title: dialogTitle,
                iconCls: iconCls,
                width: <?=$loginMobile?"'100%'":450?>,
                height: '30%',
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
                    text:'保存',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        $(that.dialog).find('form').eq(0).form('submit', {
                            onSubmit: function(){
                                var isValid = $(this).form('validate');
                                if (!isValid) return false;
                                $.messager.progress({text:'处理中，请稍候...'});
                                $.post(href, $(this).serialize(), function(res){
                                    $.messager.progress('close');
                                    if(!res.code){
                                        $.app.method.alertError(null, res.msg);
                                    }else{
                                        $.app.method.tip('提示', res.msg, 'info');
                                        $(that.dialog).dialog('close');
                                        that.reload();
                                    }
                                }, 'json');
                                return false;
                            }
                        });
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
        deleteFieldItem:function(id){
            var that = this;
            var href = '<?=url('index/Config/fieldItemDelete')?>';
            href += href.indexOf('?') != -1 ? '&id=' + id : '?id='+id;
            $.messager.confirm('提示', '确认删除吗?', function(result){
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
        saveField:function(id=0, title=''){
            var that = this;
            if(id == 0){
                //新增
                var href = '<?=url('index/Config/fieldSave')?>';
                var dialogTitle = '新增擅长领域类别';
                var iconCls = 'fa fa-plus-circle';
            }else{
                //修改
                var href = '<?=url('index/Config/fieldSave')?>';
                href += href.indexOf('?') != -1 ? '&id=' + id : '?id='+id;
                var dialogTitle = '修改擅长领域类别 - ' + title;
                var iconCls = 'fa fa-pencil-square';
            }
            $(that.dialog).dialog({
                title: dialogTitle,
                iconCls: iconCls,
                width: <?=$loginMobile?"'100%'":450?>,
                height: '30%',
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
                    text:'保存',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        $(that.dialog).find('form').eq(0).form('submit', {
                            onSubmit: function(){
                                var isValid = $(this).form('validate');
                                if (!isValid) return false;
                                $.messager.progress({text:'处理中，请稍候...'});
                                $.post(href, $(this).serialize(), function(res){
                                    $.messager.progress('close');
                                    if(!res.code){
                                        $.app.method.alertError(null, res.msg);
                                    }else{
                                        $.app.method.tip('提示', res.msg, 'info');
                                        $(that.dialog).dialog('close');
                                        that.reload();
                                    }
                                }, 'json');
                                return false;
                            }
                        });
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
        deleteField:function(id){
            var that = this;
            var href = '<?=url('index/Config/fieldDelete')?>';
            href += href.indexOf('?') != -1 ? '&id=' + id : '?id='+id;
            $.messager.confirm('提示', '确认删除吗?', function(result){
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

        }
    };
</script>