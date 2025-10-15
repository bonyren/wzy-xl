<style>
    .preview-container{position:relative;height:100%;width:100%;}
    .preview-container .loading{position:absolute;width:100%;background:#FFF;text-align: center;display:table}
    .preview-container .loading div{display:table-cell;vertical-align:middle;}
    .preview-container .preview-attach{margin:0;padding:0}
    .preview-container #attach-preview-image{text-align: center}
    .preview-container #attach-preview-none{font-family: 'Microsoft Yahei';font-size:20px;}
    .attach-info {position:absolute;width:100%;bottom:0;text-align: center;background-color: #FEFEE9;border-top:1px solid #B1B1B1}
    .attach-info h2{font-family: 'Microsoft Yahei';display: inline-block}
</style>
<div class="preview-container">
    <?php if($preview_type == 'office') : ?>
        <div class="loading">
            <div>
                <img src="/static/img/loading.gif">
                <p>Loading..</p>
            </div>
        </div>
        <iframe class="preview-attach" id="attach-preview-office" width="100%" frameborder="0" src="<?=$preview_url?>"></iframe>
    <?php elseif($preview_type == 'image') : ?>
        <div class="preview-attach" id="attach-preview-image">
            <img src="<?=$preview_url?>" border="0"  height="500px" >
            <div style="position: absolute;z-index:10;right: 50px;top: 50px">
                <a href="javascript:void(0)" class="previewImageM btn btn-default fa fa-search-minus"></a>
                <a href="javascript:void(0)" class="previewImageP btn btn-default fa fa-search-plus"></a>
            </div>
        </div>
    <?php elseif($preview_type == 'pdf') : ?>
        <div class="preview-attach" id="attach-preview-pdf"></div>
    <?php else : ?>
        <table class="preview-attach" id="attach-preview-none" width="100%">
            <tr>
                <td align="center">
                    <p class="bg-warning"><?=$error_msg?></p>
                </td>
            </tr>
        </table>
    <?php endif; ?>
</div>
<script>
    var box = $('#globel-dialog2-div');
    var h_box = box.height();
    var h_info = box.find('.attach-info').height();
    var h_preview = h_box - h_info - 8;
    box.find('.loading,.preview-attach').height(h_preview);
    var ifrm = document.getElementById('attach-preview-office');
    if (ifrm) {
        ifrm.onload = ifrm.onreadystatechange = function() {
            if (this.readyState && this.readyState != 'complete') {
                return;
            }
            box.find('.loading').remove();
        }
    }
    var $pdf = $('#attach-preview-pdf');
    if ($pdf.length) {
        PDFObject.embed("<?=$preview_url?>", $pdf);
    }
    $(function(){
        $(".previewImageP").click(function(){
            $("#attach-preview-image").find("img").attr("height",(parseInt($("#attach-preview-image").find("img").attr("height"))+10)+"px");
        });
        $(".previewImageM").click(function(){
            $("#attach-preview-image").find("img").attr("height",(parseInt($("#attach-preview-image").find("img").attr("height"))-10)+"px");
        });
    });
</script>