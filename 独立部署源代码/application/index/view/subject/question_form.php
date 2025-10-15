<?php
use app\index\logic\Defs as IndexDefs;
?>
<style>
#input-field-control-container .input-field-control-item{
    padding: 0.5rem;
    font-size: 1rem;
    font-weight: 700;
    line-height: 2rem;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    color: #fff;
    background-color: #6c757d;
}
#question-form-container{
    width: 100%;
    height: 100%;
}
#question-form-item-group{
    max-width: 540px;
}
#question-form-item-group li.question-form-item{
    display: block;
    clear: both;
    content: "";
}
#question-form-item-group .question-form-item .question-form-item-opt{
    float: left;
    width: 20%;
}
#question-form-item-group .question-form-item .question-form-item-content{
    float: left;
    width: 80%;
}
.weui-cells label{
    margin-bottom: 0px;
}
</style>
<link rel="stylesheet" href="/static/mp/css/weui.min.css?<?=STATIC_VER?>">
<div class="form-container">
    <div class="form-body">
        <div class="clearfix" style="width:100%;height:100%;">
            <div class="float-left border-right" style="width:60%;height:100%;">
                <div id="question-form-container" class="overflow-auto">
                    <div class="table-tr-caption shadow rounded p-3">问卷表单</div>
                    <ul id="question-form-item-group" class="list-group center-block mt-3">
                    <?=$questionForm?>
                    <!--
                        <li class="list-group-item question-form-item">
                        </li>
                    -->
                    </ul>
                </div>
            </div>
            <div class="float-right" style="width:40%;height:100%;">
                <div class="table-tr-caption shadow rounded p-3">表单组件</div>
                <div id="input-field-control-container" class="mt-3 d-flex flex-wrap justify-content-start">
                    <div class="input-field-control-item m-2" data-type="input-text">
                        <span class="fa fa-text-width">文本输入框</span>
                    </div>
                    <div class="input-field-control-item m-2" data-type="input-number">
                        <span class="fa fa-text-height">数字输入框</span>
                    </div>
                    <div class="input-field-control-item m-2" data-type="input-checkbox-group">
                        <span class="fa fa-check-square-o">复选框组</span>
                    </div>
                    <div class="input-field-control-item m-2" data-type="input-radio-group">
                        <span class="fa fa-dot-circle-o">单选框组</span>
                    </div>
                    <div class="input-field-control-item m-2" data-type="input-date">
                        <span class="fa fa-calendar-o">日期输入框</span>
                    </div>
                    <div class="input-field-control-item m-2" data-type="input-datetime">
                        <span class="fa fa-calendar">时间输入框</span>
                    </div>
                    <div class="input-field-control-item m-2" data-type="input-textarea">
                        <span class="fa fa-font">多行输入框</span>
                    </div>
                </div>
                <div class="alert alert-info" role="alert">
                    <p>请选择上面控件向左侧"问卷表单"拖动</p>
                </div>
            </div>
        </div>
    </div>
    <div class="form-toolbar">
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.ok,
                    onClick:function(){
                        subjectQuestionFormModule.save(this);
                    }">保存
        </a>
        &nbsp;
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.cancel,
                    onClick:subjectQuestionFormModule.cancel">关闭
        </a>
    </div>
</div>
<script type="text/javascript">
    var inputFieldControlItemTpls = {
        'input-text':
                    `<div class="question-form-item-opt">
                        <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                        <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                    </div>
                    <div class="question-form-item-content border-left" data-type="input-text">
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__hd">
                                    <label class="weui-label">标题文字</label>
                                </div>
                                <div class="weui-cell__bd">
                                    <input
                                        class="weui-input"
                                        type="text"
                                        name="###NAME###"
                                        value=""
                                        placeholder="请输入"
                                        maxlength="256"
                                    />
                                </div>
                                <div class="weui-cell__ft">
                                </div>
                            </div>
                        </div>
                    </div>`,
        'input-number':
                    `<div class="question-form-item-opt">
                        <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                        <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                    </div>
                    <div class="question-form-item-content border-left" data-type="input-number">
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__hd">
                                    <label class="weui-label">标题文字</label>
                                </div>
                                <div class="weui-cell__bd">
                                    <input class="weui-input" type="number" name="###NAME###" value="" pattern="[0-9]*" placeholder="请输入">
                                </div>
                                <div class="weui-cell__ft">
                                </div>
                            </div>
                        </div>
                    </div>`,
        'input-checkbox-group':
                    `<div class="question-form-item-opt">
                        <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                        <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                    </div>
                    <div class="question-form-item-content border-left" data-type="input-checkbox-group">
                        <div class="weui-cells__title">标题文字</div>
                        <div class="weui-cells weui-cells_checkbox">
                            <label class="weui-cell weui-check__label">
                                <div class="weui-cell__hd">
                                    <input type="checkbox" name="###NAME###" value="选项1" class="weui-check">
                                    <i class="weui-icon-checked"></i>
                                </div>
                                <div class="weui-cell__bd">选项1</div>
                            </label>
                            <label class="weui-cell weui-check__label">
                                <div class="weui-cell__hd">
                                    <input type="checkbox" name="###NAME###" value="选项2" class="weui-check">
                                    <i class="weui-icon-checked"></i>
                                </div>
                                <div class="weui-cell__bd">选项2</div>
                            </label>
                        </div>
                    </div>`,
        'input-radio-group':
                    `<div class="question-form-item-opt">
                        <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                        <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                    </div>
                    <div class="question-form-item-content border-left" data-type="input-radio-group">
                        <div class="weui-cells__title">标题文字</div>
                        <div class="weui-cells weui-cells_radio">
                            <label class="weui-cell weui-check__label">
                                <div class="weui-cell__bd">选项1</div>
                                <div class="weui-cell__ft">
                                    <input type="radio" name="###NAME###" value="选项1" class="weui-check">
                                    <span class="weui-icon-checked"></span>
                                </div>
                            </label>
                            <label class="weui-cell weui-check__label">
                                <div class="weui-cell__bd">选项2</div>
                                <div class="weui-cell__ft">
                                    <input type="radio" name="###NAME###" value="选项2" class="weui-check">
                                    <span class="weui-icon-checked"></span>
                                </div>
                            </label>
                        </div>
                    </div>`,
        'input-date':
                    `<div class="question-form-item-opt">
                        <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                        <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                    </div>
                    <div class="question-form-item-content border-left" data-type="input-date">
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__hd">
                                    <label for="" class="weui-label">标题文字</label>
                                </div>
                                <div class="weui-cell__bd">
                                    <input type="date" name="###NAME###" value="" class="weui-input" placeholder="请输入">
                                </div>
                            </div>
                        </div>
                    </div>`,
        'input-datetime':
                    `<div class="question-form-item-opt">
                        <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                        <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                    </div>
                    <div class="question-form-item-content border-left" data-type="input-datetime">
                        <div class="weui-cells">
                            <div class="weui-cell">
                                <div class="weui-cell__hd">
                                    <label for="" class="weui-label">标题文字</label>
                                </div>
                                <div class="weui-cell__bd">
                                    <input type="datetime-local" name="###NAME###" value="" class="weui-input" placeholder="请输入">
                                </div>
                            </div>
                        </div>
                    </div>`,
        'input-textarea':
                    `<div class="question-form-item-opt">
                        <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                        <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                    </div>
                    <div class="question-form-item-content border-left" data-type="input-textarea">
                        <div class="weui-cells__title">标题文字</div>
                        <div class="weui-cells weui-cells_form">
                            <div class="weui-cell">
                                <div class="weui-cell__bd">
                                    <textarea name="###NAME###" class="weui-textarea" placeholder="请输入" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>`
    };
    var subjectQuestionFormModule = {
        dialog:'#globel-dialog2-div',
        init:function(){
            /*拖*/
            $('#input-field-control-container .input-field-control-item').draggable({
                revert: true,
                proxy: 'clone',
                cursor: 'move',
                onStartDrag:function(){
                    $(this).draggable('proxy').css('z-index', 10);
                },
                onStopDrag:function(){
                    //$(this).draggable('options').cursor = 'move';
                }
            });
            /*放*/
            $('#question-form-container').droppable({
                accept: '#input-field-control-container .input-field-control-item',
                onDragEnter:function(e, source){
                    ///$(source).draggable('options').cursor = 'pointer';
                },
                onDragLeave:function(e, source){
                    //$(source).draggable('options').cursor = 'move';
                },
                onDrop:function(e, source){
                    var type = $(source).data('type');
                    subjectQuestionFormModule.addFieldItem(type);
                    //$(source).draggable('options').cursor = 'move';
                }
            });
        },
        addFieldItem:function(type){
            if(!inputFieldControlItemTpls[type]){
                return;
            }
            var name = QT.util.uuid();
            if(type == 'input-checkbox-group'){
                name += '[]';
            }
            var itemHtml = inputFieldControlItemTpls[type];
            itemHtml = itemHtml.replace('###NAME###', name);

            var $item = $('<li class="list-group-item question-form-item"></li>');
            $item.append($(itemHtml));
            $('#question-form-item-group').append($item);
        },
        editFieldItem:function(target){
            var that = subjectQuestionFormModule;
            var $itemContent = $(target).parent().next();
            var title = '';
            var editContent = '';
            //初始值获取
            var fieldControlItemType = $itemContent.data('type');
            console.log('control item type', fieldControlItemType);
            switch(fieldControlItemType){
                case 'input-text':
                case 'input-date':
                case 'input-datetime':
                case 'input-number':
                case 'input-textarea':
                {
                    title = $itemContent.find('.weui-label').text();
                    editContent = 
                                `<form class="p-3">
                                    <div class="text-center">
                                        <input class="easyui-textbox" name="title" value="${title}" label="标题"
                                            data-options="required:true,multiline:false,width:450,validType:['length[1,60]']">
                                    </div>
                                </form>`;
                }break;
                case 'input-checkbox-group':
                case 'input-radio-group':
                {
                    title = $itemContent.find('.weui-cells__title').text();
                    var options = [];
                    $itemContent.find('.weui-cell__bd').each(function(){
                        options.push($.trim($(this).text()));
                    });
                    var optionsText = '';
                    if(options.length > 0){
                        optionsText = options.join("\r\n");
                    }
                    editContent = 
                                `<form class="p-3">
                                    <div class="text-center m-1">
                                        <input class="easyui-textbox" name="title" value="${title}" label="标题"
                                            data-options="required:true,multiline:false,width:450,validType:['length[1,60]']">
                                    </div>
                                    <div class="text-center m-1">
                                        <textarea class="easyui-textbox" name="options" label="选项"
                                            data-options="required:true,multiline:true,width:450,height:200,validType:['length[1,256]']">${optionsText}</textarea>
                                    </div>
                                    <div class="text-center">
                                        <span class="bg-info m-1">提示：每个选项占用一行</span>
                                    </div>
                                </form>`;
                }break;
                default:;
            }
            //编辑对话框
            $(that.dialog).dialog('clear');
            $(that.dialog).dialog({
                title: '编辑输入域控件',
                width: <?=$loginMobile?"'100%'":600?>,
                height: '60%',
                content: editContent,
                modal: true,
                cache:false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        var $form = $(that.dialog).find('form').eq(0);
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $form.serializeArray().forEach(function(item){
                            var name = item['name'];
                            var value = item['value'];
                            if(name == 'title'){
                                //标题文字
                                switch(fieldControlItemType){
                                    case 'input-text':
                                    case 'input-date':
                                    case 'input-datetime':
                                    case 'input-number':
                                    {
                                        $itemContent.find('.weui-label').text(value);
                                    }break;
                                    case 'input-checkbox-group':
                                    case 'input-radio-group':
                                    case 'input-textarea':
                                    {
                                        $itemContent.find('.weui-cells__title').text(value);
                                    }break;
                                    default:;
                                }
                            }else if(name == 'options'){
                                var options = value.split("\r\n");
                                var $cells = $itemContent.find('.weui-cells');
                                $cells.html('');
                                switch(fieldControlItemType){
                                    case 'input-checkbox-group':
                                    {
                                        var name = QT.util.uuid() + '[]';
                                        options.forEach(function(option){
                                            $cells.append($(`
                                                <label class="weui-cell weui-check__label">
                                                    <div class="weui-cell__hd">
                                                        <input type="checkbox" name="${name}" value="${option}" class="weui-check">
                                                        <i class="weui-icon-checked"></i>
                                                    </div>
                                                    <div class="weui-cell__bd">${option}</div>
                                                </label>
                                            `));
                                        });
                                    }break;
                                    case 'input-radio-group':
                                    {
                                        var name = QT.util.uuid();
                                        options.forEach(function(option){
                                            $cells.append($(`
                                                <label class="weui-cell weui-check__label">
                                                    <div class="weui-cell__bd">${option}</div>
                                                    <div class="weui-cell__ft">
                                                        <input type="radio" name="${name}" value="${option}" class="weui-check">
                                                        <span class="weui-icon-checked"></span>
                                                    </div>
                                                </label>
                                            `));
                                        });
                                    }break;
                                    default:;
                                }
                            }
                        });
                        $(that.dialog).dialog('close');
                    }
                },{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        removeFieldItem:function(that){
            $(that).closest('.question-form-item').remove();
        },
        save:function(that){
            var that = subjectQuestionFormModule;
            var items = [];
            $('#question-form-item-group .question-form-item .question-form-item-content').each(function(){
                var type = $(this).data('type');
                var html = $(this).html();
                items.push({
                    type: type,
                    html: html
                });
            });
            var content = '';
            if(items.length > 0){
                content = JSON.stringify(items);
            }
            var href = '<?=$urlHrefs['save']?>';
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, content, function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                }
            }, 'json');
        },
        cancel:function(){
            $(this).closest('.window-body').dialog('close');
        }
    };
    subjectQuestionFormModule.init();
</script>