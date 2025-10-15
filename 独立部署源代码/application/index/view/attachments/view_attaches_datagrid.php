<table id="attachesDatagrid_<?=$uniqid?>" class="easyui-datagrid" data-options="
    striped:true,
    nowrap:false,
    fit:<?=$fit?'true':'false'?>,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['attaches']?>',
    method:'post',
    pagination:false,
    border:false,
    fitColumns:false">
    <thead>
    <tr>
        <th data-options="field:'original_name',width:200,formatter:attachModule_<?=$uniqid?>.formatName">文件名</th>
        <!--
        <th data-options="field:'size',align:'center',width:100,formatter:GLOBAL.func.byteFormat">大小(Bytes)</th>
        -->
        <th data-options="field:'entered',width:100,formatter:GLOBAL.func.dateTimeFilter">时间</th>
        <th data-options="field:'download_url',align:'center',width:60,formatter:attachModule_<?=$uniqid?>.formatDownload">下载</th>
    </tr>
    </thead>
</table>
<script type="text/javascript">
    var attachModule_<?=$uniqid?> = {
        dialog:'#globel-dialog-div',
        dialog2:'#globel-dialog2-div',
        datagrid:'#attachesDatagrid_<?=$uniqid?>',
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        load:function(){
            $(this.datagrid).datagrid('load');
        },
        formatName:function(val, row, index){
            if(row.attachment_id == 0){
                return '';
            }
            return '<a title="' + val + '" href="javascript:void(0)" onclick="QT.filePreview('+row.attachment_id+')">' + val + '</a>';
        },
        formatDownload:function(val, row, index){
            if(row.attachment_id == 0){
                return '';
            }
            return '<a class="text-secondary size-MINI fa fa-download" href="' + row.download_url + '" target="_blank">&nbsp;</a>';
        }
    }
</script>