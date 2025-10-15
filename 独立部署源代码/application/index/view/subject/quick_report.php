<?php
use app\Defs;
?>
<table class="table-form" cellpadding="10">
    <tr class="field-label">
        <th rowspan="2" style="width:10% ;">维度因子</th>
        <th rowspan="2" style="width:5% ;">题目数</th>
        <th colspan="2" style="width:15% ;">总分</th>
        <th colspan="2" style="width:15% ;">平均分</th>
        <th rowspan="2" style="width:5% ;">阳性项目数</th>
        <th colspan="2" style="width:10% ;">阳性项目平均分</th>
        <th rowspan="2" style="width:10% ;">匹配表达式</th>
        <th rowspan="2" style="width:10% ;">解读</th>
        <th rowspan="2" style="width:10% ;">预警</th>
        <th rowspan="2" style="width:10% ;">建议</th>
    </tr>
    <tr class="field-label">
        <th>原始</th>
        <th>标准</th>
        <th>原始</th>
        <th>标准</th>
        <th>原始</th>
        <th>标准</th>
    </tr>
    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?=$row['latitude']??''?></td>
            <td><?=$row['item_count']??''?></td>
            <td><?=$row['total_weight']??''?> <br/>[<?=$row['total_weight_min']??''?> - <?=$row['total_weight_max']??''?>]</td>
            <?php if(isset($row['total_weight_standard'])){ ?>
                <td><?=$row['total_weight_standard']?> <br/>[<?=$row['total_weight_min_standard']?> - <?=$row['total_weight_max_standard']?>]</td>
            <?php }else{ ?>
                <td></td>
            <?php } ?>
            <td><?=$row['average_weight']??''?> <br/>[<?=$row['average_weight_min']??''?> - <?=$row['average_weight_max']??''?>]</td>
            <?php if(isset($row['total_weight_standard'])){ ?>
                <td><?=$row['average_weight_standard']?> <br/>[<?=$row['average_weight_min_standard']?> - <?=$row['average_weight_max_standard']?>]</td>
            <?php }else{ ?>
                <td></td>
            <?php } ?>
            <td><?=$row['positive_item_count']??''?></td>
            <td><?=$row['positive_average_weight']??''?></td>
            <?php if(isset($row['total_weight_standard'])){ ?>
                <td><?=$row['positive_average_weight_standard']?></td>
            <?php }else{ ?>
                <td></td>
            <?php } ?>
            <td><?=implode('<br />', convertSubjectExpression($row['matched_expressions']??[]))?></td>
            <td><?=nl2br(implode('<br />', $row['matched_descs']))?></td>
            <td>
                <?php
                if(isset($row['warning_level'])){
                    echo Defs::MEASURE_WARNINGS_HTML[$row['warning_level']];
                }
                ?>
            </td>
            <td>
            <?php if(!empty($row['matched_remarks'])){
                echo nl2br(implode('<br />', $row['matched_remarks']));
            } ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>