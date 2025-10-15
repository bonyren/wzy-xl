<div class="m-2 text-center">
    <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:qrcodeModule.download">下载</a>
    <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:qrcodeModule.refresh">刷新</a>
</div>
<div class="m-2 text-center">
    <img id="expert-qrcode-img" class="img-thumbnail" src="<?=$qrcode?>">
</div>
<script type="text/javascript">
    var qrcodeModule = {
        download:function(){
            window.open('<?=url('index/Qrcode/download', ['name'=>$name, 'savePath'=>$qrcode])?>')
        },
        refresh:function(){
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Expert/qrcode')?>',{id: <?=$id?>},function(res){
                $.messager.progress('close');
                if (res.code) {
                    $.app.method.tip('提示', res.msg, 'info');
                    $('#expert-qrcode-img').attr('src', res.data);
                } else {
                    $.app.method.alertError(null, res.msg);
                }
            },'json');
        }
    };
</script>