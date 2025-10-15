<table id="dbBackupsDatagrid" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('System/dbBackups')?>',
    method:'post',
    pagination:false,
    fit:true,
    fitColumns:false,
    title:'',
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:60,align:'center',formatter:dbBackupsModule.operate">操作</th>
        <th data-options="field:'date',width:150,align:'center'">备份日期</th>
        <th data-options="field:'name',width:200,align:'center'">文件名称</th>
    </tr>
    </thead>
</table>
<script>
    var dbBackupsModule = {
        dialog:'#globel-dialog-div',
        datagrid:'#dbBackupsDatagrid',
        operate:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-default size-MINI radius" onclick="dbBackupsModule.delete(\'' + row.name + '\')" title="删除"><i class="fa fa-trash-o fa-lg"></i></a>');
            return btns.join(' ');
        },
        delete:function(name){

        }
    };
</script>