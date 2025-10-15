<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$current_request_url?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    onDblClickRow:function(index, row){
        <?=JVAR?>.view(row.id, row.name);
    },
    onLoadSuccess:<?=JVAR?>.onLoadSuccess,
    onBeforeLoad:<?=JVAR?>.onBeforeLoad,
    border:false">
    <thead>
    <tr>
        <th data-options="field:'btns',width:60,fixed:true,align:'center',formatter:<?=JVAR?>.formatOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <!--
        <th data-options="field:'id',width:50,align:'center'">编号</th>
        -->
        <th data-options="field:'banner',width:80,align:'center',formatter:<?=JVAR?>.formatImage.bind(<?=JVAR?>)">图片</th>
        <th data-options="field:'name',width:100,align:'center'">名称</th>
        <th data-options="field:'subjects_names',width:200,align:'center'">关联量表</th>
        <th data-options="field:'description',width:100,align:'center'">介绍</th>
        <th data-options="field:'status',width:80,align:'center',sortable:true,formatter:datagridFormatter.formatEntityStatus">状态</th>
        <th data-options="field:'qrcode_push',width:80,align:'center'">推送二维码</th>
        <th data-options="field:'ctime',width:100,align:'center'">创建时间</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[name]" class="easyui-textbox" prompt="组合测试名称" data-options="validType:['length[0,64]']" style="width:200px;">
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[status]" class="easyui-combobox" prompt="状态" style="width:100px;" data-options="editable:false,panelHeight:'auto',value:''">
                <?php foreach (IndexDefs::$entityStatusDefs as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton search-submit" iconCls="fa fa-search" onclick="<?=JVAR?>.search()">搜索</a>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton search-reset" iconCls="fa fa-rotate-left" onclick="<?=JVAR?>.reset()">重置</a>
        </div>
    </form>
    <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
    <div class="line my-1"></div>
    <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-plus-circle" onclick="<?=JVAR?>.edit(0)">新增组合量表</a>
    <?php } ?>
</div>
<script>
var <?=JVAR?> = {
    dialog:'#globel-dialog-div',
    datagrid:'#<?=DATAGRID_ID?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    searchForm: '#<?=FORM_ID?>',
    formatOpt:function(val, row, index){
        var html = '<a href="javascript:void(0)" id="<?=UNIQID?>-subject-combination-operate-row-' + index + '"' +
                        'data-options="menu:\'#<?=UNIQID?>-subject-combination-operate-row-menu-'  + index + '\',iconCls:\'fa fa-ellipsis-v\'" ></a>' +
                    '<div id="<?=UNIQID?>-subject-combination-operate-row-menu-' + index + '" style="width:60px;">';
        /***只读操作******************************************************************/
        html += '<div data-options="iconCls:\'fa fa-eye\'" onclick="<?=JVAR?>.view('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">查看</div>';
        html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcodeInfo('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) + '\')">二维码</div>';
        html += '<div data-options="iconCls:\'fa fa-navicon\'" onclick="<?=JVAR?>.orders('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">订单</div>';
        var text = row.qrcode ? '下载推送二维码' : '生成推送二维码';
        html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcode('+row.id+',\'' + row.qrcode +'\')">推送二维码</div>';
        /***读写操作******************************************************************/
        <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
            html += '<div class="menu-sep"></div>';
            html += '<div data-options="iconCls:\'fa fa-pencil-square-o\'" onclick="<?=JVAR?>.edit('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">编辑</div>';
            html += '<div data-options="iconCls:\'fa fa-trash-o\'" onclick="<?=JVAR?>.del('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">删除</div>';
            html += '<div data-options="iconCls:\'fa fa-trash\'" onclick="<?=JVAR?>.delForce('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">硬删除</div>';
        <?php } ?> 
        html += '</div>';
        return html;
    },
    onLoadSuccess:function(data){
        var that = <?=JVAR?>;
        data.rows.forEach(function(v,i){
            $(that.datagrid).datagrid('updateRow',{
                index:i,
                row:{
                    qrcode_push:v.qrcode ? '<img class="img-thumbnail" src="' + v.qrcode + '" style="height:60px;">' : '',
                }
            });
        });
        $.parser.parse('.tobe-parse');
        /**********************************/
        var total = $(that.datagrid).datagrid('getRows').length;
        for(var i=0; i<total; i++){
            $('#<?=UNIQID?>-subject-combination-operate-row-' + i).splitbutton();
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
            $('#<?=UNIQID?>-subject-combination-operate-row-' + i).remove();
            $('#<?=UNIQID?>-subject-combination-operate-row-menu-' + i).remove();
        }
    },
    formatImage:function(val, row){
        return '<img class="img-thumbnail my-1" src="' + row.banner + '" style="height:60px;">';
    },
    reload:function(){
        var that = <?=JVAR?>;
        $(that.datagrid).datagrid('reload');
    },
    search:function(){
        var that = <?=JVAR?>;
        var isValid = $(that.searchForm).form('validate');
        if(!isValid){
            return;
        }
        var data = {};
        $.each($(that.toolbar).children('form').serializeArray(), function() {
            data[this['name']] = this['value'];
        });
        $(that.datagrid).datagrid('load', data);
    },
    reset:function(){
        var that = <?=JVAR?>;
        $(that.toolbar).find('.easyui-textbox').textbox('clear');
        $(that.toolbar).find('.easyui-combobox').combobox('reset');
        $(that.datagrid).datagrid('load', {});
    },
    view:function(id, name){
        var that = <?=JVAR?>;
        var href = '<?=url('index/SubjectCombination/view')?>';
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        $(that.dialog).dialog({
            title: name + ' - 组合量表详情',
            iconCls: 'fa fa-eye',
            width: <?=$loginMobile?"'100%'":600?>,
            height: '60%',
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
    edit:function(id, title=''){
        var that = <?=JVAR?>;
        var href = '<?=url('index/SubjectCombination/save')?>?id='+id;
        $(that.dialog).dialog({
            title: id?('编辑组合量表 - ' + title):'新增组合量表',
            iconCls: id?'fa fa-pencil':'fa fa-plus-circle',
            width: <?=$loginMobile?"'100%'":900?>,
            height: '100%',
            href: href,
            modal: true,
            cache:false,
            maximizable:false,
            onClose: $.noop,
            closable: true,
            buttons:[{
                text:'确定',
                iconCls:iconClsDefs.ok,
                handler: function(){
                    var $form = $(that.dialog).find('form').eq(0);
                    if($form.length == 0){
                        $(that.dialog).dialog('close');
                        return;
                    }
                    var isValid = $form.form('validate');
                    if (!isValid) return;
                    $.messager.progress({text:'处理中，请稍候...'});
                    $.post(href, $form.serialize(), function(res){
                        $.messager.progress('close');
                        if(!res.code){
                            $.app.method.alertError(null, res.msg);
                        }else{
                            $.app.method.tip('提示', res.msg, 'info');
                            $(that.dialog).dialog('close');
                            that.reload();
                        }
                    }, 'json');
                }
            },{
                text:'关闭',
                iconCls:iconClsDefs.cancel,
                handler: function(){
                    $(that.dialog).dialog('close');
                }
            }]
        });
        $(that.dialog).dialog('center');
    },
    qrcode:function(id,qrcode){
        var that = <?=JVAR?>;
        if (qrcode) {
            QT.helper.view({
                title:'二维码',
                width:<?=$loginMobile?"'100%'":600?>,
                height:'70%',
                url:'<?=url('index/SubjectCombination/qrcode')?>?id='+id
            });
            return;
        }
        $.messager.confirm('提示','确定生成二维码吗？',function(y){
            if(!y) { return; }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/SubjectCombination/qrcode')?>',{id:id},function(res){
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
        var subjectUrl = '<?=url('mp/Subject/combination_test', '', true, true)?>';
        subjectUrl = GLOBAL.func.addUrlParam(subjectUrl, 'combination_id', id);
        commonModule.qrcodeInfo(subjectUrl, title);
    },
    del:function(id, title){
        var that = <?=JVAR?>;
        $.messager.confirm('提示','确定删除"'+title+'"吗？',function(y){
            if(!y) { return; }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/SubjectCombination/remove')?>',{id:id},function(res){
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
    delForce:function(id, title){
        var that = <?=JVAR?>;
        $.messager.confirm('提示','确定硬删除"'+title+'"吗？',function(y){
            if(!y) { return; }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/SubjectCombination/removeForce')?>',{id:id},function(res){
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
    orders: function(id, title){
        var that = <?=JVAR?>;
        var iconCls = 'fa fa-navicon';
        var dialogTitle = title + ' - 测评订单';
        var href = "<?=url('index/SubjectOrder/orders')?>";
        href = GLOBAL.func.addUrlParam(href, 'combination_id', id);
        $(that.dialog).dialog({
            title: dialogTitle,
            iconCls: iconCls,
            width: <?=$loginMobile?"'100%'":"'90%'"?>,
            height: '100%',
            cache: false,
            href: href,
            modal: true,
            closable:true,
            onClose: $.noop,
            buttons:[
                {
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }
            ]
        });
        $(that.dialog).dialog('center');
    }
};
</script>