<?php
use app\index\service\EventLogs as EventLogsService;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="
    striped:true,
    rownumbers:false,
    nowrap:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$current_request_url?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    onLoadSuccess:function(data){
    },
    border:false">
    <thead>
    <tr>
        <th data-options="field:'entered',width:140">时间</th>
        <th data-options="field:'severity',width:80,formatter:<?=JVAR?>.formatSeverity">等级</th>
        <th data-options="field:'entry',width:300">事件</th>
        <th data-options="field:'realname',width:80">操作人</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form>
        等级:
        <select class="easyui-combobox" name="search[severity]" data-options="editable:false,width:150,value:'',prompt:'请选择'">
        <?php foreach(EventLogsService::$eSeverityDefs as $key=>$label){ ?>
            <option value="<?=$key?>"><?=$label?></option>
        <?php } ?>
        </select>
        <a class="easyui-linkbutton" data-options="iconCls:'fa fa-search',
                        onClick:function(){ <?=JVAR?>.search(this); }">搜索
        </a>
        <a class="easyui-linkbutton" data-options="iconCls:'fa fa-reply',
                        onClick:function(){ <?=JVAR?>.reset(this); }">重置
        </a>
    </form>
</div>
<script type="text/javascript">
var <?=JVAR?> = {
    datagrid:'#<?=DATAGRID_ID?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    formatSeverity:function(val, row, index){
        return <?=json_encode(EventLogsService::$eSeverityHtmlDefs)?>[val];
    },
    reload:function(){
        $(this.datagrid).datagrid('reload');
    },
    search:function(that){
        var searchForm = $(that).closest('form');
        var paramObj = {};
        $.each(searchForm.serializeArray(), function (){
            paramObj[this.name] = this.value;
        });
        $(this.datagrid).datagrid('load', paramObj);
    },
    reset:function(that){
        var searchForm = $(that).closest('form');
        searchForm.form('reset');
        $(this.datagrid).datagrid('load', {});
    }
};
</script>