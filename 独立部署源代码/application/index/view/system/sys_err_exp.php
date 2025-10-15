<table id="sysErrExpDatagrid" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    selectOnCheck:false,
    checkOnSelect:false,
    url:'<?=$urlHrefs['index']?>',
    method:'post',
    toolbar:'#sysErrExpToolbar',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    border:false,
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    title:''">
    <thead>
    <tr>
        <th data-options="field:'severity',width:80,align:'left'">错误级别</th>
        <th data-options="field:'message',width:300,align:'left'">内容</th>
        <th data-options="field:'file',width:150,align:'left'">文件</th>
        <th data-options="field:'line',width:50,align:'left'">行数</th>
        <th data-options="field:'time',width:80,align:'left'">时间</th>
    </tr>
    </thead>
</table>
<div id="sysErrExpToolbar" class="p-1">
    <a class="easyui-linkbutton" data-options="iconCls:'fa fa-trash',
                    onClick:function(){ sysErrExpModule.clear(); }">
                    清空
    </a>
</div>
<script type="text/javascript">
    var sysErrExpModule = {
        dialog:'#globel-dialog-div',
        datagrid:'#sysErrExpDatagrid',
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        reset:function(){
            var that = this;
            $(that.datagrid).datagrid('load', {});
        },
        clear:function(){
			$.messager.confirm('提示', '确定清空吗?', function(result){
                if(!result) return false;
				$.messager.progress({text:'处理中，请稍候...'});
				$.post('<?=url('index/System/clearSysErrExp')?>', function(res){
						$.messager.progress('close');
						if (res.code) {
							$.app.method.tip('提示', res.msg, 'info');
							sysErrExpModule.reset();
						} else {
							$.app.method.alertError(null, res.msg);
						}
					}, 'json'
				);
			});
        }
    };

</script>