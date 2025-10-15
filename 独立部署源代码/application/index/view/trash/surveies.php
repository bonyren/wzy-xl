<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="
    striped:true,
    rownumbers:false,
    nowrap:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('index/Survey/index')?>',
    queryParams:{'search[delete_flag]':1},
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    onDblClickRow:function(index, row){
        <?=JVAR?>.view(row.id, row.name);
    },
    border:false">
    <thead>
    <tr>
        <th data-options="field:'btns',width:100,formatter:<?=JVAR?>.formatOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'banner',width:80,align:'center',formatter:<?=JVAR?>.formatImage.bind(<?=JVAR?>)">图片</th>
        <th data-options="field:'name',width:150">名称</th>
        <th data-options="field:'subjects_names',width:200">关联量表</th>
        <th data-options="field:'description',width:200">说明</th>
        <th data-options="field:'ctime',width:100">创建时间</th>
        <th data-options="field:'cfg_free',width:100,formatter:GLOBAL.func.formatBoolean">是否免费</th>
        <th data-options="field:'cfg_enter_personal_data',width:100,formatter:GLOBAL.func.formatBoolean">是否录入个人资料</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            名称 <input name="search[name]" class="easyui-textbox" prompt="请输入普查名称" data-options="width:200" />
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton" data-options="iconCls:'fa fa-search',
                            onClick:function(){ <?=JVAR?>.search(this); }">搜索
            </a>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton" data-options="iconCls:'fa fa-reply',
                            onClick:function(){ <?=JVAR?>.reset(this); }">重置
            </a>
        </div>
    </form>
</div>
<script type="text/javascript">
var <?=JVAR?> = {
    dialog: '#globel-dialog-div',
    datagrid:'#<?=DATAGRID_ID?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    formatOpt:function(val, row){
        var btns = [];
        btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="<?=JVAR?>.view(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.name) + '\')" title="查看普查详情"><i class="fa fa-eye fa-lg"></i>查看</a>');
        <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
        btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="<?=JVAR?>.restore('+row.id+',\''+GLOBAL.func.escapeALinkStringParam(row.name)+'\')" title="恢复"><i class="fa fa-mail-reply-all fa-lg">恢复</i></a>');
        <?php } ?>
        return btns.join(' ');
    },
    formatImage:function(val, row){
        return '<img class="img-thumbnail my-1" src="' + row.banner + '" style="height:60px;">';
    },
    reload:function(){
        $(this.datagrid).datagrid('reload');
    },
    search:function(that){
        var searchForm = $(that).closest('form');
        var paramObj = {'search[delete_flag]':1};
        $.each(searchForm.serializeArray(), function (){
            paramObj[this.name] = this.value;
        });
        $(this.datagrid).datagrid('load', paramObj);
    },
    reset:function(that){
        var searchForm = $(that).closest('form');
        searchForm.form('reset');
        $(this.datagrid).datagrid('load', {'search[delete_flag]':1});
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
            width: <?=$loginMobile?"'100%'":900?>,
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
    restore:function(id, title){
        var that = this;
        $.messager.confirm('提示','确认恢复'+title+'吗？',function(y){
            if(!y) { return; }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Survey/restore')?>',{id:id},function(res){
                $.messager.progress('close');
                if (res.code) {
                    $.app.method.tip('提示', res.msg, 'info');
                    that.reload();
                } else {
                    $.app.method.alertError(null, res.msg);
                }
            },'json');
        });
    }
};
</script>