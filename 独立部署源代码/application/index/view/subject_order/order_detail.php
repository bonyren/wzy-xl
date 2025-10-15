<?php
use app\Defs;
?>
<div class="pd-20">
    <table class="table table-bordered">
        <tr>
            <td height="30">昵称：<?=isset($order['user']['nickname'])?$order['user']['nickname']:''?></td>
            <td height="30">评估项目：<span class="font-weight-bold"><?=isset($order['subject']['name'])?$order['subject']['name']:''?></span></td>
            <td height="30">评估时间：<span class="font-weight-bold"><?=$order['order_time']?></span></td>
        </tr>
    </table>
    <br>
    <table class="table-form" cellpadding="5">
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
        <?php if($order['finished']): foreach ($order['report_list'] as $row): ?>
            <tr>
                <td><?=$row['latitude']?></td>
                <td><?=$row['item_count']??''?></td>
                <td><?=$row['total_weight']??''?> <br/>[<?=$row['total_weight_min']??''?> - <?=$row['total_weight_max']??''?>]</td>
                <?php if(isset($row['total_weight_standard'])){ ?>
                    <td><?=$row['total_weight_standard']?> <br/>[<?=$row['total_weight_min_standard']??''?> - <?=$row['total_weight_max_standard']??''?>]</td>
                <?php }else{ ?>
                    <td></td>
                <?php } ?>
                <td><?=$row['average_weight']??''?> <br/>[<?=$row['average_weight_min']??''?> - <?=$row['average_weight_max']??''?>]</td>
                <?php if(isset($row['total_weight_standard'])){ ?>
                    <td><?=$row['average_weight_standard']?> <br/>[<?=$row['average_weight_min_standard']??''?> - <?=$row['average_weight_max_standard']??''?>]</td>
                <?php }else{ ?>
                    <td></td>
                <?php } ?>
                <td><?=$row['positive_item_count']??''?></td>
                <td><?=$row['positive_average_weight']??''?></td>
                <?php if(isset($row['total_weight_standard'])){ ?>
                    <td><?=$row['positive_average_weight_standard']??''?></td>
                <?php }else{ ?>
                    <td></td>
                <?php } ?>
                <td><?=implode('<br />', convertSubjectExpression($row['matched_expressions']??[]))?></td>
                <td><?=nl2br(implode('<br />', ellipsisString($row['matched_descs'], 32)))?></td>
                <td>
                    <?php
                    if(isset($row['warning_level'])){
                        echo Defs::MEASURE_WARNINGS_HTML[$row['warning_level']];
                    }
                    ?>
                </td>
                <td>
                <?php if(!empty($row['matched_remarks'])){
                    echo nl2br(implode('<br />', ellipsisString($row['matched_remarks'], 32)));
                } ?>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="9" height="50" class="text-red"><i>评估未完成</i></td>
            </tr>
        <?php endif; ?>
    </table>
</div>
<br>
<div class="pd-20">
    <table class="table table-bordered">
        <?php
        $k=0;
        foreach ($items as $itemId=>$item){
            $border = ($k ? 'border-top:1px dashed #ccc;' : '');
            $itemValue=isset($order['items'][$itemId])?$order['items'][$itemId]['value']:'';
            ?>
            <tr>
                <td width="20" height="40" style="<?=$border?>" class="table-active"><?=$k+1?>.</td>
                <td style="<?=$border?>">&nbsp;<?=$item['item']?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php if($item['type'] == Defs::QUESTION_RADIO){ ?>
                        <?php for($i=1; $i<=12; $i++): if ($item['option_'.$i]): ?>
                            <input type="radio" onclick="return false;" <?=$itemValue==$i?'checked':''?>><?php echo $item['option_'.$i]; ?>&nbsp;&nbsp;
                        <?php endif; endfor; ?>
                    <?php }else if($item['type'] == Defs::QUESTION_CHECKBOX){
                            if(!is_array($itemValue)){
                                //多选只选了一个，转化为数组
                                $itemValue = [$itemValue];
                            }
                        ?>
                        <?php for($i=1; $i<=12; $i++): if ($item['option_'.$i]): ?>
                            <input type="checkbox" onclick="return false;" <?=in_array($i, $itemValue)?'checked':''?>><?php echo $item['option_'.$i]; ?>&nbsp;&nbsp;
                        <?php endif; endfor; ?>
                    <?php }else if($item['type'] == Defs::QUESTION_TEXT){ ?>
                        <textarea cols="100" rows="2" readonly="readonly"><?=$itemValue?></textarea>
                    <?php } ?>
                </td>
            </tr>
        <?php $k++; } ?>
    </table>
</div>