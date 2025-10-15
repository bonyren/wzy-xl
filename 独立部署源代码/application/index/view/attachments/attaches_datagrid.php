<table id="attachesDatagrid_<?=$uniqid?>" class="easyui-datagrid" data-options="
    striped:true,
    fit:<?=$fit?'true':'false'?>,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    selectOnCheck:false,
    checkOnSelect:false,
    url:'<?=$urlHrefs['attaches']?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:false,
    border:false,
    fitColumns:false">
    <thead>
    <tr>
        <th field="ck" checkbox="true"></th>
        <th data-options="field:'original_name',width:200,formatter:attachModule_<?=$uniqid?>.formatName">文件名</th>
        <!--
        <th data-options="field:'size',align:'center',width:100,formatter:GLOBAL.func.byteFormat">大小(Bytes)</th>
        -->
        <th data-options="field:'entered',width:100,formatter:GLOBAL.func.dateTimeFilter">时间</th>
        <th data-options="field:'download_url',align:'center',width:60,formatter:attachModule_<?=$uniqid?>.formatDownload">下载</th>
        <th data-options="field:'opt',align:'center',width:60,formatter:attachModule_<?=$uniqid?>.formatOperate">删除</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>">
    <a class="easyui-linkbutton" data-options="iconCls:'fa fa-cloud-upload',onClick:attachModule_<?=$uniqid?>.uploadAttach">上传</a>
    <a class="easyui-linkbutton" data-options="iconCls:'fa fa-trash-o',onClick:attachModule_<?=$uniqid?>.deleteAttaches">删除</a>
</div>
<script type="text/javascript">
    var attachModule_<?=$uniqid?> = {
        dialog:'#globel-dialog-div',
        dialog2:'#globel-dialog2-div',
        datagrid:'#attachesDatagrid_<?=$uniqid?>',
        callback:<?=$bindValues['callback'] ? $bindValues['callback'] : 'null'?>,
        queries:<?=json_encode($_GET,JSON_UNESCAPED_UNICODE)?>,
        reload:function(){
            $(this.datagrid).datagrid('reload');
        },
        load:function(){
            $(this.datagrid).datagrid('load');
        },
        formatName:function(val, row, index){
            if(row.attachment_id == 0){
                return '';
            }
            return '<a title="' + val + '" href="javascript:void(0)" onclick="QT.filePreview(' + row.attachment_id + ')">' + val + '</a>';
        },
        formatDownload:function(val, row, index){
            if(row.attachment_id == 0){
                return '';
            }
            return '<a class="text-secondary size-MINI fa fa-download" href="' + row.download_url + '" target="_blank">&nbsp;</a>';
        },
        formatOperate:function(val, row, index){
            if(row.attachment_id == 0){
                return '';
            }
            var btns = [];
            btns.push('<a class="text-danger size-MINI fa fa-remove" href="javascript:void(0)" onclick="attachModule_<?=$uniqid?>.deleteAttach(' + row.attachment_id + ')">&nbsp;</a>');
            return btns.join(' ');
        },
        uploadAttach:function(){
            var that = attachModule_<?=$uniqid?>;
            var url = '<?=$urlHrefs['uploadAttach']?>';
            $.app.method.upload(url, function (obj) {
                if(!obj.code){
                    $.app.method.tip('提示',obj.msg,'error');
                    return;
                }else{
                    if (obj.html) {
                        $.messager.alert('提示',obj.html,'warning');
                    } else {
                        $.app.method.tip('提示','成功','info');
                    }
                }
                <?php if($bindValues['externalId'] != 0){ ?>
                    that.reload();
                <?php }else{ ?>
                    $.each(obj.data,function(i,v){
                        $(that.datagrid).datagrid('appendRow', {
                            attachment_id: v.attachment_id,
                            original_name: v.name,
                            size: v.size,
                            entered: v.entered,
                            download_url: v.download_url
                        });
                    });
                <?php } ?>
                if (that.callback) {
                    that.callback(obj.data,that.queries);
                }
            });
        },
        deleteAttaches:function(){
            var that = attachModule_<?=$uniqid?>;
            var attachmentIds = [];
            var rows = $(that.datagrid).datagrid('getChecked');
            if(rows.length == 0){
                $.app.method.alertError(null, '请选择要删除的文件');
                return;
            }
            $.each(rows, function(index, val){
                attachmentIds.push(val.attachment_id);
            });
            var href = '<?=$urlHrefs['deleteAttaches']?>';
            $.messager.confirm('提示', '确认删除吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {attachmentIds:attachmentIds}, function(res){
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
        deleteAttach:function(attachmentId){
            var that = this;
            var href = '<?=$urlHrefs['deleteAttach']?>';
            href += href.indexOf('?') != -1 ? '&attachmentId=' + attachmentId : '?attachmentId='+attachmentId;
            $.messager.confirm('提示', '确认删除吗?', function(result){
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
        }
    }
</script>