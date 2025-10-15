
<!--<h3 class="text-center">
<?=$title?>
</h3>
-->
<div class="text-center m-2">
    <img src="<?=$dataUri?>" class="img-thumbnail" style="width: 360px;">
</div>
<!--
<div id="display-qrcode-container" class="p-1" style="text-align: center">
</div>
-->
<p class="text-center m-2"><a href="javascript:;" class="easyui-linkbutton" data-options="onClick:downloadQrcode">下载</a></p>
<p class="text-center">请用微信扫码开始相关服务</p>
<script>
    //table or canvas
    /*
    $('#display-qrcode-container').qrcode({
        title: "<?=$title?>",
        render: "canvas",
        width: 360,
        height: 360,
        text: "<?=$text?>",
        correctLevel:QRErrorCorrectLevel.M
    });
    function downloadQrcode(){
        var canvas = document.getElementById('display-qrcode-container').getElementsByTagName('canvas')[0];
        var strDataURI = canvas.toDataURL("image/png", 1);
        //var image=strDataURI.replace("image/png", "image/octet-stream");
        //window.location.href=image;
        var a = document.createElement("a");
        a.download = "<?=$title?>";
        a.href = strDataURI; 
        document.body.appendChild(a); a.click(); a.remove();
    }*/
    function downloadQrcode(){
        var a = document.createElement("a");
        a.download = "<?=$title?>";
        a.href = "<?=$dataUri?>"; 
        document.body.appendChild(a); a.click(); a.remove();
    }
</script>