<?php
use app\index\logic\Defs as IndexDefs;
?>
<table class="table table-bordered table-sm" cellpadding="5">
    <tr>
        <td class="table-active" style="width: 15%;">图片</td>
        <td><img src="<?=generateThumbnailUrl($survey['banner'], 100)?>" class="img-thumbnail" style="width: 80px;"/></td>
    </tr>
    <tr>
        <td class="table-active">名称</td>
        <td><?=$survey['name']?></td>
    </tr>
    <tr>
        <td class="table-active">量表</td>
        <td>
        <?=action('Selector/save', [
                    'inputCtrlName'=>'formData[subjects]',
                    'inputCtrlValue'=>$survey['subjects'],
                    'dbTable'=>'subject',
                    'labelField'=>'name',
                    'valueField'=>'id',
                    'selectUrl'=>url('index/Subject/index'),
                    'multiple'=>true,
                    'readonly'=>true
                ], 'widget')?>
        </td>
    </tr>
    <tr>
        <td class="table-active">介绍</td>
        <td><?=nl2br($survey['description'])?></td>
    </tr>
    <tr>
        <td class="table-active">是否免费</td>
        <td>
            <?=formatBoolean($survey['cfg_free'])?>
        </td>
    </tr>
    <tr>
        <td class="table-active">是否录入个人资料</td>
        <td>
            <?=formatBoolean($survey['cfg_enter_personal_data'])?>
        </td>
    </tr>
    <tr>
        <td class="table-active">个人资料项目</td>
        <td>
            <?php
            if($survey['cfg_enter_personal_data']){
                $items = explode(',', $survey['cfg_personal_data']);
                foreach($items as $index=>$item){
                    if($index > 0){ echo ', '; }
                    if(isset(IndexDefs::SURVEY_ENTER_PERSONAL_DATA_ITEMS[$item])){
                        echo IndexDefs::SURVEY_ENTER_PERSONAL_DATA_ITEMS[$item];
                    }
                }
            }
            ?>
        </td>
    </tr>
    <tr>
        <td class="table-active">是否允许用户查看报告</td>
        <td>
            <?=formatBoolean($survey['cfg_view_report'])?>
        </td>
    </tr>
</table>