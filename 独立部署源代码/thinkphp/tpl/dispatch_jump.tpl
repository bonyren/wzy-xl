{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>跳转提示</title>
    <link rel='shortcut icon' href='/static/favicon.ico' />
    <link rel="stylesheet" type="text/css" href="/static/css/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/static/js/easyui/themes/default/easyui.css"/>
    <script type="text/javascript" src="/static/js/easyui/jquery.min.js"></script>
    <script type="text/javascript" src="/static/js/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/static/js/easyui/locale/easyui-lang-zh_CN.js"></script>
    <style type="text/css">
        a{color:#08c;text-decoration:none}
        a:hover,a:focus{color:#005580;text-decoration:underline}
    </style>
</head>
<body style="background-color: #B7B7B7;">
    <div class="easyui-window" title="提示" style="width:380px;height:200px" data-options="iconCls:'fa fa-info',
                                                                        modal:false,
                                                                        resizable:false,
                                                                        collapsible:false,
                                                                        minimizable:false,
                                                                        maximizable:false,
                                                                        closable:false">
        <div style="font-size:18px;text-align:center;margin-top:40px">
            <?php switch ($code){?>
            <?php case 1:?>
            <p class="success"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
            <?php case 0:?>
            <p class="error"><?php echo(strip_tags($msg));?></p>
            <?php break;?>
        <?php } ?>
            <p style="font-size:12px">
            页面自动 <a id="href" href="<?php echo($url);?>">跳转</a> 等待时间： <b id="wait"><?php echo($wait);?></b>
            </p>
        </div>
    </div>
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),
                href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                }
            }, 1000);
        })();
    </script>
</body>
</html>
