
<div class="d-flex justify-content-between" style="width:<?=$width+35?>px;">
    <div>    
        <img id="image_preview_<?=$uniqid?>" class="img-thumbnail" style="width:<?=$width?>px;"
            src="<?=$figureUrl?>">
         <input id="image_url_<?=$uniqid?>" type="hidden" name="<?=$inputCtrlName?>" value="<?=$figureUrl?>">
    </div>
    <div class="d-flex flex-column align-self-center">
        <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-upload" onclick="figureSaveModule_<?=$uniqid?>.upload()"></a>
        <div class="m-1"></div>
        <a href="javascript:;" class="easyui-linkbutton" iconCls="fa fa-trash" onclick="figureSaveModule_<?=$uniqid?>.delete()"></a>
    </div>
</div>    

<script type="text/javascript">
    var figureSaveModule_<?=$uniqid?> = {
        upload:function(){
            $.app.method.uploadImage('<?=url('index/Upload/uploadImage')?>',function(res){
                if(res.code){
                    $('#image_preview_<?=$uniqid?>').attr('src', res.data.absolute_url);
                    $('#image_url_<?=$uniqid?>').val(res.data.absolute_url);
                }else{
                    $.app.method.alertError(null, res.msg);
                }
            });
        },
        delete:function(){
            $('#image_preview_<?=$uniqid?>').attr('src', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=');
            $('#image_url_<?=$uniqid?>').val('');
        }
    };
    (function($){
        var imageUrl = $('#image_preview_<?=$uniqid?>').attr('src');
        if(!imageUrl){
            $('#image_preview_<?=$uniqid?>').attr('src', 'data:image/gif;base64,R0lGODdhAQABAPAAAMPDwwAAACwAAAAAAQABAAACAkQBADs=');
        }
    })(jQuery);
</script>