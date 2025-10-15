<?php
use app\index\logic\Defs as IndexDefs;
?>
<style>
    .textbox textarea.textbox-text{
        white-space:pre-wrap;
    }
</style>
<form method="post" style="height: 100%;">
<div class="easyui-tabs" data-options="fit:true,tabPosition:'left',justified:true,border:false,tabWidth:100,headerWidth:100">
    <div title="基本信息" data-options="cache:false,iconCls:'fa fa-circle',border:false">
        <table class="table-form" cellpadding="5">
            <tr>
                <td class="field-label" style="width: 200px;">头像(最佳尺寸180x180)</td>
                <td class="field-input">
                    <?=action('Figure/save', ['inputCtrlName'=>'infos[workimg_url]', 'figureUrl'=>$infos['workimg_url'], 'width'=>120], 'widget')?>
                </td>
            </tr>
            <tr>
                <td class="field-label">姓名</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="infos[real_name]" value="<?=$infos['real_name']?>"
                           data-options="required:true,width:200,validType:['length[1,16]'],disabled:false">
                </td>
            </tr>
            <tr>
                <td class="field-label">咨询分类</td>
                <td class="field-input">
                    <?php foreach($categories as $key=>$name) {?>
                        <input name="infos[category][]" class="easyui-checkbox" data-options="label:'<?=$name?>',
                            labelPosition:'after',
                            labelWidth:100,
                            checked:<?=in_array($key,$infos['categoryIds'])?'true':'false'?>,
                            disabled:false" value="<?=$key?>">
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="field-label">联系方式</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="infos[cellphone]" value="<?=$infos['cellphone']?>"
                           data-options="required:true,width:200,validType:['length[1,16]','mobile'],disabled:false">(手机号码)
                    
                </td>
            </tr>
            <tr>
                <td class="field-label">从业时间</td>
                <td class="field-input">
                    <input class="easyui-datebox" name="infos[first_job_time]" value="<?=dateFilter($infos['first_job_time'])?>"
                           data-options="required:true,width:200,disabled:false">
                </td>
            </tr>
            <tr>
                <td class="field-label">工作单位</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="infos[workplace]" value="<?=$infos['workplace']?>"
                           data-options="required:true,
                            width:'100%',
                            validType:['length[1,256]'],
                            disabled:false">
                </td>
            </tr>
            <tr>
                <td class="field-label">个人介绍</td>
                <td class="field-input">
                    <div class="easyui-texteditor" name="infos[expert_profile]"
                         data-options="title:'',
                         width:'100%',
                         height:150,
                         validType:['length[1,1024]'],
                         disabled:false,
                         toolbar:['bold','italic','strikethrough','underline','-','justifyleft','justifycenter','justifyright','justifyfull','-','insertorderedlist','insertunorderedlist','insertimage']">
                        <?=htmlspecialchars_decode($infos['expert_profile'])?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="field-label">从业资质</td>
                <td class="field-input">
                    <!--不能很好的支持换行-->
                    <textarea id="expert-qualification-textbox" class="easyui-textbox" name="infos[expert_quality]"
                           data-options="required:false,
                            width:'100%',
                            height:100,
                            multiline:true,
                            validType:['length[1,1024]'],
                            disabled:false,
                            prompt:'请输入从业资质相关信息'"><?=$infos['expert_quality']?></textarea>
                    <!--
                    <textarea name="infos[expert_quality]" style="width: 100%;height: 60px;"><?=$infos['expert_quality']?></textarea>
                    -->
                </td>
            </tr>
            <tr>
                <td class="field-label">咨询经验(小时)</td>
                <td class="field-input">
                    <input name="infos[consult_quantity]" class="easyui-numberspinner" value="<?=$infos['consult_quantity']?>"
                           data-options="required:true,width:200,min:0,max:10000,editable:true">
                </td>
            </tr>
            <tr>
                <td class="field-label">咨询对象</td>
                <td class="field-input">
                    <?php foreach($targets as $key=>$name) {?>
                        <input name="infos[target][]" class="easyui-checkbox" data-options="label:'<?=$name?>',
                            labelPosition:'after',
                            labelWidth:80,
                            checked:<?=in_array($key,$infos['targetIds'])?'true':'false'?>,
                            disabled:false" value="<?=$key?>">
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="field-label">咨询价格</td>
                <td class="field-input">
                    <input class="easyui-numberbox" name="infos[appoint_fee]" value="<?=$infos['appoint_fee']?>"
                           data-options="required:true,
                            min:0,
							max:10000,
                            precision:2,
                            width:200,
                            disabled:false">(45分钟)
                </td>
            </tr>
            <tr>
                <td class="field-label">复诊价格</td>
                <td class="field-input">
                    <input class="easyui-numberbox" name="infos[appoint_review_fee]" value="<?=$infos['appoint_review_fee']?>"
                           data-options="required:true,
                            min:0,
							max:10000,
                            precision:2,
                            width:200,
                            disabled:false">(15分钟)
                </td>
            </tr>
            <tr>
                <td class="field-label">状态</td>
                <td class="field-input">
                    <select class="easyui-combobox" name="infos[status]" data-options="editable:false,panelHeight:'auto',value:'<?=$infos['status']?>'" style="width:200px;">
                        <?php foreach(IndexDefs::$entityStatusDefs as $key=>$label){ ?>
                        <option value="<?=$key?>"><?=$label?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    <div title="擅长领域" data-options="cache:false,iconCls:'fa fa-circle',border:false">
        <div class="m-5">
        <?php foreach($fields as $field){ ?>
            <h4><?=$field['field']?></h4>
            <?php foreach($field['field_items'] as $fieldItem){ ?>
                <input name="infos[field][<?=$field['field_id']?>][]" class="easyui-checkbox" data-options="label:'<?=$fieldItem['field_item']?>',
                        labelPosition:'after',
                        labelWidth:100,
                        checked:<?=in_array($fieldItem['field_item_id'],$infos['fieldItemIds'])?'true':'false'?>,
                        disabled:false" value="<?=$fieldItem['field_item_id']?>">
            <?php } ?>
            <h4></h4>
            <div class="line"></div>
        <?php } ?>
        </div>
    </div>
</div>
</form>
<script>
    $.parser.onComplete = function(context){
        $("#expert-qualification-textbox").textbox('autoHeight');
        $.parser.onComplete = $.noop;
    }

</script>