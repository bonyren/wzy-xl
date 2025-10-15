<?php
use app\index\logic\Defs as IndexDefs;
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
    onDblClickRow:function(index, row){
        <?=JVAR?>.view(row.id, row.name);
    },
    onLoadSuccess:<?=JVAR?>.onLoadSuccess,
    onBeforeLoad:<?=JVAR?>.onBeforeLoad,
    border:false">
    <thead>
    <tr>
        <th rowspan="2" data-options="field:'btns',width:60,fixed:true,align:'center',formatter:<?=JVAR?>.formatOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <?php
        $colspan = 9;
        ?>
        <th colspan="<?=$colspan?>">属性</th>
        <th colspan="2">次数统计</th>
    </tr>
    <tr>
        <!--属性-->
        <th data-options="field:'banner',width:80,align:'center',formatter:<?=JVAR?>.formatImage.bind(<?=JVAR?>)">图片</th>
        <th data-options="field:'name',width:150">名称</th>
        <th data-options="field:'subjects_names',width:200">关联量表</th>
        <th data-options="field:'description',width:200">介绍</th>
        <th data-options="field:'ctime',width:100">创建时间</th>
        <th data-options="field:'cfg_free',width:100,formatter:GLOBAL.func.formatBoolean">是否免费</th>
        <th data-options="field:'cfg_enter_personal_data',width:100,formatter:GLOBAL.func.formatBoolean">录入个人资料</th>
        <th data-options="field:'cfg_view_report',width:100,formatter:GLOBAL.func.formatBoolean">允许用户查看报告</th>
        <th data-options="field:'status',width:80,align:'center',sortable:true,formatter:datagridFormatter.formatEntityStatus">状态</th>
        <!--次数统计-->
        <th data-options="field:'completed_count',width:100">完成</th>
        <th data-options="field:'warning_count',width:100">预警</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[name]" class="easyui-textbox" prompt="普查名称" data-options="validType:['length[0,64]']" style="width:200px;" />
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
                            onClick:function(){ <?=JVAR?>.search(this); }">搜索
            </a>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton search-reset" data-options="iconCls:'fa fa-rotate-left',
                            onClick:function(){ <?=JVAR?>.reset(this); }">重置
            </a>
        </div>
    </form>
    <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
    <div class="line my-1"></div>
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ <?=JVAR?>.save(0); },iconCls:iconClsDefs.add">新增普查</a>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
var <?=JVAR?> = {
    dialog: '#globel-dialog-div',
    datagrid:'#<?=DATAGRID_ID?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    searchForm: '#<?=FORM_ID?>',
    formatOpt:function(val, row, index){
        var html = '<a href="javascript:void(0)" id="<?=UNIQID?>-survey-operate-row-' + index + '"' +
                        'data-options="menu:\'#<?=UNIQID?>-survey-operate-row-menu-'  + index + '\',iconCls:\'fa fa-ellipsis-v\'" ></a>' +
                    '<div id="<?=UNIQID?>-survey-operate-row-menu-' + index + '" style="width:60px;">';
        /***只读操作******************************************************************/            
        html += '<div data-options="iconCls:\'fa fa-eye\'" onclick="<?=JVAR?>.view('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">查看</div>';
        html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcodeInfo('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) + '\')">二维码</div>';
        html += '<div data-options="iconCls:\'fa fa-navicon\'" onclick="<?=JVAR?>.orders('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">订单</div>';
        /***读写操作******************************************************************/
        <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
            html += '<div class="menu-sep"></div>';
            html += '<div data-options="iconCls:\'fa fa-pencil-square-o\'" onclick="<?=JVAR?>.save('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">编辑</div>';
            html += '<div data-options="iconCls:\'fa fa-building-o\'" onclick="<?=JVAR?>.organization('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">组织</div>';
            html += '<div data-options="iconCls:\'fa fa-trash-o\'" onclick="<?=JVAR?>.delete('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">删除</div>';
            html += '<div data-options="iconCls:\'fa fa-trash\'" onclick="<?=JVAR?>.deleteForce('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">硬删除</div>';
        <?php } ?> 
        html += '</div>';
        return html;
    },
    formatImage:function(val, row){
        return '<img class="img-thumbnail my-1" src="' + row.banner + '" style="height:60px;">';
    },
    onLoadSuccess:function(data){
        var that = <?=JVAR?>;
        var total = $(that.datagrid).datagrid('getRows').length;
        for(var i=0; i<total; i++){
            $('#<?=UNIQID?>-survey-operate-row-' + i).splitbutton();
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
            $('#<?=UNIQID?>-survey-operate-row-' + i).remove();
            $('#<?=UNIQID?>-survey-operate-row-menu-' + i).remove();
        }
    },
    reload:function(){
        var that = <?=JVAR?>;
        $(that.datagrid).datagrid('reload');
    },
    search:function(that){
        var searchForm = $(that).closest('form');
        var isValid = searchForm.form('validate');
        if(!isValid){
            return;
        }
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
    },
    save:function(id, title=''){
        var that = <?=JVAR?>;
        var iconCls = 'fa fa-plus-circle';
        var dialogTitle = '新增普查';
        if(id){
            iconCls = 'fa fa-pencil-square';
            dialogTitle = title + ' - 普查编辑';
        }
        var href = "<?=url('index/Survey/save')?>";
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        href = GLOBAL.func.addUrlParam(href, 'callback_submit', '<?=JVAR?>.reload();$("#globel-dialog-div").dialog("close");');
        href = GLOBAL.func.addUrlParam(href, 'callback_cancel', '$("#globel-dialog-div").dialog("close");');
        $(that.dialog).dialog({
            title: dialogTitle,
            iconCls: iconCls,
            width: <?=$loginMobile?"'100%'":900?>,
            height: '100%',
            cache: false,
            href: href,
            modal: true,
            closable:true,
            onClose: $.noop,
            buttons:[]
        });
        $(that.dialog).dialog('center');
    },
    view:function(id, title=''){
        var that = <?=JVAR?>;
        var iconCls = 'fa fa-eye';
        var dialogTitle = title + ' - 普查查看';
        var href = "<?=url('index/Survey/view')?>";
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        $(that.dialog).dialog({
            title: dialogTitle,
            iconCls: iconCls,
            width: <?=$loginMobile?"'100%'":600?>,
            height: '60%',
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
    },
    delete:function(id, title){
        var that = <?=JVAR?>;
        $.messager.confirm('提示','确定删除"'+title+'"吗？',function(y){
            if(!y) { return; }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Survey/delete')?>',{id:id},function(res){
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
    deleteForce:function(id, title){
        var that = <?=JVAR?>;
        $.messager.confirm('提示','确定硬删除"'+title+'"吗？',function(y){
            if(!y) { return; }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Survey/deleteForce')?>',{id:id},function(res){
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
    qrcodeInfo:function(id, title){
        var that = <?=JVAR?>;
        var subjectUrl = '<?=url('mp/Subject/survey_test', '', true, true)?>';
        subjectUrl = GLOBAL.func.addUrlParam(subjectUrl, 'survey_id', id);
        commonModule.qrcodeInfo(subjectUrl, title);
    },
    orders: function(id, title){
        var that = <?=JVAR?>;
        var iconCls = 'fa fa-navicon';
        var dialogTitle = title + ' - 测评订单';
        var href = "<?=url('index/SubjectOrder/orders')?>";
        href = GLOBAL.func.addUrlParam(href, 'survey_id', id);
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
    },
    organization:function(id, title){
        var that = <?=JVAR?>;
        var iconCls = 'fa fa-building-o';
        var dialogTitle = title + ' - 组织管理';
        var href = "<?=url('index/SurveyOrganization/index')?>";
        href = GLOBAL.func.addUrlParam(href, 'surveyId', id);
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