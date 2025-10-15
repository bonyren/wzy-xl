
<div>
    <div class="position-relative" style="width:<?=$width?>px;">    
        <img id="image_preview_<?=$uniqid?>" class="img-thumbnail" style="width:<?=$width?>px;"
            src="<?=$figureUrl?>">
         <input id="image_url_<?=$uniqid?>" type="hidden" name="<?=$inputCtrlName?>" value="<?=$figureUrl?>">
         <!--
         <div class="position-absolute d-flex flex-row" style="left:50%;top:50%;z-index:999;margin-left:-38px;margin-top:-19px;">
            <a href="javascript:;" plain="true" class="easyui-linkbutton m-1" iconCls="fa fa-upload" onclick="figureSaveModule_<?=$uniqid?>.upload()"></a>
            <a href="javascript:;" plain="true" class="easyui-linkbutton m-1" iconCls="fa fa-trash" onclick="figureSaveModule_<?=$uniqid?>.delete()"></a>
         </div>
        -->
        <div class="position-absolute" style="top:0;right: 0;">
        <a href="javascript:;" plain="true" class="easyui-linkbutton" iconCls="fa fa-trash" onclick="figureSaveModule_<?=$uniqid?>.delete()"></a>
        </div>
        <div class="position-absolute" style="left:50%;top:50%;z-index:999;margin-left:-19px;margin-top:-19px;">
            <a href="javascript:;" plain="true" class="easyui-linkbutton" iconCls="fa fa-upload" onclick="figureSaveModule_<?=$uniqid?>.upload()"></a>
        </div>
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