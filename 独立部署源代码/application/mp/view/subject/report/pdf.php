<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>查看测评报告</title>
    <link rel="stylesheet" type="text/css" href="<?=SITE_URL?>/static/bootstrap/v4/bootstrap.min.css?<?= STATIC_VER ?>" />
    <style>
        body {
            font-family: "simsun";
        }
    </style>
</head>

<body>
    <h3><?= $subject['name'] ?> - 测评报告</h3>
    <!---------------------------------------------------------------------->
    <p>测评人：<?= $user['nickname'] ?></p>
    <p>测评时间：<?= $order['finish_time'] ?></p>
    <p>提供方：<?=$_studio['store_name']?></p>
    <!---------------------------------------------------------------------->
    <h3>测评结果</h3>
    <table class="table table-bordered" style="table-layout: fixed; font-size:12px;">
        <thead>
            <tr>
                <td rowspan="2">维度因子</td>
                <td rowspan="2">题目数</td>

                <td colspan="2" class="text-center">原始分</td>
                <td colspan="2" class="text-center">标准分</td>
                <td rowspan="2" class="text-center">阳性(平均分)<br/>(数量/原始/标准)</td>

                <td rowspan="2">解读</td>
                <td rowspan="2">预警</td>
                <td rowspan="2">建议</td>
            </tr>
            <tr>
                <td class="text-center">总分</td>
                <td class="text-center">平均</td>
                <td class="text-center">总分</td>
                <td class="text-center">平均</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($order['report_list'] as $v) : ?>
                <tr>
                    <td class="text-break" style="width: 10%;"><?= $v['latitude'] ?></td>
                    <td class="text-break" style="width: 5%;"><?= $v['item_count'] ?></td>

                    <td class="text-center" style="width: 10%;"><?= $v['total_weight'] ?><br/> [<?= $v['total_weight_min']??''?> - <?= $v['total_weight_max']?>]</td>
                    <td class="text-center" style="width: 10%;"><?= $v['average_weight'] ?><br/> [<?= $v['average_weight_min']??''?> - <?= $v['average_weight_max']?>]</td>
                    <?php if(isset($v['total_weight_standard'])){ ?>
                        <td class="text-center" style="width: 10%;"><?= $v['total_weight_standard']?><br/> [<?= $v['total_weight_min_standard']??''?> - <?= $v['total_weight_max_standard']?>]</td>
                        <td class="text-center" style="width: 10%;"><?= $v['average_weight_standard']?><br/> [<?= $v['average_weight_min_standard']??''?> - <?= $v['average_weight_max_standard']?>]</td>
                    <?php }else{ ?>
                        <td style="width: 10%;"></td>
                        <td style="width: 10%;"></td>
                    <?php } ?>
                    <td class="text-center" style="width: 15%;"><?=$v['positive_item_count']?:''?> / <?=$v['positive_average_weight']?:''?> / <?=$v['positive_average_weight_standard']?:''?></td>

                    <td class="text-break" style="width: 15%;">
                        <?=nl2br(implode('<br />', $v['matched_descs']))?>
                    </td>
                    <td style="width: 5%;">
                        <?= \app\Defs::MEASURE_WARNINGS_MP_HTML[$v['warning_level']] ?>
                    </td>
                    <td class="text-break" style="width: 10%;">
                    <?php if(!empty($v['matched_remarks'])){
                        echo nl2br(implode('<br />', $v['matched_remarks']));
                    } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!---------------------------------------------------------------------->
    <?php if($subject['report_story1']){ ?>
    <h3>如何改善</h3>
    <?php } ?>
    <table class="table" style="table-layout: fixed">
    <tbody>
        <?php for ($i = 1; $i <= 6; $i++) : if ($subject['report_story' . $i]) : ?>
                <tr>
                    <td class="text-break" style="width: 100%;">
                        <?= htmlspecialchars_decode($subject['report_story' . $i]) ?>
                    </td>
                </tr>
        <?php endif;
        endfor; ?>
    </tbody>
    </table>
    <p class="text-center">Copyright © 2023 <?=$_studio['store_name']?></p>
</body>
</html>