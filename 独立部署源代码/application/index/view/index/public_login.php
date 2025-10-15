<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="renderer" content="Blink|webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE9">
    <meta name="author" content="szsupernan">
    <title><?=systemSetting('general_site_title')?></title>
    <?php
    include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "head.php";
    include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "js.php";
    ?>
    <style>
        html {
            height: 100%;
        }
        body {
            background: url(/static/img/loginbg.jpg) 0% 0% / cover no-repeat;
            position: static;
        }
        .copyright{
            position: fixed;
            bottom:0px;
            width:100%;
        }
        #particles-js {
            width: 100%;
            height: 100%;
            position: relative;
            /*
            background-image: url('/static/img/loginbg.jpg');
            */
            background-position: 50% 50%;
            background-size: cover;
            background-repeat: no-repeat;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="loader"></div>
    <div class="easyui-dialog" data-options="title:'请登录您的账号',
        width:<?=$loginMobile?"'100%'":'450'?>,
        closable:false,
        border:true,
        iconCls:'fa fa-user',
        buttons:[
            {text:'登录',iconCls:'fa fa-sign-in',handler:submit}
        ],
        onOpen:function(){
            init();
        }
        ">
        <form id='login-form' action="<?=$urls['login']?>" method="post">
            <div class="bg-light text-center p-2">
                <h3><img src="<?=systemSetting('general_organisation_logo')?>" style="height: 32px;">&nbsp;<?=systemSetting('general_organisation_name')?></h3>
            </div>
            <div class="p-2">
                <input class="easyui-textbox" id="login_account" name="username" data-options="required:true,
                    label:'用户名',
                    validType:{length:[2,20]},
                    tipPosition:'bottom',
                    width:'100%',
                    labelWidth:60
                    ">
            </div>
            <div class="p-2">
                <input class="easyui-passwordbox" id="login_password"  name="password" data-options="required:true,
                    label:'密码',
                    validType:{length:[6,20]},
                    tipPosition:'bottom',
                    checkInterval:50,
                    width:'100%',
                    labelWidth:60
                    ">
            </div>
            <?php if($login_captcha_enable){ ?>
            <div class="p-2">
                <input class="easyui-textbox" id="login_captcha" name="captcha" data-options="required:true,
                    label:'验证码',
                    validType:{length:[4,4]},
                    tipPosition:'bottom',
                    width:200,
                    labelWidth:60" />
                <img id="login-captcha-img" align="top" onclick="changeCode();return false;"
                           src="<?=$urls['captcha']?>" title="刷新验证码" style="cursor:pointer;border:1px solid #eeeeee;">
            </div>
            <?php } ?>
            <div class="p-3">
                <input class="easyui-checkbox" name="auto_login" value="1" data-options="label:'自动登录',labelPosition:'after'">
            </div>
        </form>
        <footer class="p-2" style="font-size: 12px;">
            测试账号, 用户名: <span class="text-primary">test</span> 密码: <span class="text-primary">123456</span><br />
            意向咨询，微信: <span class="text-primary">wzyer_com</span> 邮件: <a href="mailto:wzycoding@qq.com">wzycoding@qq.com</a>
        </footer>
    </div>
    <?php
    include APP_PATH . "index" . DS . "view" . DS . "common" . DS . "foot.php";
    ?>
    <div class="text-center mb-1 copyright">
        <span class="text-dark"><?=systemSetting('general_power_by_text')?></span><a href="https://beian.miit.gov.cn" target="_blank" class="ml-3 text-dark"><?=systemSetting('general_site_beian')?></a>
    </div>
    <script type="text/javascript">
        function changeCode(){
            var that = document.getElementById('login-captcha-img');
            if (that) {
                var src = that.src;
                src = src.replace(/&salt=[0-9.]+/,'');
                that.src = src + '&salt=' + Math.random();
            }
        }
        function init(){
            <?php if($login_captcha_enable){ ?>
                changeCode();
            <?php } ?>
            $('.loader').fadeOut();
        }
        function submit(){
            var isValid = $('#login-form').form('validate');
            if(!isValid){
                return false;
            }
            $.messager.progress({text:'处理中，请稍候...'});
            //为了兼容微信浏览器，所以采用网页提交
            $('#login-form').submit();
        }
        setTimeout(function() {
            $('#login_account').textbox('textbox').focus();
        }, 500);
        $(document).keyup(function(event){
            if(event.keyCode == 13){
                submit();
            }
        });
        (function(){
            particlesJS('particles-js',
                {
                    "particles": {
                        "number": {
                            "value": 40,
                            "density": {
                                "enable": true,
                                "value_area": 800
                            }
                        },
                        "color": {
                            "value": "#ffffff"
                        },
                        "shape": {
                            "type": "circle",
                            "stroke": {
                                "width": 0,
                                "color": "#000000"
                            },
                            "polygon": {
                                "nb_sides": 5
                            },
                            "image": {
                                "src": "img/github.svg",
                                "width": 100,
                                "height": 100
                            }
                        },
                        "opacity": {
                            "value": 0.7,
                            "random": false,
                            "anim": {
                                "enable": false,
                                "speed": 1,
                                "opacity_min": 0.1,
                                "sync": false
                            }
                        },
                        "size": {
                            "value": 3,
                            "random": true,
                            "anim": {
                                "enable": false,
                                "speed": 40,
                                "size_min": 0.1,
                                "sync": false
                            }
                        },
                        "line_linked": {
                            "enable": true,
                            "distance": 150,
                            "color": "#ffffff",
                            "opacity": 0.6,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": 6,
                            "direction": "none",
                            "random": false,
                            "straight": false,
                            "out_mode": "out",
                            "bounce": false,
                            "attract": {
                                "enable": false,
                                "rotateX": 600,
                                "rotateY": 1200
                            }
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": true,
                                "mode": "grab"
                            },
                            "onclick": {
                                "enable": true,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "grab": {
                                "distance": 200,
                                "line_linked": {
                                    "opacity": 1
                                }
                            },
                            "bubble": {
                                "distance": 400,
                                "size": 40,
                                "duration": 2,
                                "opacity": 8,
                                "speed": 3
                            },
                            "repulse": {
                                "distance": 200,
                                "duration": 0.4
                            },
                            "push": {
                                "particles_nb": 4
                            },
                            "remove": {
                                "particles_nb": 2
                            }
                        }
                    },
                    "retina_detect": false
                }
            );
        })();
    </script>
</body>
</html>