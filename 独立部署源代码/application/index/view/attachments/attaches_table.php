<div class="card" style="border-color:#fff;">
    <div class="clearfix">
        <div class="float-left">
        <a class="easyui-linkbutton" data-options="iconCls:'fa fa-cloud-upload',onClick:function(){
                                attachModule_<?=$uniqid?>.uploadAttach();
                            }">上传</a>
        </div>
        <div class="float-right">
            <?php if(!empty($prompt)){ ?>
                <span class="fa fa-info-circle"><?=$prompt?></span>
            <?php } ?>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-border table-bg">
            <thead>
                <tr>
                    <th>文件名</th>
                    <!--
                    <th width="140">大小(KB)</th>
                    -->
                    <th width="150">时间</th>
                    <th width="100">下载</th>
                    <th width="100">删除</th>
                </tr>
            </thead>
            <tbody id="attaches_<?=$uniqid?>">
            <?php foreach($bindValues['attaches'] as $attach){ ?>
                <tr id="attach_file_<?=$uniqid?>_<?=$attach['attachment_id']?>">
                    <td>
                        <a title="<?=$attach['original_name']?>" href="javascript:void(0)" onclick="QT.filePreview(<?=$attach['attachment_id']?>)">
                            <?=$attach['original_name']?>
                        </a>
                    </td>
                    <!--
                    <td>
                        <?=round($attach['size']/1024,2)?>
                    </td>
                    -->
                    <td>
                        <?=substr($attach['entered'],0,16)?>
                    </td>
                    <td>
                        <a class="text-secondary size-MINI fa fa-download" href="<?=$attach['download_url']?>" target="_blank">&nbsp;</a>
                    </td>
                    <td>
                        <a class="text-danger size-MINI fa fa-remove" href="javascript:void(0)" onclick="attachModule_<?=$uniqid?>.deleteAttach(<?=$attach['attachment_id']?>)">&nbsp;</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    var attachModule_<?=$uniqid?> = {
        dialog:'#globel-dialog-div',
        dialog2:'#globel-dialog2-div',
        callback:<?=$bindValues['callback'] ? $bindValues['callback'] : 'null'?>,
        queries:<?=json_encode($_GET,JSON_UNESCAPED_UNICODE)?>,
        uploadAttach:function(){
            var that = this;
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
                var html = '';
                $.each(obj.data,function(i,v){
                    html += '<tr id="attach_file_<?=$uniqid?>_' + v.attachment_id + '">' +
                        '<td class="text-center"><a title="' + v.name + '" href="javascript:void(0)" onclick="QT.filePreview('+v.attachment_id+')">'+
                        v.name + '</a></td>' +
                        '<td class="text-center attach-size">' + v.size + '</td>' +
                        '<td><a class="text-secondary size-MINI fa fa-download" href="' + v.download_url + '" target="_blank">&nbsp;</a></td>' +
                        '<td><a class="text-danger size-MINI fa fa-remove" href="javascript:void(0)" onclick="attachModule_<?=$uniqid?>.deleteAttach('+v.attachment_id+')">&nbsp;</a></td>' +
                        '</tr>';
                });
                $("#attaches_<?=$uniqid?>").append(html);
                if (that.callback) {
                    that.callback(obj.data,that.queries);
                }
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
                        $('#attach_file_<?=$uniqid?>_' + attachmentId).remove();
                    }
                }, 'json');
            });
        }
    }
</script>