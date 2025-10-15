<table class="table table-bordered table-sm" cellpadding="5">
    <tr>
        <td colspan="2" class="table-tr-caption">基本信息</td>
    </tr>
    <!--
    <tr>
        <td class="table-active" style="width: 20%;">专家编号</td>
        <td><?=$infos['id']?></td>
    </tr>
    -->
    <tr>
        <td class="table-active" style="width: 15%;">头像</td>
        <td><img src="<?=generateThumbnailUrl($infos['workimg_url'], 100)?>" class="img-thumbnail" style="width: 120px;"></td>
    </tr>
    <tr>
        <td class="table-active">姓名</td>
        <td><?=$infos['real_name']?></td>
    </tr>
    <tr>
        <td class="table-active">分类</td>
        <td>
            <?php foreach($infos['categoryNames'] as $categoryName){ ?>
            <span class="label label-default"><?=$categoryName?></span>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td class="table-active">联系方式(手机)</td>
        <td><?=$infos['cellphone']?></td>
    </tr>
    <tr>
        <td class="table-active">从业时间</td>
        <td><?=$infos['first_job_time']?></td>
    </tr>
    <tr>
        <td class="table-active">工作单位</td>
        <td><?=$infos['workplace']?></td>
    </tr>
    <tr>
        <td class="table-active">个人介绍</td>
        <td><?=htmlspecialchars_decode($infos['expert_profile'])?></td>
    </tr>
    <tr>
        <td class="table-active">从业资质</td>
        <td><?=nl2br($infos['expert_quality'])?></td>
    </tr>
    <tr>
        <td class="table-active">咨询经验(小时)</td>
        <td><?=$infos['consult_quantity']?></td>
    </tr>
    <tr>
        <td class="table-active">咨询对象</td>
        <td>
            <?php foreach($infos['targetNames'] as $targetName){ ?>
                <span class="label label-default mr-10"><?=$targetName?></span>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td class="table-active">咨询价格</td>
        <td><?=$infos['appoint_fee']?>元/小时</td>
    </tr>
    <tr>
        <td class="table-active">擅长领域</td>
        <td>
            <?php foreach($infos['fields'] as $field=>$items){ ?>
                <div>
                    <i><?=$field?></i>
                    (
                        <?php foreach($items as $item){ ?>
                            <span class="label label-warning mr-10"><?=$item?></span>
                        <?php } ?>
                    )
                </div>
                <div class="line"></div>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td class="table-active">预约工作日</td>
        <td>
            <?php foreach(\app\index\logic\Defs::$appointDayDefs as $dayKey=>$weekDay){ ?>
                <div><strong><?=$weekDay?></strong></div>
                <?php $i=0;foreach($appointIntervals as $intervalItem){ $i++;?>
                    <?php
                    $colorCls = '';
                    foreach($infos['appoint_day_times'] as $item){
                        $appointTimes = explode(',', $item['appoint_time']);
                        if($item['week_day'] == $dayKey && in_array($intervalItem, $appointTimes)){
                            //find
                            if(count($appointTimes) == 1){
                                $colorCls = 'label-primary';
                            }else if(count($appointTimes) == 3){
                                $colorCls = 'label-success';
                            }
                        }
                    }
                    ?>
                    <span class="label <?=$colorCls?> mr-10"><?=$intervalItem?></span>

                    <?php
                    /*
                    $filterAppointTimes = array_filter($infos['appoint_day_times'], function($item) use($dayKey, $intervalItem){
                        if($item['week_day'] == $dayKey && $item['appoint_time'] == $intervalItem){
                            return true;
                        }else{
                            return false;
                        }
                    });
                    echo $filterAppointTimes?'<span class="label label-success mr-10">'.$intervalItem.'</span>':'<span class="label label-default mr-10">'.$intervalItem.'</span>';
                    */
                    if($i%8 == 0){ echo '<br />'; }
                    ?>
                <?php } ?>
                <div class="line"></div>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="table-tr-caption">业务统计</td>
    </tr>
    <tr>
        <td class="table-active">最近预约时间</td>
        <td><?=$infos['latest_appoint_time']?></td>
    </tr>
    <tr>
        <td class="table-active">预约总次数</td>
        <td><?=$infos['total_appoint_quantity']?></td>
    </tr>
    <tr>
        <td class="table-active">预约总金额</td>
        <td><?=$infos['total_appoint_amount']?></td>
    </tr>
</table>