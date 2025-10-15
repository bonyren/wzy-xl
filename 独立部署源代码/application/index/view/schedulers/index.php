<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('index/Schedulers/index')?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    onDblClickRow:<?=JVAR?>.detail,
    onLoadSuccess:<?=JVAR?>.convert,
    border:false">
    <thead>
    <tr>
        <th data-options="field:'btns',width:100,align:'center',hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'name',width:200,align:'center'">任务名称</th>
        <th data-options="field:'interval',width:150,align:'center'">执行间隔</th>
        <th data-options="field:'range',width:200,align:'center'">有效期</th>
        <th data-options="field:'status',width:100,align:'center'">状态</th>
        <th data-options="field:'last_run',width:150,align:'center',formatter:GLOBAL.func.dateTimeFilter">最近执行</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <div>
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="onClick:function(){ <?=JVAR?>.edit(0); },iconCls:'fa fa-plus'">添加计划任务</a>
    </div>
</div>
<script>
var <?=JVAR?> = {
    dialog:'#globel-dialog-div',
    datagrid:'#<?=DATAGRID_ID?>',
    convert:function(data){
        var that = <?=JVAR?>;
        var records = [];
        var statusMap = {'0':{cls:'success',label:'正常'},'1':{cls:'danger',label:'禁用'}};
        $.each(data.rows, function(i,v){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius" onclick="<?=JVAR?>.edit(' + v.id + ')" title="编辑"><i class="fa fa-pencil-square-o fa-lg"></i>编辑</a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius" onclick="<?=JVAR?>.logs(' + v.id + ',\'' + GLOBAL.func.escapeALinkStringParam(v.name) + '\')" title="日志"><i class="fa fa-align-justify fa-lg"></i>日志</a>');
            /*禁止删除
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius" onclick="<?=JVAR?>.remove(' + v.id + ')" title="删除"><i class="fa fa-trash-o fa-lg"></i>删除</a>');
            */
            $(that.datagrid).datagrid('updateRow',{
                index: i,
                row: {
                    range: v.date_time_start.substring(0,16) + ' - ' + (v.date_time_end != DEFAULT_DB_DATETIME_VALUE ? v.date_time_end : '永远'),
                    status:'<span class="badge badge-'+statusMap[v.disabled]['cls']+'">'+statusMap[v.disabled]['label']+'</span>',
                    btns:btns.join(' ')
                }
            });
        });
    },
    reload:function(){
        $(this.datagrid).datagrid('reload');
    },
    edit:function(id){
        var that = this, href = '<?=url('index/Schedulers/edit')?>?id='+(id?id:'');
        $(that.dialog).dialog({
            title: '计划任务',
            iconCls: 'fa ' + (id?'fa-pen':'fa-plus-circle'),
            width: <?=$loginMobile?"'100%'":500?>,
            height: 500,
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
    remove:function(id){
        var that = this;
        $.messager.confirm('提示', '确认删除吗?', function(result){
            if(!result) return false;
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Schedulers/remove')?>', {id:id}, function(res){
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
    detail:function(idx, row){
        var that = <?=JVAR?>;
        $(that.dialog).dialog({
            title: '计划任务 - ' + row.name,
            iconCls: 'fa fa-eye',
            width: <?=$loginMobile?"'100%'":800?>,
            height: '95%',
            cache: false,
            href: '<?=url('index/Schedulers/view')?>?id='+row.id,
            modal: true,
            collapsible: false,
            minimizable: false,
            resizable: false,
            maximizable: false,
            onClose: $.noop,
            closable: true,
            buttons:[{
                text:'关闭',
                iconCls:iconClsDefs.cancel,
                handler: function(){
                    $(that.dialog).dialog('close');
                }
            }]
        });
        $(that.dialog).dialog('center');
    },
    logs:function(id, title){
        var that = <?=JVAR?>;
        $(that.dialog).dialog({
            title: '计划任务 - ' + title,
            iconCls: 'fa fa-eye',
            width: <?=$loginMobile?"'100%'":800?>,
            height: '95%',
            cache: false,
            href: '<?=url('index/Schedulers/view')?>?id='+id,
            modal: true,
            collapsible: false,
            minimizable: false,
            resizable: false,
            maximizable: false,
            onClose: $.noop,
            closable: true,
            buttons:[{
                text:'关闭',
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