<?php
use app\index\logic\Defs as IndexDefs;
?>
<div class="form-container">
    <div class="form-body">
        <div class="p-3">
            <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-upload" onclick="reportDemoImagesModule.upload()">上传</a>
        </div>
        <div id="reportDemoImagesList" class="easyui-imagelist" data-options="onDelete:reportDemoImagesModule.onDelete">
        </div>
    </div>
    <div class="form-toolbar">
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.ok,
                    onClick:function(){
                        reportDemoImagesModule.save(this);
                    }">保存
        </a>
        &nbsp;
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.cancel,
                    onClick:reportDemoImagesModule.cancel">关闭
        </a>
    </div>
</div>
<script>
    var reportDemoImagesModule = {
        images:<?=json_encode($images)?>,
        init:function(){
            reportDemoImagesModule.images.forEach(function(image){
                $('#reportDemoImagesList').imagelist('addItem', 0, '', image);
            });
        },
        upload:function(){
            $.app.method.uploadImage('<?=url('index/Upload/uploadImage')?>',function(res){
                if(res.code){
                    reportDemoImagesModule.images.push(res.data.absolute_url);
                    $('#reportDemoImagesList').imagelist('addItem', 0, '', res.data.absolute_url);
                }else{
                    $.app.method.alertError(null, res.msg);
                }
            });
        },
        onDelete:function(item){
            let pos = reportDemoImagesModule.images.indexOf(item.src)
            if(pos != -1){
                reportDemoImagesModule.images.splice(pos, 1);
            }
        },
        save:function(that){
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=$urlHrefs['save']?>',{
                images:reportDemoImagesModule.images
            },
            function(res){
                $.messager.progress('close');
                if (res.code) {
                    $.app.method.tip('提示', res.msg, 'info');
                    $(that).closest('.window-body').dialog('close');
                } else {
                    $.app.method.alertError(null, res.msg);
                }
            }, 'json');
        },
        cancel:function(){
            $(this).closest('.window-body').dialog('close');
        }
    };
    setTimeout(function(){
        reportDemoImagesModule.init();
    }, 500);
</script>