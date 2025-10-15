<table id="<?=DATAGRID_ID?>" class="easyui-treegrid"
       data-options="
        url: '<?=$urls['list']?>',
        fit:true,
        fitColumns:<?=$loginMobile?'false':'true'?>,
        rownumbers: false,
        border:false,
        idField:'id',
        treeField:'name',
        onDblClickRow:<?=JVAR?>.edit,
        rowStyler:<?=JVAR?>.rowStyler,
        toolbar:<?=JVAR?>.toolbar">
    <thead>
    <tr>
        <th field="name" width="10">名称</th>
        <th field="c" width="10">控制器</th>
        <th field="a" width="10">方法</th>
        <th field="params" width="10">附加参数</th>
        <th field="order_id" width="10">排序</th>
        <th field="iconCls" width="10">icon类</th>
    </tr>
    </thead>
</table>

<script>
var <?=JVAR?> = {
    dialog:'#globel-dialog-div',
    treegrid:'#<?=DATAGRID_ID?>',
    toolbar:[
        { text: '添加', iconCls: 'fa fa-plus', handler: function(){<?=JVAR?>.save();} },
        { text: '删除', iconCls: 'fa fa-trash', handler: function(){<?=JVAR?>.del();} },
        { text: '刷新', iconCls: 'fa fa-refresh', handler: function(){<?=JVAR?>.reload();} }
    ],
    rowStyler:function(row){
        row.show_cn = (row.show === '1') ? '是' : '否';
        row.cache_cn = (row.cache === '1') ? '是' : '否';
    },
    reload:function(){
        $(this.treegrid).treegrid('reload');
    },
    del:function(){
        var that = this;
        $.messager.confirm('提示','确定要删除该节点吗？',function(y){
            if(!y){
                return false;
            }
            var node = $(that.treegrid).treegrid('getSelected');
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=$urls['delete']?>', {id:node.id}, function(res){
                $.messager.progress('close');
                if (res.code) {
                    $.app.method.tip('提示', '删除成功');
                    $(that.treegrid).treegrid('reload');
                } else {
                    $.messager.alert('提示', res.msg, 'error');
                }
            }, 'json');
        });
    },
    edit:function(row){
        <?=JVAR?>.save(row.id);
    },
    save:function(editId){
        var that = this;
        var id = editId ? editId : 0;
        var default_pid = 0;
        if (!id) {
            var selected = $(this.treegrid).treegrid('getSelected');
            if (selected != null) {
                default_pid = selected.id;
            }
        }
        var $dialog = $(that.dialog);
        var href = '<?=$urls['edit']?>';
        href = GLOBAL.func.addUrlParam(href, 'id', id);
        href = GLOBAL.func.addUrlParam(href, 'pid', default_pid);
        $dialog.dialog({
            title: id ? '编辑节点' : '添加节点',
            width: 390,
            height: "90%",
            cache: false,
            href: href,
            modal: true,
            buttons:[{
                text:'确定',
                iconCls:iconClsDefs.ok,
                handler: function(){
                    var form = $dialog.find('form');
                    if(form.length == 0){
                        $dialog.dialog('close');
                        return;
                    }
                    if (!form.form('validate')) {
                        return;
                    }
                    $.messager.progress({text:'处理中，请稍候...'});
                    $.post(href, form.serialize(), function(res){
                        $.messager.progress('close');
                        if (res.code) {
                            $.app.method.tip('提示', '保存成功');
                            $dialog.dialog('close');
                            $(that.treegrid).treegrid('reload');
                        } else {
                            $.messager.alert('提示', res.msg, 'error');
                        }
                    }, 'json');
                }
            },{
                text:'取消',
                iconCls:iconClsDefs.cancel,
                handler: function(){
                    $dialog.dialog('close');
                }
            }]
        });
        $dialog.dialog('center');
    }
};
</script>