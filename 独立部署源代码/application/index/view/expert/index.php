<?php
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
        <?=JVAR?>.view(row.id, row.real_name);
    },
    onLoadSuccess:<?=JVAR?>.onLoadSuccess,
    onBeforeLoad:<?=JVAR?>.onBeforeLoad,
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:60,fixed:true,formatter:<?=JVAR?>.operate,align:'center',hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'workimg_url',width:120,formatter:<?=JVAR?>.formatAvatarImage,align:'center'">头像</th>
        <th data-options="field:'real_name',width:100,align:'center'">姓名</th>
        <th data-options="field:'cellphone',width:100,align:'center'">手机号码</th>
        <th data-options="field:'first_job_time',width:100,align:'center',formatter:GLOBAL.func.dateFilter">从业时间</th>
        <th data-options="field:'appoint_fee',width:120,align:'center'">价格(元/45分钟)</th>
        <th data-options="field:'field_items',width:200,align:'center'">擅长领域</th>
        <th data-options="field:'total_appoint_quantity',width:100,align:'center',sortable:true">咨询次数</th>
        <th data-options="field:'qrcode',width:100,align:'center',formatter:<?=JVAR?>.formatQrcodeImage">推送二维码</th>
        <th data-options="field:'view_appoints',width:100,align:'center',formatter:<?=JVAR?>.formatViewAppoints">订单</th>
        <th data-options="field:'status',width:80,align:'center',sortable:true,formatter:datagridFormatter.formatEntityStatus">状态</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[real_name]" class="easyui-textbox"
                        data-options="validType:['length[0,16]'],width:120" prompt="姓名"/>
        </div>
        <div class="datagrid-search-form-box">
            <input name="search[cellphone]" class="easyui-textbox"
                        data-options="validType:['length[0,16]'],width:120" prompt="手机号码"/>
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[status]" class="easyui-combobox" prompt="状态" style="width:100px;" data-options="editable:false,panelHeight:'auto',value:''">
                <?php foreach (IndexDefs::$entityStatusDefs as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton search-submit" data-options="iconCls:'fa fa-search',
                        onClick:function(){ <?=JVAR?>.search(); }">搜索
            </a>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton search-reset" data-options="iconCls:'fa fa-rotate-left',
                        onClick:function(){ <?=JVAR?>.reset(); }">重置
            </a>
        </div>
    </form>
    <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
    <div class="line my-1"></div>
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ <?=JVAR?>.add(); },iconCls:iconClsDefs.add">新增专家</a>
    </div>
    <?php } ?>
</div>
<script>
    var <?=JVAR?> = {
        dialog:'#globel-dialog-div',
        datagrid:'#<?=DATAGRID_ID?>',
        searchForm:'#<?=FORM_ID?>',
        operate:function(val, row, index){
            var html = '<a href="javascript:void(0)" id="<?=UNIQID?>-expert-operate-row-' + index + '"' +
                        'data-options="menu:\'#<?=UNIQID?>-expert-operate-row-menu-'  + index + '\',iconCls:\'fa fa-ellipsis-v\'" ></a>' +
                    '<div id="<?=UNIQID?>-expert-operate-row-menu-' + index + '" style="width:60px;">';
            /***只读操作******************************************************************/             
            html += '<div data-options="iconCls:\'fa fa-eye\'" onclick="<?=JVAR?>.view('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) +'\')">查看</div>';
            html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcodeInfo('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) + '\')">二维码</div>';

            html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcode('+row.id+',\'' + row.qrcode +'\')">推送二维码</div>';
            /***读写操作******************************************************************/
            <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
                html += '<div class="menu-sep"></div>';
                html += '<div data-options="iconCls:\'fa fa-pencil-square-o\'" onclick="<?=JVAR?>.edit('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) +'\')">编辑</div>'; 
                html += '<div data-options="iconCls:\'fa fa-clock-o\'" onclick="<?=JVAR?>.schedule('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) +'\')">预约时间设置</div>';
                html += '<div data-options="iconCls:\'fa fa-trash-o\'" onclick="<?=JVAR?>.delete('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) +'\')">删除</div>';
                html += '<div data-options="iconCls:\'fa fa-trash\'" onclick="<?=JVAR?>.deleteForce('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) +'\')">硬删除</div>';
            <?php } ?>
            html += '</div>';
            return html;
        },
        formatAvatarImage:function(val, row, index){
            return val?'<img class="img-thumbnail my-1" src="' + val + '" style="height:60px;">':'';
        },
        formatQrcodeImage:function(val, row, index){
            return val?'<img class="img-thumbnail" src="' + val + '" style="height:60px;">':'';
        },
        formatViewAppoints:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-success size-MINI radius" onclick="<?=JVAR?>.appointment(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.real_name) + '\')" title="预约订单">预约订单</a>');
            return btns.join(' ');
        },
        onLoadSuccess:function(data){
            /*
            $.each(data.rows, function(i, row){
            });*/
            var that = <?=JVAR?>;
            $.parser.parse('.to-be-parse');
            $(".to-be-rating").rating({min:0, max:10, step:1, size:'xs', showCaption:false, animate:false, displayOnly:true});
            /**********************************/
            var total = $(that.datagrid).datagrid('getRows').length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-expert-operate-row-' + i).splitbutton();
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
                $('#<?=UNIQID?>-expert-operate-row-' + i).remove();
                $('#<?=UNIQID?>-expert-operate-row-menu-' + i).remove();
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
            $(this.datagrid).datagrid('load', {});
        },
        add:function(){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Expert/save')?>';
            $(that.dialog).dialog({
                title: '新增专家',
                iconCls: 'fa fa-plus-circle',
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
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
        edit:function(expertId, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Expert/save')?>';
            href = GLOBAL.func.addUrlParam(href, 'expertId', expertId);
            $(that.dialog).dialog({
                title: name + ' - 专家编辑',
                iconCls: 'fa fa-pencil-square',
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
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
        delete:function(expertId, title){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Expert/delete')?>';
            href = GLOBAL.func.addUrlParam(href, 'expertId', expertId);
            $.messager.confirm('提示', '确认"'+title+'"删除吗?', function(result){
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
        deleteForce:function(expertId, title){
            var that = <?=JVAR?>;
            $.messager.confirm('提示','确定硬删除"'+title+'"吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Expert/deleteForce')?>',{expertId:expertId},function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        },
        view:function(expertId, name){
            var that = <?=JVAR?>;
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
            var that = <?=JVAR?>;
            var href = '<?=url('index/Expert/appointment')?>';
            href = GLOBAL.func.addUrlParam(href, 'expertId', expertId);
            $(that.dialog).dialog({
                title: name + ' - 预约查看',
                iconCls: 'fa fa-clock-o',
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
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
        schedule:function(expertId, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Expert/manageSchedule')?>';
            href = GLOBAL.func.addUrlParam(href, 'expertId', expertId);
            $(that.dialog).dialog({
                title: name + ' - 预约时间管理',
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
        },
        qrcode:function(id, qrcode){
            var that = <?=JVAR?>;
            if (qrcode) {
                QT.helper.view({
                    title:'二维码',
                    width:<?=$loginMobile?"'100%'":600?>,
                    height:'70%',
                    url:'<?=url('index/Expert/qrcode')?>?id='+id
                });
                return;
            }
            $.messager.confirm('提示','确定生成二维码吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Expert/qrcode')?>',{id: id},function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.qrcode(id,1);
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        },
        qrcodeInfo:function(id, title){
            var that = <?=JVAR?>;
            var expertUrl = '<?=url('mp/Expert/detail', '', true, true)?>';
            expertUrl = GLOBAL.func.addUrlParam(expertUrl, 'expertId', id);
            commonModule.qrcodeInfo(expertUrl, title);
        }
    };
</script>