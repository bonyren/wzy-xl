<div style="width:<?=$width?>px;" id="video-control-box-<?=$uniqid?>">
    <video id="video_preview_<?=$uniqid?>" controls="controls" style="width:<?=$width?>px;" src="<?=$videoUrl?>">
        your browser does not support the video tag
    </video>
    <p>支持mp4,webm,ogv格式</p>
</div>
<div style="width:<?=$width?>px;" class="text-center">
    <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-upload" onclick="videoSaveModule_<?=$uniqid?>.upload()"></a>
    <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-trash" onclick="videoSaveModule_<?=$uniqid?>.delete()"></a>
    <input id="video_url_<?=$uniqid?>" type="hidden" name="<?=$inputCtrlName?>" value="<?=$videoUrl?>">
</div>

<script type="text/javascript">
    var videoSaveModule_<?=$uniqid?> = {
        upload:function(){
            $.app.method.uploadVideo('<?=url('index/Upload/uploadVideo')?>',function(res){
                if(res.code){
                    $('#video_preview_<?=$uniqid?>').attr('src', res.data.absolute_url);
                    $('#video_url_<?=$uniqid?>').val(res.data.absolute_url);
                    $('#video-control-box-<?=$uniqid?>').show();
                }else{
                    $.app.method.alertError(null, res.msg);
                }
            });
        },
        delete:function(){
            //$('#video_preview_<?=$uniqid?>').attr('src', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=');
            $('#video_preview_<?=$uniqid?>').attr('src', '');
            $('#video_url_<?=$uniqid?>').val('');
            $('#video-control-box-<?=$uniqid?>').hide();
        }
    };
    (function($){
        var videoUrl = $('#video_preview_<?=$uniqid?>').attr('src');
        if(!videoUrl){
            $('#video-control-box-<?=$uniqid?>').hide();
            //$('#video_preview_<?=$uniqid?>').attr('src', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=');
        }
    })(jQuery);
</script>