<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=url('index/SubjectCombination/index')?>',
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
    onBeforeLoad:function(param){
        param['search[delete_flag]']=1;
    },
    onLoadSuccess:<?=JVAR?>.convert,
    border:false">
    <thead>
    <tr>
        <th data-options="field:'btns',width:100,align:'left',hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'banner',width:80,align:'center',formatter:<?=JVAR?>.formatImage.bind(<?=JVAR?>)">图片</th>
        <th data-options="field:'name',width:100,align:'center'">名称</th>
        <th data-options="field:'subjects_names',width:200,align:'center'">关联量表</th>
        <th data-options="field:'description',width:100,align:'center'">说明</th>
        <th data-options="field:'ctime',width:100,align:'center'">创建时间</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            名称 <input name="search[name]" class="easyui-textbox" prompt="请输入组合测试名称" style="width:200px;">
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-search" onclick="<?=JVAR?>.search()">搜索</a>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-reply" onclick="<?=JVAR?>.reset()">重置</a>
        </div>
    </form>
</div>
<script>
var <?=JVAR?> = {
    dialog:'#globel-dialog-div',
    datagrid:'#<?=DATAGRID_ID?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    convert:function(data){
        var that = <?=JVAR?>;
        data.rows.forEach(function(v,i){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="<?=JVAR?>.view(' + v.id + ',\'' + GLOBAL.func.escapeALinkStringParam(v.name) + '\')" title="查看组合量表详情"><i class="fa fa-eye fa-lg"></i>查看</a>');
            <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="<?=JVAR?>.restore('+v.id+',\''+GLOBAL.func.escapeALinkStringParam(v.name)+'\')" title="恢复"><i class="fa fa-mail-reply-all fa-lg">恢复</i></a>');
            <?php } ?>
            $(that.datagrid).datagrid('updateRow',{
                index:i,
                row:{
                    btns:btns.join(' ')
                }
            });
        });
        $.parser.parse('.tobe-parse');
    },
    formatImage:function(val, row){
        return '<img class="img-thumbnail my-1" src="' + row.banner + '" style="height:60px;">';
    },
    reload:function(){
        $(this.datagrid).datagrid('reload');
    },
    search:function(){
        var that = this;
        var data = {};
        $.each($(that.toolbar).children('form').serializeArray(), function() {
            data[this['name']] = this['value'];
        });
        $(that.datagrid).datagrid('load', data);
    },
    reset:function(){
        var that = this;
        $(that.toolbar).find('.easyui-textbox').textbox('clear');
        $(that.datagrid).datagrid('load', {});
    },
    view:function(id, name){
        var that = this;
        var href = '<?=url('index/SubjectCombination/view')?>';
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        $(that.dialog).dialog({
            title: name + ' - 组合量表详情',
            iconCls: 'fa fa-eye',
            width: <?=$loginMobile?"'100%'":450?>,
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
    restore:function(id, title){
        var that = this;
        $.messager.confirm('提示','确认恢复'+title+'吗？',function(y){
            if(!y) { return; }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/SubjectCombination/restore')?>',{id:id},function(res){
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