<?php
use app\Defs;
?>
<link rel="stylesheet" href="/static/mp/css/weui.min.css?<?=STATIC_VER?>">
<style>
    .weui-cells{
        margin-top: 0;
    }
    .weui-input,.weui-textarea{
        background-color: beige;
    }
</style>
<div>
    <div class="center-block border mt-1" style="max-width: 540px;">
        <form id="subject-question-form" method="POST" enctype="application/x-www-form-urlencoded">
            <?=$questionForm?>
        </form>
    </div>
</div>
<script type="text/javascript">
    var questionAnswer = <?=$questionAnswer?>;
    $('input').each(function(index, elem){
        $(this).prop('disabled', true);
        var name = $(elem).attr('name');
        var type = $(elem).attr('type');
        var value = $(elem).attr('value');
        if(type == 'text' || type == 'date' || type == 'datetime-local'|| type == 'number'){
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
</script>