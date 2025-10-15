<script type="text/javascript">
    var iconClsDefs = <?=json_encode(\app\index\logic\Defs::$iconClsDefs, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?>;
    GLOBAL.namespace('config');
    GLOBAL.config.upload = <?=json_encode(config('upload'), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?>;
    GLOBAL.config.upload_image_url = '<?=url('index/Upload/uploadImage')?>';
    var commonModule = {
        dialog:'#globel-dialog-div',
        qrcodeInfo:function(text, title, $dialog=null){
            var that = this;
            $dialog = $dialog || $(that.dialog);
            if(GLOBAL.func.isValidUrl(text)){
                //二维码扫码url
                text = GLOBAL.func.addUrlParam(text, 'enter_scene', 'qrcode');
            }
            var href = '<?=url('index/Qrcode/displayQrcode')?>';
            href = GLOBAL.func.addUrlParam(href, 'text', text);
            href = GLOBAL.func.addUrlParam(href, 'title', title);
            $dialog.dialog({
                title: title,
                iconCls: 'fa fa-qrcode',
                width: <?=$loginMobile?"'100%'":500?>,
                height: '80%',
                href: href,
                modal: true,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $dialog.dialog('close');
                    }
                }]
            });
            $dialog.dialog('center');
        }
    };
    var datagridFormatter = {
        formatEntityStatus:function(val, row){
            return <?=json_encode(\app\index\logic\Defs::$entityStatusHtmlDefs)?>[val];
        }
    };
</script>