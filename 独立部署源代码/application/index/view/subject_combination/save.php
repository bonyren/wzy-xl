<?php
use app\index\logic\Defs as IndexDefs;
?>
<form id="combination-save-form">
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 100px;">图片(最佳尺寸750x370)</td>
            <td class="field-input">
                <?=action('Figure/save', ['inputCtrlName'=>'data[banner]', 'figureUrl'=>$row['banner'], 'width'=>120], 'widget')?>
            </td>
        </tr>
        <tr>
            <td class="field-label">名称</td>
            <td class="field-input">
                <input class="easyui-textbox" name="data[name]" value="<?=$row['name']?>" 
                    data-options="required:true,width:'100%',validType:['length[2,64]']">
            </td>
        </tr>
        <tr>
            <td class="field-label">量表</td>
            <td class="field-input">
                <?=action('Selector/save', [
                    'inputCtrlName'=>'data[subjects]',
                    'inputCtrlValue'=>$row['subjects'],
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
                <textarea class="easyui-textbox" name="data[description]"
                     data-options="width:'100%',height:50,multiline:true,validType:['length[1,1024]']"><?=$row['description']?></textarea>
            </td>
        </tr>
        <tr>
            <td class="field-label">状态</td>
            <td class="field-input">
                <select class="easyui-combobox" name="data[status]" data-options="editable:false,panelHeight:'auto',value:'<?=$row['status']?>'" style="width:160px;">
                    <?php foreach(IndexDefs::$entityStatusDefs as $key=>$label){ ?>
                    <option value="<?=$key?>"><?=$label?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table>
</form>