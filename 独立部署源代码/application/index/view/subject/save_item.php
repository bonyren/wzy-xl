<?php
use app\Defs;
?>
<form>
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" width="100">题目</td>
            <td class="field-input">
                <textarea class="easyui-textbox" name="formData[item]"
                       data-options="required:true,multiline:true,width:'99%',height:60,validType:['length[2,200]']"><?=$formData['item']?></textarea>
            </td>
            <td class="field-input" width="80">
                <?=action('Figure/save', ['inputCtrlName'=>'formData[image]', 'figureUrl'=>$formData['image'], 'width'=>60, 'compact'=>true], 'widget')?>
            </td>
        </tr>
        <tr>
            <td class="field-label">类型</td>
            <td class="field-input" colspan="2">
                <select class="easyui-combobox" name="formData[type]" value="<?=$formData['type']?>" data-options="editable:false,width:100,panelHeight:'auto',onChange:saveItemModule.onTypeChange,value:'<?=$formData['type']?>'">
                    <?php foreach(Defs::QUESTION_TYPES as $key=>$label){ ?>
                    <option value="<?=$key?>"><?=$label?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr class="save-item-radio-checkbox-part">
            <td class="field-label">维度(因子)</td>
            <td class="field-input" colspan="2">
                <?php foreach($standards as $standard){ ?>
                <input type="checkbox" name="standards[]" value="<?=$standard['standard_id']?>" <?=in_array($standard['standard_id'],$formData['standards'])?'checked':''?>><?php echo $standard['latitude'];?>
                <?php } ?>
            </td>
        </tr>
        <tr>
            <td class="field-label">备注</td>
            <td class="field-input" colspan="2">
                <textarea class="easyui-textbox" name="formData[remark]"
                       data-options="width:'95%',height:60,multiline:true,validType:['length[0,128]']"><?=$formData['remark']?></textarea>
            </td>
        </tr>
        <tr class="save-item-radio-checkbox-part">
            <td class="field-label" colspan="3">选项</td>
        </tr>
        <?php for($i=1; $i<=12; $i++){ ?>
            <tr class="save-item-radio-checkbox-part">
                <td class="field-input" colspan="2">
                    <textarea class="easyui-textbox" id="form_option_data_<?=$i?>" name="formOptionData[option_<?=$i?>]" data-options="required:<?=$i==1?'false':'false'?>,label:'选项<?=$i?>',width:230,multiline:true,height:100,validType:['length[1,128]']"><?=$formData['option_'.$i]?></textarea>
                    <input class="easyui-textbox" name="formOptionData[weight_<?=$i?>]" value="<?=$formData['weight_'.$i]?>" data-options="label:'分数',validType:['subject_item_option_weight']" style="width:100px">
                    <select class="easyui-combobox" name="formOptionData[nature_<?=$i?>]" data-options="editable:false,
                        showItemIcon:true,
                        panelHeight:'auto',
                        label:'性质',
                        value:'<?=$formData['nature_'.$i]?>'" style="width:120px;">
                        <?php foreach(Defs::SUBJECT_ITEM_OPTION_NATURES as $key=>$label){ ?>
                        <option value="<?=$key?>" iconCls="<?=Defs::SUBJECT_ITEM_OPTION_ICONS_NATURES[$key]?>"><?=$label?></option>
                        <?php } ?>
                    </select>
                </td>
                <td class="field-input" width="80">
                <?=action('Figure/save', ['inputCtrlName'=>'formOptionData[image_'.$i.']', 'figureUrl'=>$formData['image_'.$i], 'width'=>60, 'compact'=>true], 'widget')?>
                </td>
            </tr>
        <?php } ?>
    </table>
</form>
<script type="text/javascript">
    var saveItemModule = {
        onTypeChange:function(newValue,oldValue){
            if(newValue == <?=Defs::QUESTION_TEXT?>){
                $('.save-item-radio-checkbox-part').hide();
                $('#form_option_data_1').textbox('disableValidation');
            }else{
                $('.save-item-radio-checkbox-part').show();
                $('#form_option_data_1').textbox('enableValidation');
            }
        }
    };
    setTimeout(function(){
        //wait the easyui components ready
        <?php if($formData['type'] == Defs::QUESTION_TEXT){ ?>
        $('.save-item-radio-checkbox-part').hide();
        <?php } ?>
    }, 100);
</script>