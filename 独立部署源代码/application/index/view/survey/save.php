<?php
use app\index\logic\Defs as IndexDefs;
?>
<div class="form-container">
    <form id="<?=FORM_ID?>" class="form-body" method="post">
        <table class="table-form" cellpadding="5">
            <tr>
                <td class="field-label" style="width: 100px;">图片(最佳尺寸750x370)</td>
                <td class="field-input">
                    <?=action('Figure/save', ['inputCtrlName'=>'formData[banner]', 'figureUrl'=>$formData['banner'], 'width'=>120], 'widget')?>
                </td>
            </tr>
            <tr>
                <td class="field-label">名称</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="formData[name]" value="<?=$formData['name']?>" 
                        data-options="required:true,width:'100%',validType:['length[2,64]']">
                </td>
            </tr>
            <tr>
                <td class="field-label">量表</td>
                <td class="field-input">
                    <?=action('Selector/save', [
                        'inputCtrlName'=>'formData[subjects]',
                        'inputCtrlValue'=>$formData['subjects'],
                        'dbTable'=>'subject',
                        'labelField'=>'name',
                        'valueField'=>'id',
                        'selectUrl'=>url('index/Subject/index'),
                        'multiple'=>true,
                        'readonly'=>false
                    ], 'widget')?>
                </td>
            </tr>
            <tr>
                <td class="field-label">介绍</td>
                <td class="field-input">
                    <textarea class="easyui-textbox" name="formData[description]"
                        data-options="width:'100%',height:50,multiline:true,validType:['length[1,1024]']"><?=$formData['description']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="field-label">付费策略</td>
                <td class="field-input">
                    <select class="easyui-combobox" name="formData[cfg_free]" data-options="editable:false,panelHeight:'auto',value:'<?=$formData['cfg_free']?>'" style="width:160px;">
                        <option value="1">全部量表免费</option>
                        <option value="0">按照量表配置</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field-label">录入个人资料</td>
                <td class="field-input">
                    <select id="cfg-enter-personal-data-combobox" class="easyui-combobox" name="formData[cfg_enter_personal_data]" 
                        data-options="editable:false,panelHeight:'auto',value:'<?=$formData['cfg_enter_personal_data']?>',onChange:<?=JVAR?>.onCfgEnterPersonalDataChange" 
                            style="width:160px;">
                        <option value="1">普查前录入</option>
                        <option value="0">不录入资料</option>
                    </select>
                </td>
            </tr>
            <tr id="cfg-personal-data-section">
                <td class="field-label" style="width: 100px;">个人资料项目</td>
                <td class="field-input">
                    <ul class="easyui-datalist" id="cfg-personal-data" 
                        data-options="checkbox:true,lines:true,selectOnCheck:false,checkOnSelect:false" style="width:250px;height:250px">
                        <?php foreach(IndexDefs::SURVEY_ENTER_PERSONAL_DATA_ITEMS as $key=>$label){ ?>
                            <li value="<?=$key?>"><?=$label?></li>
                        <?php } ?>
                    </ul>
                    <input id="cfg-personal-data-input" type="hidden" name="formData[cfg_personal_data]" value="">
                </td>
            </tr>
            <tr>
                <td class="field-label">用户查看报告</td>
                <td class="field-input">
                    <select class="easyui-combobox" name="formData[cfg_view_report]" 
                        data-options="editable:false,panelHeight:'auto',value:'<?=$formData['cfg_view_report']?>'" 
                            style="width:160px;">
                        <option value="1">允许</option>
                        <option value="0">不允许</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field-label">状态</td>
                <td class="field-input">
                    <select class="easyui-combobox" name="formData[status]" data-options="editable:false,panelHeight:'auto',value:'<?=$formData['status']?>'" style="width:160px;">
                        <?php foreach(IndexDefs::$entityStatusDefs as $key=>$label){ ?>
                        <option value="<?=$key?>"><?=$label?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
    <div class="form-toolbar">
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.ok,
                    onClick:function(){
                        <?=JVAR?> .save();
                    }">确定
        </a>
        &nbsp;
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.cancel,
                    onClick:function(){
                        <?=JVAR?> .cancel();
                    }">取消
        </a>
    </div>
</div>
<script>
    var <?=JVAR?> = {
        form:'#<?=FORM_ID?>',
        init:function(){
            //个人资料项目
            var personalDataChecked = [];
            var personalDataCheckedStr = '<?=$formData['cfg_personal_data']?>';
            if($.trim(personalDataCheckedStr)){
                personalDataChecked = personalDataCheckedStr.split(',');
            }
            var rows = $('#cfg-personal-data').datalist('getRows');
            rows.forEach(function(row, index){
                if(personalDataChecked.indexOf(row.value) != -1){
                    $('#cfg-personal-data').datalist('checkRow', index);
                }      
            });
            //是否填写个人资料
            var personalDataEnter = <?=$formData['cfg_enter_personal_data']?>;
            if(personalDataEnter){
                $('#cfg-personal-data-section').show();   
            }else{
                $('#cfg-personal-data-section').hide();
            }
        },
        onCfgEnterPersonalDataChange:function(newValue, oldValue){
            if(newValue == 1){
                $('#cfg-personal-data-section').show();
            }else{
                $('#cfg-personal-data-section').hide();
            }
        },
        save:function(){
            var that = this;
            var isValid = $(that.form).form('validate');
            if(!isValid){
                return false;
            }
            var enterPersonalData = $('#cfg-enter-personal-data-combobox').combobox('getValue');
            //个人资料项目
            var personalDataItems = [];
            var personalDataIRows = $('#cfg-personal-data').datalist('getChecked');
            personalDataIRows.forEach(function(item, index){
                personalDataItems.push(item.value);
            });
            if(parseInt(enterPersonalData) && personalDataItems.length == 0){
                $.app.method.alertError(null, '请选择个人资料项目');
                return;
            }
            $('#cfg-personal-data-input').val(personalDataItems.join(','));
            //
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Survey/save', ['id'=>$id])?>', $(that.form).serialize(), function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    //关闭对话框
                    $.app.method.tip('提示', res.msg, 'info');
                    <?php if(!empty($_GET['callback_submit'])){ ?>
                        eval('<?=$_GET['callback_submit']?>');
                    <?php } ?>
                }
            }, 'json');
        },
        cancel:function(){
            <?php if(!empty($_GET['callback_cancel'])){ ?>
                eval('<?=$_GET['callback_cancel']?>');
            <?php } ?>
        }
    };
    setTimeout(function(){
        <?=JVAR?>.init();
    }, 100);
</script>