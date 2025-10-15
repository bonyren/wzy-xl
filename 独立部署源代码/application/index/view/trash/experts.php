<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('index/Trash/experts')?>',
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
        <?=JVAR?>.view(row.id, row.real_name);
    },
    onLoadSuccess:function(data){
        $.each(data.rows, function(i, row){
        });
    }
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:180,fixed:true,formatter:<?=JVAR?>.operate,align:'left',hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'workimg_url',width:120,formatter:<?=JVAR?>.formatAvatarImage,align:'center'">头像</th>
        <th data-options="field:'real_name',width:100,align:'center'">姓名</th>
        <th data-options="field:'cellphone',width:100,align:'center'">手机号码</th>
        <th data-options="field:'first_job_time',width:100,align:'center',formatter:GLOBAL.func.dateFilter">参加工作日期</th>
        <th data-options="field:'appoint_fee',width:120,align:'center'">咨询价格(45分钟)</th>
        <th data-options="field:'total_appoint_quantity',width:100,align:'center',sortable:true">咨询次数</th>
        <th data-options="field:'view_appoints',width:100,align:'center',formatter:<?=JVAR?>.formatViewAppoints">查看订单</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=TOOLBAR_ID?>Form" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            姓名 <input id="<?=TOOLBAR_ID?>FormSearchbox" name="search[real_name]" class="easyui-textbox"
                            data-options="width:120" prompt="请输入姓名"/>
        </div>
        <div class="datagrid-search-form-box">
            手机号码 <input id="<?=TOOLBAR_ID?>FormSearchbox" name="search[cellphone]" class="easyui-textbox"
                        data-options="width:120" prompt="请输入手机号码"/>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton" data-options="iconCls:'fa fa-search',
                        onClick:function(){ <?=JVAR?>.search(); }">搜索
            </a>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton" data-options="iconCls:'fa fa-reply',
                        onClick:function(){ <?=JVAR?>.reset(); }">重置
            </a>
        </div>
    </form>
</div>
<script>
    var <?=JVAR?> = {
        dialog:'#globel-dialog-div',
        datagrid:'#<?=DATAGRID_ID?>',
        searchForm:'#<?=TOOLBAR_ID?>Form',
        operate:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="<?=JVAR?>.view(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) + '\')" title="查看"><i class="fa fa-eye fa-lg"></i>查看</a>');
            <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
                btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="<?=JVAR?>.restore('+row.id+',\''+GLOBAL.func.escapeALinkStringParam(row.real_name)+'\')" title="恢复"><i class="fa fa-mail-reply-all fa-lg">恢复</i></a>');
            <?php } ?>
            return btns.join(' ');
        },
        formatAvatarImage:function(val, row, index){
            return val?'<img class="img-thumbnail my-1" src="' + val + '" style="height:60px;">':'';
        },
        formatViewAppoints:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-success size-MINI radius" onclick="<?=JVAR?>.appointment(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) + '\')" title="预约订单">预约订单</a>');
            return btns.join(' ');
        },
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        load:function(){
            $(this.datagrid).datagrid('load');
        },
        search:function(){
            var that = this;
            var queryParams = $(that.datagrid).datagrid('options').queryParams;
            //reset the query parameter
            $.each($(that.searchForm).serializeArray(), function() {
                queryParams[this['name']] = this['value'];
            });
            that.load();
        },
        reset:function(){
            var that = this;
            $(that.searchForm).form('reset');
            $(this.datagrid).datagrid('load', {});
        },
        restore:function(expertId, title){
            var that = this;
            var href = '<?=url('index/Expert/restore')?>';
            href = GLOBAL.func.addUrlParam(href, 'expertId', expertId);
            $.messager.confirm('提示', '确认恢复'+title+'吗?', function(result){
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
        view:function(expertId, name){
            var that = this;
            var href = '<?=url('index/Expert/view')?>';
            href = GLOBAL.func.addUrlParam(href, 'expertId', expertId);
            $(that.dialog).dialog({
                title: name + ' - 专家查看',
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
        },
        appointment:function(expertId, name){
            var that = this;
            var href = '<?=url('index/Expert/appointment')?>';
            href = GLOBAL.func.addUrlParam(href, 'expertId', expertId);
            $(that.dialog).dialog({
                title: name + ' - 预约查看',
                iconCls: 'fa fa-clock-o',
                width: <?=$loginMobile?"'100%'":"'90%'"?>,
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