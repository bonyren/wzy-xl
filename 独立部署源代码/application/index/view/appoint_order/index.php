<?php
use app\Defs;
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
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
        <?=JVAR?>.view(row.order_no, row.order_no);
    },
    onLoadSuccess:<?=JVAR?>.onLoadSuccess,
    onBeforeLoad:<?=JVAR?>.onBeforeLoad,
    queryParams:{}
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:60,fixed:true,formatter:<?=JVAR?>.operate,align:'center',hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'order_no',width:120,align:'center'">订单号</th>
        <th data-options="field:'nickname',width:80,align:'center'">用户昵称</th>
        <th data-options="field:'linkman',width:120,align:'center',formatter:<?=JVAR?>.formatCustomer">客户姓名</th>
        <th data-options="field:'cellphone',width:120,align:'center'">客户电话</th>
        <th data-options="field:'expert',width:80,align:'center',formatter:<?=JVAR?>.formatExpert">预约专家</th>
        <th data-options="field:'order_time',width:120,align:'center',sortable:true">订单时间</th>
        <th data-options="field:'order_amount',width:80,align:'center'">金额(元)</th>
        <th data-options="field:'appoint_time_full',width:150,align:'center',sortable:true">预约时间</th>
        <!--
        <th data-options="field:'appoint_mode',width:100,align:'center',formatter:<?=JVAR?>.formatMode">方式</th>
        -->
        <th data-options="field:'status',width:80,align:'center',formatter:<?=JVAR?>.formatStatus">订单状态</th>
        <th data-options="field:'pay_status',width:80,align:'center',formatter:<?=JVAR?>.formatPayStatus">支付状态</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-toolbar-search-form">
        <div class="datagrid-toolbar-search-form-fields border-right mr-1">
            <input name="search[order_no]" class="easyui-textbox" prompt="订单号" data-options="validType:['length[0,32]'],width:120" />
            <input name="search[real_name]" class="easyui-textbox" prompt="专家姓名" data-options="validType:['length[0,16]'],width:100" />
            <input name="search[nickname]" class="easyui-textbox" prompt="用户昵称" data-options="validType:['length[0,40]'],width:100" />
            <input name="search[linkman]" class="easyui-textbox" prompt="预约姓名" data-options="validType:['length[0,32]'],width:100" />
            <input name="search[cellphone]" class="easyui-textbox" prompt="用户电话" data-options="validType:['length[0,32]'],width:100" />
            <select name="search[status]" class="easyui-combobox" prompt="订单状态" editable="false" data-options="panelHeight:'auto',value:''" style="width:120px;">
                <?php foreach (IndexDefs::$orderStatusDefs as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
            <select name="search[pay_status]" class="easyui-combobox" prompt="支付状态" editable="false" data-options="panelHeight:'auto',value:''" style="width:120px;">
                <?php foreach (Defs::PAYS as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
            <span class="ml-1"></span>
        </div>
        <div class="datagrid-toolbar-search-form-btns">
			<a class="easyui-linkbutton search-submit" data-options="iconCls:'fa fa-search',
						onClick:function(){ <?=JVAR?>.search(); }">搜索
			</a>
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
        init:function(){
            if($(<?=JVAR?>.dialog).closest('.window').is(':visible')){
                <?=JVAR?>.dialog = '#globel-dialog2-div';
            }
            $(<?=JVAR?>.datagrid).datagrid('fixRowHeight');
        },
        operate:function(val, row, index){
            var html = '<a href="javascript:void(0)" id="<?=UNIQID?>-appoint-order-operate-row-' + index + '"' +
                        'data-options="menu:\'#<?=UNIQID?>-appoint-order-operate-row-menu-'  + index + '\',iconCls:\'fa fa-ellipsis-v\'" ></a>' +
                    '<div id="<?=UNIQID?>-appoint-order-operate-row-menu-' + index + '" style="width:60px;">';
            /***只读操作******************************************************************/
            html += '<div data-options="iconCls:\'fa fa-eye\'" onclick="<?=JVAR?>.view(\''+row.order_no+'\',\'' + GLOBAL.func.escapeALinkStringParam(row.order_no) +'\')">查看</div>';
            /***读写操作******************************************************************/
            <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
                html += '<div class="menu-sep"></div>';
                if(row.status != <?=IndexDefs::ORDER_FINISH_STATUS?>){
                    html += '<div data-options="iconCls:\'fa fa-check\'" onclick="<?=JVAR?>.finish(\''+row.order_no+'\',\'' + GLOBAL.func.escapeALinkStringParam(row.order_no) +'\')">完成预约</div>';
                }
                if(row.status != <?=IndexDefs::ORDER_CANCELLED_STATUS?>){
                    html += '<div data-options="iconCls:\'fa fa-close\'" onclick="<?=JVAR?>.cancel(\''+row.order_no+'\',\'' + GLOBAL.func.escapeALinkStringParam(row.order_no) +'\')">取消预约</div>';
                }
                /*
                if(row.pay_status == <?=Defs::PAY_SUCCESS?> && row.order_amount > 0){
                    html += '<div data-options="iconCls:\'fa fa-mail-reply-all\'" onclick="<?=JVAR?>.refund(\''+row.order_no+'\',\'' + GLOBAL.func.escapeALinkStringParam(row.order_no) +'\')">微信支付退款</div>';
                }
                */
            <?php } ?>
            html += '</div>';
            return html;
        },
        formatCustomer:function(val, row){
            //return row.linkman + "/" + row.cellphone + "";
            return row.linkman;
        },
        formatExpert:function(val, row){
            return row.expert_real_name;
        },
        formatMode:function(val, row){
            return <?=json_encode(IndexDefs::$appointModeHtmlDefs, JSON_UNESCAPED_UNICODE)?>[val];
        },
        formatStatus:function(val, row, index){
            return <?=json_encode(IndexDefs::$orderStatusHtmlDefs, JSON_UNESCAPED_UNICODE)?>[val];
        },
        formatPayStatus:function(val, row){
            return <?=json_encode(Defs::PAYS_HTML, JSON_UNESCAPED_SLASHES)?>[val];
        },
        onLoadSuccess:function(data){
            var that = <?=JVAR?>;
            $.each(data.rows, function(i, row){
            });
            var total = $(that.datagrid).datagrid('getRows').length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-appoint-order-operate-row-' + i).splitbutton();
            }
            <?=JVAR?>.init();
        },
        onBeforeLoad:function(){
            var that = <?=JVAR?>;
            var rows = $(that.datagrid).datagrid('getRows');
            if(!rows){
                return;
            }
            var total = rows.length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-appoint-order-operate-row-' + i).remove();
                $('#<?=UNIQID?>-appoint-order-operate-row-menu-' + i).remove();
            }
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
            var queryParams = $(that.datagrid).datagrid('options').queryParams;
            $.each($(that.searchForm).serializeArray(), function() {
                delete queryParams[this['name']];
            });
            $(this.datagrid).datagrid('load');
        },
        finish:function(orderNo, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/AppointOrder/finish')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $.messager.confirm('提示', '确认完成来自['+name+']订单吗?', function(result){
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

        },
        cancel:function(orderNo, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/AppointOrder/cancel')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $.messager.confirm('提示', '确认取消来自['+name+']订单吗?', function(result){
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
        },
        refund:function(orderNo, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/AppointOrder/refund')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $.messager.confirm('提示', '确认退款来自['+name+']订单吗?', function(result){
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
        },
        view:function(orderNo, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/AppointOrder/view')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $(that.dialog).dialog({
                title: name + ' - 订单查看',
                iconCls: 'fa fa-eye',
                width: <?=$loginMobile?"'100%'":"'70%'"?>,
                height: '100%',
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