<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('index/Schedulers/logs',['scheduler_id'=>$scheduler_id])?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    fit:true,
    fitColumns:false,
    onDblClickRow:<?=JVAR?>.detail,
    onLoadSuccess:<?=JVAR?>.convert,
    border:false">
    <thead>
    <tr>
        <th data-options="field:'execute_time',width:150,align:'center'">执行时间</th>
        <th data-options="field:'execute_end_time',width:150,align:'center'">结束时间</th>
        <th data-options="field:'status',width:80,align:'center'">状态</th>
        <th data-options="field:'result',width:100,align:'center'">结果</th>
        <th data-options="field:'message',width:200,align:'center'">消息</th>
    </tr>
    </thead>
</table>
<script>
var <?=JVAR?> = {
    datagrid:'#<?=DATAGRID_ID?>',
    status:<?=json_encode(\app\cli\model\JobQueue::JOB_STATUS,JSON_UNESCAPED_UNICODE)?>,
    convert:function(data){
        var that = <?=JVAR?>;
        $.each(data.rows, function(i,v){
            $(that.datagrid).datagrid('updateRow',{
                index: i,
                row: {
                    execute_time:v.execute_time ? v.execute_time.substr(0,16) : '',
                    execute_end_time:v.execute_end_time ? v.execute_end_time.substr(0,16) : '',
                    status:'<span class="badge badge-'+that.status[v.status]['cls']+'">'+that.status[v.status]['label']+'</span>',
                }
            });
        });
    },
    reload:function(){
        $(this.datagrid).datagrid('reload');
    }
};
</script>