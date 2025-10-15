<?php
use app\index\service\OperationLogs as OperationLogsService;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    selectOnCheck:false,
    checkOnSelect:false,
    url:'<?=$urlHrefs['index']?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    border:false,
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    title:'',
    onDblClickRow:function(index, row){
        <?=JVAR?>.view(row.id);
    }">
    <thead>
    <tr>
        <th data-options="field:'category',width:80,align:'center',formatter:<?=JVAR?>.formatCategory">类别</th>
        <th data-options="field:'type',width:80,align:'center',formatter:<?=JVAR?>.formatType">类型</th>
        <th data-options="field:'entered',width:150,fixed:true,align:'center'">时间</th>
        <th data-options="field:'title',width:150,align:'left'">标题</th>
        <th data-options="field:'content',width:300,align:'left'">内容</th>
        <th data-options="field:'changed_by',width:100,align:'center'">操作人</th>
        <th data-options="field:'device',width:60,align:'center',formatter:<?=JVAR?>.formatDevice">设备</th>
        <th data-options="field:'ip',width:100,align:'center'">Ip</th>
        <th data-options="field:'channel',width:100,align:'center',formatter:<?=JVAR?>.formatChannel">渠道</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <select name="search[category]" class="easyui-combobox" prompt="类别" style="width:100px;" data-options="editable:false,panelHeight:'auto',value:''">
                <?php foreach (OperationLogsService::CATEGORY_DEFS as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[type]" class="easyui-combobox" prompt="类型" style="width:100px;" data-options="editable:false,panelHeight:'auto',value:''">
                <?php foreach (OperationLogsService::OPT_DEFS as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <input name="search[keyword]" class="easyui-textbox" data-options="width:200" prompt="关键词" />
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
        formatCategory:function(val, row){
            var obj = <?=json_encode(OperationLogsService::CATEGORY_DEFS)?>;
            return obj[val];
        },
        formatType:function(val, row){
            var obj = <?=json_encode(OperationLogsService::OPT_DEFS)?>;
            return obj[val];
        },
        formatDevice:function(val, row){
            var obj = <?=json_encode(\app\Defs::DEVICE_DEFS)?>;
            return obj[val];
        },
        formatChannel:function(val, row){
            var obj = <?=json_encode(OperationLogsService::CHANNEL_DEFS)?>;
            return obj[val];
        },
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        reset:function(){
            var that = <?=JVAR?>;
            $(that.searchForm).form('reset');
            $(that.datagrid).datagrid('load', {});
        },
        search:function(){
            var that = <?=JVAR?>;
            var paramObj = {};
            //reset the query parameter
            $.each($(that.searchForm).serializeArray(), function() {
                paramObj[this['name']] = this['value'];
            });
            $(that.datagrid).datagrid('load', paramObj);
        },
        view:function(id){
            var that = <?=JVAR?>;
            var href = '<?=url('index/OperationLogs/view')?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $(that.dialog).dialog({
                title: '日志查看',
                iconCls: 'fa fa-eye',
                width: <?=$loginMobile?"'100%'":"'60%'"?>,
                height: '80%',
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