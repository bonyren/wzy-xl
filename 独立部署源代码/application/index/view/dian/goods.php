<?php
use app\Defs;
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['goods']?>',
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
    },
    onLoadSuccess:function(data){
        $.each(data.rows, function(i, row){
        });
    }
    ">
    <thead>
    <tr>
        <th data-options="field:'operate',width:90,fixed:true,formatter:<?=JVAR?>.operate,align:'left',hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'subject_name',width:80,align:'left'">量表名称</th>
        <th data-options="field:'url',width:200,align:'left'">商品链接</th>
        <th data-options="field:'status',width:30,align:'left',formatter:<?=JVAR?>.formatStatus">状态</th>
        <th data-options="field:'entered',width:50,align:'left'">创建时间</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <select class="easyui-combobox" id="goods_subject_id_<?=UNIQID?>" name="search[subject_id]" style="width:200px;" value=""
                data-options="prompt:'测评量表',limitToList:true,valueField:'id',textField:'name',url:'<?=url('index/Subject/getSubjectComboData')?>'">
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[status]" class="easyui-combobox" prompt="状态" editable="false" data-options="panelHeight:'auto',value:''" style="width:120px;">
                <?php foreach (Defs::$eGoodsStatusDefs as $k=>$v): ?>
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
        <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
        <div class="datagrid-search-form-box">
            <a class="easyui-linkbutton c8" data-options="iconCls:'fa fa-plus',
                        onClick:function(){ <?=JVAR?>.add(); }">生成商品
            </a>
        </div>
        <?php } ?>
        <div class="datagrid-search-form-box">
            <a class="easyui-linkbutton" iconCls="fa fa-share-square-o" onclick="<?=JVAR?>.export()">导出</a>
        </div>
    </form>
</div>
<script>
    var <?=JVAR?> = {
        dialog:'#globel-dialog-div',
        datagrid:'#<?=DATAGRID_ID?>',
        searchForm:'#<?=FORM_ID?>',
        operate:function(val, row){
            var btns = [];
            <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
            btns.push('<a href="javascript:;" class="btn btn-outline-secondary size-MINI radius my-1" onclick="<?=JVAR?>.delete('+row.id+')">删除</a>');
            <?php } ?>
            if(row.status == <?=Defs::eGoodsUsed?> && row.finished){
                btns.push('<a href="javascript:;" class="btn btn-outline-success size-MINI radius my-1" onclick="<?=JVAR?>.report('+row.id+')">报告</a>');
            }
            return btns.join(' ');
        },
        formatStatus:function(val){
            return <?=json_encode(Defs::$eGoodsStatusHtmlDefs, JSON_UNESCAPED_SLASHES)?>[val];
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
        report:function(id){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['report']?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $(that.dialog).dialog({
                title: '量表测评报告',
                width: "<?=$loginMobile?'100%':'60%'?>",
                height: "100%",
                href: href,
                iconCls:'fa fa-file-text-o',
                modal: true,
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
        delete:function(id){
            var that = <?=JVAR?>;
            $.messager.confirm('提示','确定删除吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=$urlHrefs['delete']?>',{id:id},function(res){
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
        export:function(){
            var that = <?=JVAR?>;
            if ($('#goods_subject_id_<?=UNIQID?>').combobox('getValue') == '') {
                $.app.method.alertError(null, '请选择测评量表');
                return;
            }
            var paramStr = $(that.searchForm).serialize();
            var href = '<?=$urlHrefs['export']?>';
            href += '?';
            href += paramStr;
            window.open(href);
        },
        add:function(customerId, name){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['add']?>';
            $(that.dialog).dialog({
                title: '发布商品链接',
                iconCls: 'fa fa-plus',
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
                height: '80%',
                cache: false,
                href: href,
                modal: true,
                maximizable:false,
                onClose:function(){
                    that.reload();    
                },
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