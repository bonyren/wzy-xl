<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    selectOnCheck:true,
    checkOnSelect:true,
    url:'<?=url('index/Trash/subjects')?>',
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
    onLoadSuccess:<?=JVAR?>.convert,
    border:false,
    emptyMsg:''">
    <thead>
    <tr>
        <th data-options="field:'btns',width:200,fixed:true,align:'left',formatter:<?=JVAR?>.formatOpt.bind(<?=JVAR?>),hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'image_url',width:80,align:'center',formatter:<?=JVAR?>.formatImage.bind(<?=JVAR?>)">图片</th>
        <th data-options="field:'name',width:200,align:'center'">名称</th>
        <th data-options="field:'category',width:80,align:'center'">分类</th>
        <th data-options="field:'item_num',width:80,align:'center'">题目数量</th>
        <th data-options="field:'current_price',width:80,align:'center',sortable:true">价格(元)</th>
        <th data-options="field:'participants',width:80,align:'center',sortable:true">总测评次数</th>
        <th data-options="field:'total_amount',width:80,align:'center',sortable:true">总测评金额</th>
        <th data-options="field:'status',width:80,align:'center',formatter:<?=JVAR?>.formatStatus.bind(<?=JVAR?>)">状态</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
                <span class="ml-1">名称</span> 
                <input name="search[name]" class="easyui-textbox" prompt="请输入量表名称" style="width:150px;">
        </div>
        <div class="datagrid-search-form-box">
                <span class="ml-1">分类</span>
                <select name="search[category_id]" class="easyui-combobox" prompt="请选择量表分类" style="width:150px;" data-options="editable:false,value:''">
                    <?php foreach ($categories as $v): ?>
                        <option value="<?=$v['id']?>"><?=$v['name']?></option>
                    <?php endforeach; ?>
                </select>
        </div>
        <div class="datagrid-search-form-box">
                <span class="ml-1">状态</span>
                <select name="search[status]" class="easyui-combobox" prompt="请选择状态" style="width:150px;" data-options="editable:false,panelHeight:'auto',value:''">
                    <?php foreach (IndexDefs::$entityStatusDefs as $k=>$v): ?>
                        <option value="<?=$k?>"><?=$v?></option>
                    <?php endforeach; ?>
                </select>
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
        searchForm: '#<?=FORM_ID?>',
        formatOpt:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="<?=JVAR?>.view(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.name) + '\')" title="查看"><i class="fa fa-eye fa-lg"></i>查看</a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="<?=JVAR?>.restore('+row.id+',\''+GLOBAL.func.escapeALinkStringParam(row.name)+'\')" title="恢复"><i class="fa fa-mail-reply-all fa-lg">恢复</i></a>');
            return btns.join(' ');
        },
        formatStatus:function(val, row){
            return <?=json_encode(IndexDefs::$entityStatusHtmlDefs)?>[val];
        },
        formatImage:function(val, row){
            return '<img class="img-thumbnail my-1" src="' + row.image_url + '" style="height:60px;">';
        },
        convert:function(data){
            $.parser.parse('.to-be-parse');
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
            $(that.searchForm).form('reset');
            $(that.datagrid).datagrid('load', {});
        },
        view:function(id, name){
            var that = this;
            var href = '<?=url('index/Subject/view')?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $(that.dialog).dialog({
                title: name + ' - 量表查看',
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
        restore:function(id, title){
            var that = this;
            $.messager.confirm('提示','确定恢复"'+title+'"吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Subject/restore')?>',{id:id},function(res){
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