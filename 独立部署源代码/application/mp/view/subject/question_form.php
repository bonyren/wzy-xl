<?php
use app\Defs;
?>
<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<link rel="stylesheet" href="/static/mp.ionic/css/weui.min.css?<?=STATIC_VER?>">
<style>
    .weui-input,.weui-textarea{
        background-color: beige;
    }
</style>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">测前调查</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <form id="subject-question-form" method="POST" enctype="application/x-www-form-urlencoded">
        <?=$questionForm?>
    </form>
</ion-content>
<ion-footer>
  <ion-toolbar>
    <ion-buttons slot="end">
        <ion-button id="submitBtn" color="action" fill="solid">提交保存</ion-button>
    </ion-buttons>
  </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
    var questionAnswer = <?=$questionAnswer?>;
    $('input').each(function(index, elem){
        var name = $(elem).attr('name');
        var type = $(elem).attr('type');
        var value = $(elem).attr('value');
        if(type == 'text' || type == 'date' || type == 'datetime-local' || type == 'number'){
            questionAnswer.forEach(function(item){
                if(item['name'] == name){
                    $(elem).val(item['value']);
                }
            });
        }else if(type == 'checkbox' || type == 'radio'){
            questionAnswer.forEach(function(item){
                if(item['name'] == name && item['value'] == value){
                    $(elem).prop('checked', true);
                }
            });
        }
    });
    $('textarea').each(function(index, elem){
        var name = $(elem).attr('name');
        questionAnswer.forEach(function(item){
            if(item['name'] == name){
                $(elem).val(item['value']);
            }
        });
    });
    /*******************************************************************/
    $('#submitBtn').on('click', function(e){
        //1.check all input fields
        var allFieldOk = true;
        $('input').each(function(index, elem){
            var name = $(elem).attr('name');
            var type = $(elem).attr('type');
            var value = $(elem).val();
            if(type == 'text' || type == 'date' || type == 'datetime-local' || type == 'number'){
                if($.trim(value) == ''){
                    allFieldOk = false;
                    return false;
                }
            }else if(type == 'checkbox' || type == 'radio'){
                if($(`input[name='${name}']:checked`).length == 0){
                    allFieldOk = false;
                    return false;
                }
            }
        });
        $('textarea').each(function(index, elem){
            var value = $(elem).val();
            if($.trim(value) == ''){
                allFieldOk = false;
                return false;
            }
        });
        if(!allFieldOk){
            TOAST.warning('请填写完整所有数据');
            return false;
        }
        //2.submit to remote
        var answer = JSON.stringify($('#subject-question-form').serializeArray());
        LOADING.show('处理中').then(()=>{
            $.post('<?=url('mp/Subject/question_form', ['order_no'=>$order['order_no']])?>',{
                question_answer: answer
            }, function(res){
                LOADING.hide();
                if (!res.code) {
                    TOAST.error(res.msg);
                } else {
                    var test_url = '<?=url('mp/Subject/test', ['order_no'=>$order['order_no'], 'skip_question'=>1])?>';
                    window.location.replace(test_url);
                }
            },'json');
        });

        return false;
    });
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>