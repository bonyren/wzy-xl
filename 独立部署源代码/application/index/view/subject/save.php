<?php
use app\index\logic\Defs as IndexDefs;
?>

<div class="form-container">
    <form id="subject-add-form" class="form-body">
        <table class="table-form" cellpadding="5">
            <tr>
                <td class="field-label" style="width: 10%;">量表图片(最佳尺寸750x500像素)</td>
                <td class="field-input" style="width: 50%;">
                    <?=action('Figure/save', ['inputCtrlName'=>'formData[image_url]', 'figureUrl'=>$formData['image_url'], 'width'=>120], 'widget')?>
                </td>
                <td class="field-label" style="width: 10%;">轮播图(最佳尺寸750x500像素)</td>
                <td class="field-input" style="width: 30%;">
                    <?=action('Figure/save', ['inputCtrlName'=>'formData[banner_img]', 'figureUrl'=>$formData['banner_img'], 'width'=>200], 'widget')?>
                </td>
            </tr>
            <tr>
                <td class="field-label">量表名称</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="formData[name]" value="<?=$formData['name']?>" data-options="required:true,width:350,validType:['length[2,128]']">
                </td>
                <td class="field-label">量表副标题</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="formData[subtitle]" value="<?=$formData['subtitle']?>" data-options="required:false,width:350,validType:['length[2,256]']">
                </td>
            </tr>
            <tr>
                <td class="field-label">分类(多选)</td>
                <td class="field-input">
                    <select class="easyui-combobox" name="formData[category_ids]" style="width:350px;"
                            data-options="prompt:'请选择分类',required:true,editable:false,multiple:true,multivalue:false,value:'<?=$formData['category_value']?>'">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?=$category['id']?>"><?=$category['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td class="field-label">价格</td>
                <td class="field-input">
                    <input class="easyui-numberbox" name="formData[current_price]" value="<?=$formData['current_price']?>" style="width:350px;"
                           data-options="required:true,min:0,max:10000,precision:2">（元）
                </td>
            </tr>
            <tr>
                <td class="field-label">预期完成时间</td>
                <td class="field-input">
                    <input class="easyui-numberbox" name="formData[expect_finish_time]" value="<?=$formData['expect_finish_time']?>" style="width:350px;"
                           data-options="required:true,min:0,max:60">（分钟）
                </td>
                <td class="field-label">答题后退</td>
                <td class="field-input">
                    <select class="easyui-combobox" name="formData[test_allow_back]" data-options="editable:false,panelHeight:'auto',value:'<?=$formData['test_allow_back']?>'" style="width:120px;">
                        <option value="0">不允许</option>
                        <option value="1">允许</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="field-label">量表介绍</td>
                <td class="field-input">
                    <div class="easyui-texteditor" name="formData[subject_desc]"
                         data-options="width:'100%',height:500,validType:['length[1,1000]'],toolbar:['bold','italic','strikethrough','underline','-','justifyleft','justifycenter','justifyright','justifyfull','-','insertorderedlist','insertunorderedlist','insertimage']">
                        <?=htmlspecialchars_decode($formData['subject_desc'])?>
                    </div>
                </td>
                <td colspan="2" valign="top">
                    <table class="table-form" cellpadding="5">
                        <tr>
                            <td class="field-label" style="width: 150px;">测前提示</td>
                            <td class="field-input">
                                <textarea class="easyui-textbox" name="formData[subject_tip]"
                                    data-options="required:false,
                                        width:'100%',
                                        height:60,
                                        multiline:true,
                                        validType:['length[1,1024]'],
                                        disabled:false,
                                        prompt:'请输入用户开始测评前提示用户的信息'"><?=$formData['subject_tip']?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">用户查看报告</td>
                            <td class="field-input">
                                <select class="easyui-combobox" name="formData[test_allow_view_report]" 
                                    data-options="editable:false,panelHeight:'auto',value:'<?=$formData['test_allow_view_report']?>'" style="width:120px;">
                                    <option value="1">允许</option>
                                    <option value="0">不允许</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">是否允许答题为空，直接跳过</td>
                            <td class="field-input">
                                <select class="easyui-combobox" name="formData[test_allow_answer_empty]" 
                                    data-options="editable:false,panelHeight:'auto',value:'<?=$formData['test_allow_answer_empty']?>'" style="width:120px;">
                                    <option value="0">不允许</option>
                                    <option value="1">允许</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">状态</td>
                            <td class="field-input">
                                <select class="easyui-combobox" name="formData[status]" data-options="editable:false,panelHeight:'auto',value:'<?=$formData['status']?>'" style="width:120px;">
                                    <?php foreach(IndexDefs::$entityStatusDefs as $key=>$label){ ?>
                                    <option value="<?=$key?>"><?=$label?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">测评人数(展示用)</td>
                            <td class="field-input">
                                <input class="easyui-numberbox" name="formData[participants_show]" value="<?=$formData['participants_show']?>" style="width:350px;"
                                        data-options="required:true,min:0,max:100000000">
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">评价</td>
                            <td class="field-input">
                                <input id="subject-rating-input" name="formData[rating]" type="text" value="<?=$formData['rating']?>" />
                            </td>
                        </tr>
                        <tr>
                            <td class="field-label">小程序</td>
                            <td class="field-input">
                                <input class="easyui-checkbox" name="formData[uni_app]" 
                                    data-options="label:'',labelWidth:100,labelPosition:'after',checked:<?=$formData['uni_app']?'true':'false'?>" value="1">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    <div class="form-toolbar">
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.ok,
                    onClick:function(){
                        subjectSaveModule.save(this);
                    }">保存
        </a>
        &nbsp;
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.cancel,
                    onClick:subjectSaveModule.cancel">关闭
        </a>
    </div>
</div>
<script type="text/javascript">
    var subjectSaveModule = {
        savedFlag:false,
        init:function(){
            $('#subject-rating-input').rating(
                {min:0, max:10, step:1, size:'md', showCaption:true, animate:true, displayOnly:false}
            );
        },
        save:function(that){
            var isValid = $('#subject-add-form').form('validate');
            if(!isValid){
                return;
            }
            var href = '<?=$urlHrefs['save']?>';
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, $('#subject-add-form').serialize(), function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                    subjectSaveModule.savedFlag = true;
                    <?php if(!$id){ ?>
                        $(that).closest('.window-body').dialog('close');
                    <?php } ?>
                }
            }, 'json');
        },
        cancel:function(){
            $(this).closest('.window-body').dialog('close');
        },
        upload:function(){
            $.app.method.uploadImage('<?=url('Upload/uploadImage')?>',function(res){
                if(res.code){
                    $('#subject_image_preview').attr('src', res.data.absolute_url);
                    $('#subject_image_url').val(res.data.absolute_url);
                }else{
                    $.app.method.alertError(null, res.msg);
                }
            });
        }
    };
    subjectSaveModule.init();
</script>