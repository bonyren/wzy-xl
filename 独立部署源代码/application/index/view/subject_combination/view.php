<table class="table table-bordered table-sm" cellpadding="5">
    <tr>
        <td class="table-active" style="width: 15%;">图片</td>
        <td><img src="<?=generateThumbnailUrl($combination['banner'], 100)?>" class="img-thumbnail" style="width: 80px;"/></td>
    </tr>
    <tr>
        <td class="table-active">名称</td>
        <td><?=$combination['name']?></td>
    </tr>
    <tr>
        <td class="table-active">量表</td>
        <td>
        <?=action('Selector/save', [
                    'inputCtrlName'=>'data[subjects]',
                    'inputCtrlValue'=>$combination['subjects'],
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
        <td><?=nl2br($combination['description'])?></td>
    </tr>
</table>