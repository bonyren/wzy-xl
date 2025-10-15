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
        <?=JVAR?>.view(row.id, row.nickname);
    },
    onLoadSuccess:<?=JVAR?>.onLoadSuccess,
    onBeforeLoad:<?=JVAR?>.onBeforeLoad,
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:60,fixed:true,formatter:<?=JVAR?>.operate,align:'left',hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'headimg_url',width:80,align:'center',formatter:<?=JVAR?>.formatImage">头像</th>
        <th data-options="field:'nickname',width:120,align:'center'">昵称</th>
        <th data-options="field:'real_name',width:100,align:'center'">姓名</th>
        <th data-options="field:'cellphone',width:100,align:'center'">手机号码</th>
        <th data-options="field:'total_test_quantity',width:100,align:'center',sortable:true">测评次数</th>
        <th data-options="field:'total_test_amount',width:100,align:'center',sortable:true">测评金额</th>
        <th data-options="field:'total_appoint_quantity',width:100,align:'center',sortable:true">预约次数</th>
        <th data-options="field:'register_time',width:100,align:'center',sortable:true">注册时间</th>
        <th data-options="field:'latest_login_time',width:100,align:'center',sortable:true">最新登录</th>
        <th data-options="field:'channel_id',width:100,align:'center',formatter:<?=JVAR?>.formatChannel">来源</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[nickname]" class="easyui-textbox"
                        data-options="validType:['length[0,40]'],width:100" prompt="用户昵称"/>
        </div>
        <div class="datagrid-search-form-box">
            <input name="search[real_name]" class="easyui-textbox"
                        data-options="validType:['length[0,16]'],width:100" prompt="用户姓名"/>
        </div>
        <div class="datagrid-search-form-box">
            <input name="search[cellphone]" class="easyui-textbox"
                        data-options="validType:['length[0,16]'],width:100" prompt="手机号码"/>
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[channel_id]" class="easyui-combobox" prompt="渠道" editable="false" data-options="panelHeight:'auto',value:''" style="width:120px;">
                <?php foreach (Defs::CHANNELS as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
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
        onLoadSuccess:function(data){
            var that = <?=JVAR?>;
            var total = $(that.datagrid).datagrid('getRows').length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-customer-operate-row-' + i).splitbutton();
            }
            $(that.datagrid).datagrid('fixRowHeight');
        },
        onBeforeLoad:function(){
            var that = <?=JVAR?>;
            var rows = $(that.datagrid).datagrid('getRows');
            if(!rows){
                return;
            }
            var total = rows.length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-customer-operate-row-' + i).remove();
                $('#<?=UNIQID?>-customer-operate-row-menu-' + i).remove();
            }
        },
        operate:function(val, row, index){
            /*
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="<?=JVAR?>.view(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) + '\')" title="用户详情">用户详情</a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-success size-MINI radius" onclick="<?=JVAR?>.save(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) + '\')" title="用户编辑">用户编辑</a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-secondary size-MINI radius my-1" onclick="<?=JVAR?>.evaluationDetail(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) + '\')" title="测评订单">测评订单</a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-info size-MINI radius my-1" onclick="<?=JVAR?>.appointmentDetail(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) + '\')" title="预约订单">预约订单</a>');
            return btns.join(' ');*/
            var html = '<a href="javascript:void(0)" id="<?=UNIQID?>-customer-operate-row-' + index + '"' +
                        'data-options="menu:\'#<?=UNIQID?>-customer-operate-row-menu-'  + index + '\',iconCls:\'fa fa-ellipsis-v\'" ></a>' +
                    '<div id="<?=UNIQID?>-customer-operate-row-menu-' + index + '" style="width:60px;">';
            html += '<div onclick="<?=JVAR?>.view('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) +'\')">用户详情</div>';
            html += '<div onclick="<?=JVAR?>.save('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) +'\')">用户编辑</div>';
            html += '<div onclick="<?=JVAR?>.evaluationDetail('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) +'\')">测评订单</div>';
            html += '<div onclick="<?=JVAR?>.appointmentDetail('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.nickname) +'\')">预约订单</div>';
            html += '</div>';
            return html;
        },
        formatImage:function(val, row, index){
            return '<img class="img-thumbnail my-1" src="' + val + '" style="height:60px;">';
        },
        formatChannel:function(val){
            return <?=json_encode(\app\Defs::CHANNELS_HTML, JSON_UNESCAPED_SLASHES)?>[val];
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
        },
        view:function(customerId, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Customer/view')?>';
            href = GLOBAL.func.addUrlParam(href, 'customerId', customerId);
            $(that.dialog).dialog({
                title: name + ' - 客户详情',
                iconCls: 'fa fa-eye',
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
                maximizable:false,
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
        save:function(customerId, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Customer/save')?>';
            href = GLOBAL.func.addUrlParam(href, 'customerId', customerId);
            $(that.dialog).dialog({
                title: name + ' - 客户编辑',
                iconCls: 'fa fa-pencil-square',
                width: <?=$loginMobile?"'100%'":450?>,
                height: <?=$loginMobile?"'100%'":"'80%'"?>,
                cache: false,
                href: href,
                modal: true,
                maximizable:false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'保存',
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
        evaluationDetail:function(customerId, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/SubjectOrder/orders')?>';
            href = GLOBAL.func.addUrlParam(href, 'customer_id', customerId);
            $(that.dialog).dialog({
                title: name + ' - 测评订单',
                iconCls: 'fa fa-eye',
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
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
        appointmentDetail:function(customerId, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/AppointOrder/index')?>';
            href = GLOBAL.func.addUrlParam(href, 'customerId', customerId);
            $(that.dialog).dialog({
                title: name + ' - 预约订单',
                iconCls: 'fa fa-eye',
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
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