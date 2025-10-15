<div id="attaches_<?=$uniqid?>" class="d-flex">
    <?php foreach($bindValues['attaches'] as $attach){ ?>
        <div id="attach_file_<?=$uniqid?>_<?=$attach['attachment_id']?>" class="attach-box-light">
            <div class="text-center mr-2 float-left">
                <a title="<?=$attach['original_name']?>" href="javascript:void(0)" onclick="QT.filePreview(<?=$attach['attachment_id']?>)">
                    <?=$attach['original_name']?>
                </a>
            </div>
            <div class="text-center float-left">
                <a class="btn btn-danger size-MINI fa fa-remove" href="javascript:void(0)" onclick="attachModule_<?=$uniqid?>.deleteAttach(<?=$attach['attachment_id']?>)"></a>
                &nbsp;
                <a class="btn btn-secondary size-MINI fa fa-download" href="<?=$attach['download_url']?>" target="_blank"></a>
            </div>
        </div>
    <?php } ?>
</div>
<div class="m-1">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="fa fa-cloud-upload" onclick="attachModule_<?=$uniqid?>.uploadAttach()">上传</a>
</div>


<script type="text/javascript">
    var attachModule_<?=$uniqid?> = {
        dialog:'#globel-dialog-div',
        dialog2:'#globel-dialog2-div',
        replace:<?=$bindValues['replace']?>,
        callback:<?=$bindValues['callback'] ? $bindValues['callback'] : 'null'?>,
        singleSelect:<?=$bindValues['singleSelect']?>,
        uploadAttach:function(){
            var that = this;
            var url = '<?=$urlHrefs['uploadAttach']?>';
            //只允许选择当个文件
            $.app.method.upload(url, function(obj){
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
                var html = [];
                $.each(obj.data,function(i, item){
                    html.push(
                        '<div id="attach_file_<?=$uniqid?>_' + item.attachment_id + '" class="attach-box-light">' +
                        '<div class="text-center mr-2 float-left"><a title="' + item.name + '" href="javascript:void(0)" onclick="QT.filePreview('+item.attachment_id+')">'+
                        item.name + '</a></div>' +
                        '<div class="text-center float-left">' +
                        '<a class="btn btn-danger size-MINI fa fa-remove" href="javascript:void(0)" onclick="attachModule_<?=$uniqid?>.deleteAttach('+item.attachment_id+')"></a>'+
                        '&nbsp;<a class="btn btn-secondary size-MINI fa fa-download" href="' + item.download_url + '" target="_blank"></a></div>' +
                        '</div>'
                    );
                });
                if (that.replace) {
                    $("#attaches_<?=$uniqid?>").html(html.pop());
                } else {
                    $("#attaches_<?=$uniqid?>").append(html);
                }
                if (that.callback) {
                    that.callback(obj.data);
                }
            }, that.singleSelect);
        },
        deleteAttach:function(attachmentId){
            var that = this;
            var href = '<?=$urlHrefs['deleteAttach']?>';
            href += href.indexOf('?') != -1 ? '&attachmentId=' + attachmentId : '?attachmentId='+attachmentId;
            $.messager.confirm('提示', '确认删除吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, '', function(res){
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
    };
</script>