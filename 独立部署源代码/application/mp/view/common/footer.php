<script type="text/javascript">
    $(function(){
        document.documentElement.style.fontSize = '18px';
        if(window.history.length == 1){
            $('#nav-top-buttons').remove();
        }
    });
    $(document).ajaxError(function (event, xhr, ajaxOptions){
        switch (xhr.status) {
            case 401: // Unauthorized
                // Take action, referencing XMLHttpRequest.responseText as needed.
                if(/[\?&]?enter_scene=qrcode/.test(window.location.href)){
                    var url = window.location.href;
                    url = url.replace(/[\?&]?enter_scene=qrcode/, '');
                    url = url.replace(/[\?&]?salt=[0-9\.]*/, '');
                    url = GLOBAL.func.addUrlParam(url, 'salt', Math.random());
                    window.location.assign(url);
                }else{
                    TOAST.error('未授权的访问');
                }
                break;
            case 400:
                //try to close the showing progress bar
                TOAST.error('错误的请求');
                break;
            case 403:
                //try to close the showing progress bar
                TOAST.error('禁止访问');
                break;
            case 404:
                //try to close the showing progress bar
                TOAST.error('资源不存在或已经删除');
                break;
            case 500:
                TOAST.error(`系统内部错误, ${xhr.responseText}，请联系管理员(wzycoding@qq.com)`);
                break; 
        }
    });
</script>
<script>
    var LOADING = {
        _loading: false,
        isLoading(){
            return LOADING._loading;
        },
        show(message){
            LOADING._loading = true;
            var ele = document.getElementById('ion-loading');
            ele.message = message;
            return ele.present();
        },
        hide(){
            LOADING._loading = false;
            var ele = document.getElementById('ion-loading');
            return ele.dismiss();
        }
    };
    var TOAST = {
        tip(message){
            var ele = document.getElementById('ion-toast');
            //ele.color = 'light';
            ele.color = 'primary';
            ele.message = message;
            ele.position = 'middle';
            return ele.present();
        },
        success(message){
            var ele = document.getElementById('ion-toast');
            ele.color = 'success';
            ele.message = message;
            ele.position = 'middle';
            return ele.present();
        },
        warning(message){
            var ele = document.getElementById('ion-toast');
            ele.color = 'warning';
            ele.message = message;
            ele.position = 'middle';
            return ele.present();
        },
        error(message){
            var ele = document.getElementById('ion-toast');
            ele.color = 'danger';
            ele.message = message;
            /*
            ele.buttons = [
                {
                    text: '关闭',
                    role: 'cancel',
                },
            ];*/
            ele.position = 'middle';
            return ele.present();
        }
    };
    var ALERT = {
        _running: false,
        isRunning(){
            return LOADING._running;
        },
        _callback: null,
        tip(message, callback, btnText=''){
            ALERT._running = true;
            ALERT._callback = callback;
            var ele = document.getElementById('ion-alert');
            ele.header = '提示';
            ele.subHeader = message;
            ele.message = '';
            ele.buttons = [{
                    text: btnText || '确定',
                    role: 'ok',
                    handler: () => {
                    },
                }];
            ele.present();
        },
        confirm(message, callback){
            ALERT._running = true;
            ALERT._callback = callback;
            var ele = document.getElementById('ion-alert');
            ele.header = '提示';
            ele.subHeader = message;
            ele.message = '';
            ele.buttons = [{
                    text: '取消',
                    role: 'cancel',
                    handler: () => {
                    },
                },{
                    text: '确认',
                    role: 'confirm',
                    handler: () => {
                    },
                },
            ];
            return ele.present();
        }
    };

</script>
<ion-toast id="ion-toast" is-open="false" icon="alert-circle-outline" animated="true" duration="3000" translucent="true" header="提示" message="Hello World!"></ion-toast>
<ion-loading id="ion-loading" is-open="false" message="正在加载" show-backdrop="true"></ion-loading>
<ion-alert id="ion-alert" header="Alert" sub-header="Important message" message="This is an alert!"></ion-alert>
<script>
    document.getElementById('ion-alert').addEventListener('ionAlertDidDismiss', function(evt){
        ALERT._running = false;
        var role = evt.detail.role;
        ALERT._callback && ALERT._callback(role);
        ALERT._callback = null; 
    });
</script>
</body>
</html>