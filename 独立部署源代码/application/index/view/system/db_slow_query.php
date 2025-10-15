<table id="dbSlowQueryDatagrid" class="easyui-datagrid" data-options="
    striped:true,
    url:'<?=$urlHrefs['index']?>',
    method:'post',
    toolbar:'#dbSlowQueryToolbar',
    fit:true,
    fitColumns:true,
    nowrap:false,
    rownumbers:false,
    selectOnCheck:false,
    checkOnSelect:false,
    singleSelect:true,
    pagination:true,
    pageSize:30,
    rowStyler:dbSlowQueryModule.rowstyler,
    border:false">
    <thead>
        <tr>
            <th field="opt" width="60" align="center" data-options="formatter:dbSlowQueryModule.formatOpt">操作</th>
            <th data-options="field:'occur_time',width:60,sortable:true">时间</th>
            <th data-options="field:'occur_user',width:50">用户</th>
            <th data-options="field:'occur_thread',width:50">线程</th>
            <th data-options="field:'query_time',width:50,sortable:true">查询时间(秒)</th>
            <th data-options="field:'lock_time',width:50,sortable:true">锁定时间(秒)</th>
            <th data-options="field:'rows_sent',width:50,sortable:true">返回行数</th>
            <th data-options="field:'rows_examined',width:50,sortable:true">扫描行数</th>
            <th data-options="field:'occur_sql',width:300">sql</th>
            <th data-options="field:'fixed_time',width:100">解决时间</th>
        </tr>
    </thead>
</table>
<div id="dbSlowQueryToolbar" class="p-1">
    <a class="easyui-linkbutton" data-options="iconCls:'fa fa-trash',
                    onClick:function(){ dbSlowQueryModule.clear(); }">
                    清空
    </a>
</div>
<script type="text/javascript">
    var dbSlowQueryModule = {
        datagrid:'#dbSlowQueryDatagrid',
        reload:function(){
            var that = this;
            $(that.datagrid).datagrid('reload');
        },
        reset:function(){
            var that = this;
            $(that.datagrid).datagrid('load', {});
        },
        rowstyler:function (idx,row) {
            if (row.status == 1) {
                return DG_ROW_CSS.rowDel;
            }
        },
        formatOpt:function(val, row){
            var btns = [];
            if(row.status == 0){
                btns.push('<a href="javascript:;" onclick="dbSlowQueryModule.fix(' + row.id +')">解决</a>');
            }
            return btns.join(' | ');
        },
        fix:function(id){
            var that = this;
			$.messager.confirm('提示', '确定解决了吗?', function(result){
                if(!result) return false;
				$.messager.progress({text:'处理中，请稍候...'});
				$.post('<?=url('index/System/fixDbSlowQuery')?>', {id:id}, function(res){
					$.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
				}, 'json');
			});
        },
        clear:function(){
            var that = this;
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/System/clearDbSlowQuery')?>', function(res){
                $.messager.progress('close');
                if (res.code) {
                    $.app.method.tip('提示', res.msg, 'info');
                    that.reset();
                } else {
                    $.app.method.alertError(null, res.msg);
                }
            }, 'json');
        }
    };
</script>