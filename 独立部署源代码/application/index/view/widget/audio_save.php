<div style="width:<?=$width?>px;" id="audio-control-box-<?=$uniqid?>">
    <audio id="audio_preview_<?=$uniqid?>" controls="controls" src="<?=$audioUrl?>">
        your browser does not support the audio tag
    </audio>
    <p>支持mp3,ogg格式</p>
</div>
<div style="width:<?=$width?>px;" class="text-center">
    <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-upload" onclick="audioSaveModule_<?=$uniqid?>.upload()"></a>
    <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-trash" onclick="audioSaveModule_<?=$uniqid?>.delete()"></a>
    <input id="audio_url_<?=$uniqid?>" type="hidden" name="<?=$inputCtrlName?>" value="<?=$audioUrl?>">
</div>

<script type="text/javascript">
    var audioSaveModule_<?=$uniqid?> = {
        upload:function(){
            $.app.method.uploadAudio('<?=url('index/Upload/uploadAudio')?>',function(res){
                if(res.code){
                    $('#audio_preview_<?=$uniqid?>').attr('src', res.data.absolute_url);
                    $('#audio_url_<?=$uniqid?>').val(res.data.absolute_url);
                    $('#audio-control-box-<?=$uniqid?>').show();
                }else{
                    $.app.method.alertError(null, res.msg);
                }
            });
        },
        delete:function(){
            //$('#audio_preview_<?=$uniqid?>').attr('src', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=');
            $('#audio_preview_<?=$uniqid?>').attr('src', '');
            $('#audio_url_<?=$uniqid?>').val('');
            $('#audio-control-box-<?=$uniqid?>').hide();
        }
    };
    (function($){
        var audioUrl = $('#audio_preview_<?=$uniqid?>').attr('src');
        if(!audioUrl){
            $('#audio-control-box-<?=$uniqid?>').hide();
            //$('#audio_preview_<?=$uniqid?>').attr('src', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=');
        }
    })(jQuery);
</script>