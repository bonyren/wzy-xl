<script type="text/javascript">
    $(document).ajaxError(function (event, xhr, ajaxOptions){
        switch (xhr.status) {
            case 401: // Unauthorized
                // Take action, referencing XMLHttpRequest.responseText as needed.
                window.location.assign('<?=url('index/Index/public_login')?>');
                break;
            case 400:
                //try to close the showing progress bar
                $.app.method.alertError(null, "错误的请求");
                if(ajaxOptions.type == 'POST'){
                    $.messager.progress('close');
                }
                break;
            case 404:
                //try to close the showing progress bar
                $.app.method.alertError(null, "资源不存在或已经删除");
                if(ajaxOptions.type == 'POST'){
                    $.messager.progress('close');
                }
                break;
            case 500:
                //try to close the showing progress bar
                $.app.method.alertError(null, `系统内部错误, ${xhr.responseText}，请联系管理员(wzycoding@qq.com)`);
                if(ajaxOptions.type == 'POST'){
                    $.messager.progress('close');
                }
                break; 
        }
    });
</script>