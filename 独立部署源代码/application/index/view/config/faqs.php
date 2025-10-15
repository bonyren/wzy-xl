<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="
    striped:true,
    rownumbers:false,
    nowrap:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['index']?>',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    border:false,
    rowStyler:<?=JVAR?>.rowStyler.bind(<?=JVAR?>)">
    <thead>
    <tr>
        <th data-options="field:'opt',width:100,formatter:<?=JVAR?>.formatOpt">操作</th>
        <th data-options="field:'question',width:100">问题</th>
        <th data-options="field:'order',width:50">排序</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ <?=JVAR?>.save(0); },iconCls:iconClsDefs.add">新增</a>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
var <?=JVAR?> = {
    datagrid:'#<?=DATAGRID_ID?>',
    dialog:'#globel-dialog-div',
    rowStyler:function (index, row) {
        //每一行会被调用两次
        if(row.delete_flag == 1){
            return DG_ROW_CSS.rowDel;
        }
    },
    formatOpt:function(val, row, index){
        var btns = [];
        btns.push('<a href="javascript:;" class="btn btn-outline-secondary size-MINI radius" onclick="<?=JVAR?>.save(' + row.id + ')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
        if(row.delete_flag == 1){
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius" onclick="<?=JVAR?>.delete(' + row.id + ', 0)" title="恢复"><i class="fa fa-mail-reply-all fa-lg">恢复</i></a>');
        }else{
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius" onclick="<?=JVAR?>.delete(' + row.id + ')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
        }
        return btns.join(' ');
    },
    save:function(id){
        var that = <?=JVAR?>;
        var href = '<?=$urlHrefs['save']?>';
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        if(id == 0){
            var dialogTitle = '新增';
            var iconCls = 'fa fa-plus-circle';
        }else{
            var dialogTitle = '修改';
            var iconCls = 'fa fa-pencil-square';
        }
        $(that.dialog).dialog({
            title: dialogTitle,
            iconCls: iconCls,
            width: <?=$loginMobile?"'100%'":"'80%'"?>,
            height: '60%',
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
    delete:function(id, flag=1){
        var that = <?=JVAR?>;
        var href = '<?=$urlHrefs['delete']?>';
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        $.messager.confirm('提示', '确认删除吗?', function(result){
            if(!result) return false;
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, {flag:flag}, function(res){
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
    reload:function(){
        var that = <?=JVAR?>;
        $(that.datagrid).datagrid('reload');
    }
};
</script>