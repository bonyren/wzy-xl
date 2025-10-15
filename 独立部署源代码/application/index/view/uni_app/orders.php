<?php
use app\Defs;
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['orders']?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    border:false,
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    title:'',
    onLoadSuccess:function(data){
        $.each(data.rows, function(i, row){
        });
    }
    ">
    <thead>
    <tr>
        <th data-options="field:'order_no',width:120,align:'center'">订单编号</th>
        <th data-options="field:'openid',width:120,align:'center'">用户openid</th>
        <th data-options="field:'subject_name',width:200,align:'center'">量表</th>
        <th data-options="field:'order_amount',width:100,align:'center'">金额</th>
        <th data-options="field:'order_time',width:100,align:'center'">下单时间</th>
        <th data-options="field:'finish_time',width:100,align:'center'">完成时间</th>
        <th data-options="field:'pay_status',width:100,align:'center',formatter:<?=JVAR?>.formatPayStatus">支付状态</th>
        <th data-options="field:'channel',width:100,align:'center'">来源</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[order_no]" class="easyui-textbox"
                        data-options="validType:['length[0,40]'],width:200" prompt="订单编号"/>
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
        formatPayStatus:function(val, row){
            return <?=json_encode(Defs::PAYS_HTML, JSON_UNESCAPED_SLASHES)?>[val];
        },
        reload:function(){
            var that = <?=JVAR?>;
            $(that.datagrid).datagrid('reload');
        },
        load:function(){
            var that = <?=JVAR?>;
            $(that.datagrid).datagrid('load');
        },
        search:function(){
            var that = <?=JVAR?>;
            var isValid = $(that.searchForm).form('validate');
            if(!isValid){
                return;
            }
            var queryParams = $(that.datagrid).datagrid('options').queryParams;
            //reset the query parameter
            $.each($(that.searchForm).serializeArray(), function() {
                queryParams[this['name']] = this['value'];
            });
            that.load();
        },
        reset:function(){
            var that = <?=JVAR?>;
            $(that.searchForm).form('reset');
            $(this.datagrid).datagrid('load', {});
        }
    };
</script>