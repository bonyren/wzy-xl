<table id="categoriesDatagrid" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('index/Config/categories')?>',
    method:'post',
    toolbar:'#categoriesToolbar',
    pagination:false,
    border:false,
    fit:true,
    fitColumns:false,
    title:'',
    onLoadSuccess:function(data){
        $.each(data.rows, function(i, row){
        });
    }
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:180,fixed:true,formatter:categoriesModule.operate,align:'center'">操作</th>
        <th data-options="field:'id',width:100,align:'center'">ID</th>
        <th data-options="field:'name',width:200,align:'center'">名称</th>
        <th data-options="field:'img_url',width:120,align:'center',formatter:categoriesModule.formatImg">图片</th>
        <th data-options="field:'sort',width:100,align:'center'">排序</th>
    </tr>
    </thead>
</table>
<div id="categoriesToolbar" class="p-1">
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ categoriesModule.save(); },iconCls:iconClsDefs.add">新增</a>
    </div>
</div>
<script>
    var categoriesModule = {
        dialog:'#globel-dialog-div',
        datagrid:'#categoriesDatagrid',
        searchForm:'#categoriesToolbarForm',
        operate:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-secondary size-MINI radius" onclick="categoriesModule.save(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.name) + '\')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius" onclick="categoriesModule.delete(' + row.id + ')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
            return btns.join(' ');
        },
        formatImg:function(val, row){
            return '<img class="img-thumbnail my-1" src="' + val + '" style="height:60px;">';
        },
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        load:function(){
            $(this.datagrid).datagrid('load');
        },
        save:function(id=0, title=''){
            var that = this;
            var href = '<?=url('index/Config/categorySave')?>';
            href += href.indexOf('?') != -1 ? '&id=' + id : '?id='+id;
            if(id == 0){
                var dialogTitle = '新增分类';
                var iconCls = 'fa fa-plus-circle';
            }else{
                var dialogTitle = '修改分类 - ' + title;
                var iconCls = 'fa fa-pencil-square';
            }
            $(that.dialog).dialog({
                title: dialogTitle,
                iconCls: iconCls,
                width: <?=$loginMobile?"'100%'":450?>,
                height: '60%',
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
        delete:function(id){
            var that = this;
            var href = '<?=url('index/Config/categoryDelete')?>';
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