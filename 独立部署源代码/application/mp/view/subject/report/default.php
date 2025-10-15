<?php
use app\Defs;
if(empty($source)){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "header.php";
}else if($source == 'dian'){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "header_dian.php";
}
?>
<div class="report-content-section">
    <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
        <ion-card-header color="light">
            <ion-card-subtitle color="dark"><?=$subject['name']?></ion-card-subtitle>
        </ion-card-header>
    <!--总分-->
    <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_TOTAL_WEIGHT_CHART, $subject['report_elements'])){ ?>
        <ion-card-content>
            <ion-segment id="total-weight-type-switch-segment-<?=$uuid?>" 
                color="secondary" 
                value="original" 
                disabled="false" class="center-block" style="width: 50%;">
                <ion-segment-button value="original">
                    <ion-label>原始分</ion-label>
                </ion-segment-button>
                <ion-segment-button value="standard">
                    <ion-label>标准分</ion-label>
                </ion-segment-button>
            </ion-segment>
            <div id="total_weight_<?=$uuid?>" style="height:250px;"></div>
        </ion-card-content>
    <?php } ?>
    <!--平均分-->
    <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_AVERAGE_WEIGHT_CHART, $subject['report_elements'])){ ?>
        <ion-card-content>
            <ion-segment id="average-weight-type-switch-segment-<?=$uuid?>" 
                color="secondary" 
                value="original" 
                disabled="false" class="center-block" style="width: 50%;">
                <ion-segment-button value="original">
                    <ion-label>原始分</ion-label>
                </ion-segment-button>
                <ion-segment-button value="standard">
                    <ion-label>标准分</ion-label>
                </ion-segment-button>
            </ion-segment>
            <div id="average_weight_<?=$uuid?>" style="height:250px;"></div>
        </ion-card-content>
    <?php } ?>
    <!--阳性数量-->
    <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_POSITIVE_ITEM_COUNT_CHART, $subject['report_elements'])){ ?>
        <ion-card-content>
            <div id="positive_item_count_<?=$uuid?>" style="height:250px;"></div>
        </ion-card-content>
    <?php } ?>
    <!--阳性平均分-->
    <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_POSITIVE_AVERAGE_WEIGHT_CHART, $subject['report_elements'])){ ?>
        <ion-card-content>
            <ion-segment id="positive-average-weight-type-switch-segment-<?=$uuid?>" 
                color="secondary" 
                value="original" 
                disabled="false" class="center-block" style="width: 50%;">
                <ion-segment-button value="original">
                    <ion-label>原始分</ion-label>
                </ion-segment-button>
                <ion-segment-button value="standard">
                    <ion-label>标准分</ion-label>
                </ion-segment-button>
            </ion-segment>
            <div id="positive_average_weight_<?=$uuid?>" style="height:250px;"></div>
        </ion-card-content>
    <?php } ?>
    </ion-card>
    <!--测评报告解读------------------------------------------------------------------------------>
    <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_RESULT_DESC, $subject['report_elements'])){ ?>
        <!--先尝试“综合评估结果”-->
        <?php
        $standResultExist = false; 
        foreach ($order['report_list'] as $v){ 
            if(isset($v['matched']) && !$v['matched']) continue;
            if($v['standard_id']) {
                $standResultExist = true;
                continue;
            }
        ?>
       <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
            <ion-card-header color="light">
                <ion-card-subtitle color="dark">综合评估结果</ion-card-subtitle>
            </ion-card-header>
            <ion-card-content class="ion-no-padding">
            <ion-list>
            <ion-item-group>
                <?php
                if($v['warning_level'] != \app\Defs::MEASURE_WARNING_UNKOWN_LEVEL){
                ?>
                <ion-item-divider>
                    <ion-label slot="end" class="ion-no-margin">
                        <?=\app\Defs::MEASURE_WARNINGS_MP_HTML[$v['warning_level']]?>
                    </ion-label>
                </ion-item-divider>
                <?php
                }
                ?>
                <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_RESULT_WEIGHT, $subject['report_elements'])){ ?>
                    <ion-item>
                        <ion-note color="secondary" class="ion-padding-vertical">
                            原始总分: <strong><?=$v['total_weight']?></strong> [<?=$v['total_weight_min']??''?> - <?=$v['total_weight_max']?>]
                            &nbsp;&nbsp;
                            原始平均分: <strong><?=$v['average_weight']?></strong> [<?=$v['average_weight_min']??''?> - <?=$v['average_weight_max']?>]
                        </ion-note>
                    </ion-item>
                    <?php if(isset($v['total_weight_standard'])){ ?>
                        <ion-item>
                            <ion-note color="secondary" class="ion-padding-vertical">
                                标准总分: <strong><?=$v['total_weight_standard']?></strong> [<?=$v['total_weight_min_standard']??''?> - <?=$v['total_weight_max_standard']?>]
                                &nbsp;&nbsp;
                                标准平均分: <strong><?=$v['average_weight_standard']?></strong> [<?=$v['average_weight_min_standard']??''?> - <?=$v['average_weight_max_standard']?>]
                            </ion-note>
                        </ion-item>
                    <?php } ?>
                <?php } ?>
                <ion-item detail="false" lines="none">
                    <ion-note color="dark" class="ion-padding-top">
                        <?php echo nl2br(implode('<br />', $v['matched_descs']));?>
                    </ion-note>
                </ion-item>
                <?php if(!empty($v['matched_remarks'])){ ?>
                    <ion-item detail="false" lines="none">
                        <ion-note color="primary" slot="start"><strong>应对建议</strong></ion-note>
                    </ion-item>
                    <ion-item detail="false" lines="none" class="ion-padding-bottom">
                        <ion-note color="dark">
                            <?=nl2br(implode('<br />', $v['matched_remarks']))?>
                        </ion-note>
                    </ion-item>
                <?php } ?>
                <?php if($v['standard_remark']){ ?>
                    <ion-item detail="false" lines="none" class="ion-padding-bottom">
                        <ion-note><?=$v['standard_remark']?></ion-note>
                    </ion-item>
                <?php } ?>
            </ion-item-group>
            </ion-list>
            </ion-card-content>
        </ion-card>
        <?php } 
        if($standResultExist){
        ?>
        <!-------------------------------------------------------------------------------------->
        <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
            <ion-card-header color="light">
                <ion-card-subtitle color="dark">各维度评估结果</ion-card-subtitle>
            </ion-card-header>
            <ion-card-content class="ion-no-padding">
            <ion-list>
                <?php foreach ($order['report_list'] as $v){ 
                    if(isset($v['matched']) && !$v['matched']) continue;
                    if(empty($v['standard_id'])) continue; 
                ?>
                <ion-item-group>
                <ion-item-divider>
                    <ion-label color="success">
                        <?=$v['latitude']?>
                    </ion-label>
                    <!--
                    <ion-chip outline="true">
                        <ion-label color="primary">
                            题目数: <?=$v['item_count']??''?>
                        </ion-label>
                    </ion-chip>
                    -->
                    <ion-label slot="end" class="ion-no-margin">
                    <?php 
                    if($v['warning_level'] != \app\Defs::MEASURE_WARNING_UNKOWN_LEVEL){
                        echo \app\Defs::MEASURE_WARNINGS_MP_HTML[$v['warning_level']]; 
                    } 
                    ?>
                    </ion-label>
                </ion-item-divider>
                <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_RESULT_WEIGHT, $subject['report_elements'])){ ?>
                    <ion-item>
                        <ion-note color="secondary" class="ion-padding-vertical">
                            原始总分: <strong><?=$v['total_weight']?></strong> [<?=$v['total_weight_min']??''?> - <?=$v['total_weight_max']?>]
                            &nbsp;&nbsp;
                            原始平均分: <strong><?=$v['average_weight']?></strong> [<?=$v['average_weight_min']??''?> - <?=$v['average_weight_max']?>]
                        </ion-note>
                    </ion-item>
                    <?php if(isset($v['total_weight_standard'])){ ?>
                        <ion-item>
                            <ion-note color="secondary" class="ion-padding-vertical">
                                标准总分: <strong><?=$v['total_weight_standard']?></strong> [<?=$v['total_weight_min_standard']??''?> - <?=$v['total_weight_max_standard']?>]
                                &nbsp;&nbsp;
                                标准平均分: <strong><?=$v['average_weight_standard']?></strong> [<?=$v['average_weight_min_standard']??''?> - <?=$v['average_weight_max_standard']?>]
                            </ion-note>
                        </ion-item>
                    <?php } ?>
                <?php } ?>
                <ion-item detail="false" lines="none">
                    <ion-note color="dark" class="ion-padding-top">
                        <?php echo nl2br(implode('<br />', $v['matched_descs']));?>
                    </ion-note>
                </ion-item>
                <?php if(!empty($v['matched_remarks'])){ ?>
                    <ion-item detail="false" lines="none">
                        <ion-note color="primary" slot="start"><strong>应对建议</strong></ion-note>
                    </ion-item>
                    <ion-item detail="false" lines="none" class="ion-padding-bottom">
                        <ion-note color="dark">
                            <?=nl2br(implode('<br />', $v['matched_remarks']))?>
                        </ion-note>
                    </ion-item>
                <?php } ?>
                <?php if($v['standard_remark']){ ?>
                    <ion-item detail="false" lines="none" class="ion-padding-bottom">
                        <ion-note><?=$v['standard_remark']?></ion-note>
                    </ion-item>
                <?php } ?>
                </ion-item-group>
                <?php }; ?>
            </ion-list>
            </ion-card-content>
        </ion-card>
        <?php } ?>
    <?php } ?>
    <!----------------------------------------------------------------------------------------->
    <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_VIDEO_AUDIO, $subject['report_elements'])){ ?>
        <!--视频-->
        <?php if($subject['video_url']){ ?>
            <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
                <ion-card-header color="light">
                    <ion-card-subtitle color="dark">视频解说</ion-card-subtitle>
                </ion-card-header>
                <ion-card-content>
                    <div class="text-center">
                        <video controls="controls" style="width:100%;" src="<?=$subject['video_url']?>">
                            your browser does not support the video tag
                        </video>
                    </div>
                </ion-card-content>
            </ion-card>
        <?php } ?>
        <!--音频-->
        <?php if($subject['audio_url']){ ?>
            <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
                <ion-card-header color="light">
                    <ion-card-subtitle color="dark">音频解说</ion-card-subtitle>
                </ion-card-header>
                <ion-card-content>
                    <div class="text-center">
                        <audio controls="controls" src="<?=$subject['audio_url']?>">
                            your browser does not support the audio tag
                        </audio>
                    </div>
                </ion-card-content>
            </ion-card>
        <?php } ?>
    <?php } ?>
    <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_STORY, $subject['report_elements'])){ ?>
        <!--专家建议-->
        <?php if($subject['report_story1']){ ?>
            <ion-card class="ion-no-margin ion-margin-vertical" mode="md">
                <ion-card-header color="light">
                    <ion-card-subtitle color="dark">专家建议</ion-card-subtitle>
                </ion-card-header>
                <ion-card-content class="ion-no-padding">
                    <ion-list>
                    <?php for ($i=1; $i<=6; $i++){ if($subject['report_story'.$i]){ ?>
                        <ion-item detail="false" lines="none">
                            <?php if (!empty($subject['report_image'.$i])){ ?>
                                <ion-avatar aria-hidden="true" slot="start">
                                    <img src="<?=generateThumbnailUrl($subject['report_image'.$i], 100)?>">
                                </ion-avatar>
                            <?php } ?>
                            <ion-note class="ion-padding-bottom">
                                <?php echo htmlspecialchars_decode($subject['report_story'.$i]); ?>
                            </ion-note>
                        </ion-item>
                    <?php }} ?>
                    </ion-list>
                </ion-card-content>
            </ion-card>
        <?php } ?>
    <?php } ?>
</div>
<script type="text/javascript">
    var reportTpl_<?=$uuid?> = (function(){
        //总分
        var chartInstanceTotalWeight = null;
        //平均分
        var chartInstanceAverageWeight = null;
        //阳性数量
        var chartInstancePositiveItemCount = null;
        //阳性平均分
        var chartInstancePositiveAverageWeight = null;
        function createChart(title, element, xData, seriesData, seriesDataMax, color) {
            var chartInstance = echarts.init(element);
            chartInstance.setOption({
                title: {
                    text: title,
                    textStyle:{
                        color:'#000',
                        fontSize: 16
                    }
                },
                legend: {
                    data: [title, '最大值'],
                    left: 'right',
                    bottom: 10,
                    textStyle: {
                        color: "#000"
                    }
                },
                xAxis: {
                    type: 'category',
                    data: xData,
                    axisLabel: {
                        rotate: 45
                    }
                },
                yAxis: {
                    type: 'value',
                },
                series: [{
                    name: title,
                    data: seriesData,
                    type: 'bar',
                    barCategoryGap: "50%",
                    //barWidth: 45,
                    label: {
                        show: true,
                        textBorderColor: '#666',
                        textBorderWidth: 2
                    },
                    itemStyle: {
                        color: color
                        /*
                        color:function(params) {
                            console.log(params);
                            var colorList = [
                                '#dee6ce', '#f0d6bd', '#ffe6d7', '#d6b5bc', '#73bd8c', '#d6e6b5', '#f7f7c5',
                                '#dee6ce', '#f0d6bd', '#ffe6d7', '#d6b5bc', '#73bd8c', '#d6e6b5', '#f7f7c5'
                            ];
                            return colorList[params.dataIndex%colorList.length];
                        }*/
                    }
                },
                {
                    name: '最大值',
                    data: seriesDataMax,
                    type: 'line',
                    label: {
                        show: true,
                        textBorderColor: '#fff',
                        textBorderWidth: 2,
                        fontSize: 12
                    },
                    itemStyle: {
                        color:'#D2691E'
                    }
                }]
            });
            return chartInstance;
        }
        return {
            init:function(){
                var result = <?=$order['result']?>;
                var xData = [];
                var xDataStandard = [];
                var seriesDataItemCount = [];
                //原始分
                var seriesDataTotalWeight = [];
                var seriesDataTotalWeightMax = [];
                var seriesDataAverageWeight = [];
                var seriesDataAverageWeightMax = [];
                var seriesDataPositiveAverageWeight = [];
                //标准分
                var seriesDataTotalWeightStandard = [];
                var seriesDataTotalWeightMaxStandard = [];
                var seriesDataAverageWeightStandard = [];
                var seriesDataAverageWeightMaxStandard = [];
                var seriesDataPositiveAverageWeightStandard = [];

                var seriesDataPositiveItemCount = [];
                
                result.reportList.forEach(function(v){
                    xData.push(v.latitude);
                    seriesDataItemCount.push(v.item_count || 0);
                    //原始分
                    seriesDataTotalWeight.push(v.total_weight || 0);
                    seriesDataTotalWeightMax.push(v.total_weight_max || 0);
                    seriesDataAverageWeight.push(v.average_weight || 0);
                    seriesDataAverageWeightMax.push(v.average_weight_max || 0);
                    seriesDataPositiveAverageWeight.push(v.positive_average_weight || 0);

                    //标准分
                    if(v.total_weight_standard !== undefined && v.total_weight_standard !== null){
                        xDataStandard.push(v.latitude);
                        seriesDataTotalWeightStandard.push(v.total_weight_standard || 0);
                        seriesDataTotalWeightMaxStandard.push(v.total_weight_max_standard || 0);
                        seriesDataAverageWeightStandard.push(v.average_weight_standard || 0);
                        seriesDataAverageWeightMaxStandard.push(v.average_weight_max_standard || 0);
                        seriesDataPositiveAverageWeightStandard.push(v.positive_average_weight_standard || 0);
                    }
                    seriesDataPositiveItemCount.push(v.positive_item_count || 0);
                });
                //总分
                <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_TOTAL_WEIGHT_CHART, $subject['report_elements'])){ ?>
                    if(seriesDataTotalWeightStandard.length == 0){
                        $('#total-weight-type-switch-segment-<?=$uuid?>').hide();
                    }
                    if(seriesDataTotalWeight.some(function(ele){ return ele > 0; })){
                        chartInstanceTotalWeight = createChart('总分', 
                            document.getElementById('total_weight_<?=$uuid?>'), 
                            xData, 
                            seriesDataTotalWeight,
                            seriesDataTotalWeightMax,
                            '#dee6ce'
                        );
                        $('#total-weight-type-switch-segment-<?=$uuid?>').on('ionChange', function(evt){
                            var value = evt.target.value;
                            if(value == 'original'){
                                //原始分
                                chartInstanceTotalWeight = createChart('总分', 
                                    document.getElementById('total_weight_<?=$uuid?>'), 
                                    xData, 
                                    seriesDataTotalWeight,
                                    seriesDataTotalWeightMax,
                                    '#dee6ce'
                                );
                            }else if(value == 'standard'){
                                //标准分
                                chartInstanceTotalWeight = createChart('标准总分', 
                                    document.getElementById('total_weight_<?=$uuid?>'), 
                                    xDataStandard, 
                                    seriesDataTotalWeightStandard,
                                    seriesDataTotalWeightMaxStandard,
                                    '#dee6ce'
                                );
                            }
                        });
                    }else{
                        $('#total_weight_<?=$uuid?>').parent().hide();   
                    }
                <?php } ?>
                //平均分
                <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_AVERAGE_WEIGHT_CHART, $subject['report_elements'])){ ?>
                    if(seriesDataAverageWeightStandard.length == 0){
                        $('#average-weight-type-switch-segment-<?=$uuid?>').hide();
                    }
                    if(seriesDataAverageWeight.some(function(ele){ return ele > 0; })){
                        chartInstanceAverageWeight = createChart('平均分', 
                            document.getElementById('average_weight_<?=$uuid?>'), 
                            xData, 
                            seriesDataAverageWeight,
                            seriesDataAverageWeightMax, 
                            '#f7f7c5'
                        );
                        $('#average-weight-type-switch-segment-<?=$uuid?>').on('ionChange', function(evt){
                            var value = evt.target.value;
                            if(value == 'original'){
                                //原始分
                                chartInstanceAverageWeight = createChart('平均分', 
                                    document.getElementById('average_weight_<?=$uuid?>'),
                                    xData, 
                                    seriesDataAverageWeight,
                                    seriesDataAverageWeightMax, 
                                    '#f7f7c5'
                                );
                            }else if(value == 'standard'){
                                //标准分
                                chartInstanceAverageWeight = createChart('标准平均分', 
                                    document.getElementById('average_weight_<?=$uuid?>'),
                                    xDataStandard, 
                                    seriesDataAverageWeightStandard,
                                    seriesDataAverageWeightMaxStandard, 
                                    '#f7f7c5'
                                );
                            }
                        });
                    }else{
                        $('#average_weight_<?=$uuid?>').parent().hide();   
                    }
                <?php } ?>
                //阳性数量
                <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_POSITIVE_ITEM_COUNT_CHART, $subject['report_elements'])){ ?>
                    if(seriesDataPositiveItemCount.some(function(ele){ return ele > 0; })){
                        chartInstancePositiveItemCount = createChart('阳性数量', 
                            document.getElementById('positive_item_count_<?=$uuid?>'), 
                            xData, 
                            seriesDataPositiveItemCount,
                            seriesDataItemCount, 
                            '#d6e6b5');
                    }else{
                        $('#positive_item_count_<?=$uuid?>').parent().hide();   
                    }
                <?php } ?>
                //阳性平均分
                <?php if(in_array(Defs::SUBJECT_REPORT_ELEMENT_POSITIVE_AVERAGE_WEIGHT_CHART, $subject['report_elements'])){ ?>
                    if(seriesDataPositiveAverageWeightStandard.length == 0){
                        $('#positive-average-weight-type-switch-segment-<?=$uuid?>').hide();
                    }
                    if(seriesDataPositiveAverageWeight.some(function(ele){ return ele > 0; })){
                        chartInstancePositiveAverageWeight = createChart('阳性平均分', 
                            document.getElementById('positive_average_weight_<?=$uuid?>'), 
                            xData, 
                            seriesDataPositiveAverageWeight,
                            seriesDataAverageWeightMax, 
                            '#d6b5bc'
                        );
                        $('#positive-average-weight-type-switch-segment-<?=$uuid?>').on('ionChange', function(evt){
                            var value = evt.target.value;
                            if(value == 'original'){
                                //原始分
                                chartInstancePositiveAverageWeight = createChart('阳性平均分', 
                                    document.getElementById('positive_average_weight_<?=$uuid?>'), 
                                    xData, 
                                    seriesDataPositiveAverageWeight,
                                    seriesDataAverageWeightMax, 
                                    '#d6b5bc'
                                );
                            }else if(value == 'standard'){
                                //标准分
                                chartInstancePositiveAverageWeight = createChart('标准阳性平均分', 
                                    document.getElementById('positive_average_weight_<?=$uuid?>'), 
                                    xDataStandard, 
                                    seriesDataPositiveAverageWeightStandard,
                                    seriesDataAverageWeightMaxStandard, 
                                    '#d6b5bc'
                                );
                            }
                        });
                    }else{
                        $('#positive_average_weight_<?=$uuid?>').parent().hide();   
                    }
                <?php } ?>
                $(window).on('resize', function(){
                    reportTpl_<?=$uuid?>.resize();
                });
            },
            resize:function(){
                if(chartInstanceTotalWeight){
                    setTimeout(function(){
                        chartInstanceTotalWeight.resize();
                    }, 500);
                }
                if(chartInstanceAverageWeight){
                    setTimeout(function(){
                        chartInstanceAverageWeight.resize();
                    }, 500);
                }
                if(chartInstancePositiveItemCount){
                    setTimeout(function(){
                        chartInstancePositiveItemCount.resize();
                    }, 500);
                }
                if(chartInstancePositiveAverageWeight){
                    setTimeout(function(){
                        chartInstancePositiveAverageWeight.resize();
                    }, 500);
                }
            }
        };
    })();
    /************************************************************************************/
    initFuncs.push(reportTpl_<?=$uuid?>.init.bind(reportTpl_<?=$uuid?>));
</script>
<?php
if(empty($source)){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "footer.php";
}else if($source == 'dian'){
    include APP_PATH . "mp" . DS . "view" . DS . "subject" . DS . 'report' . DS . "footer_dian.php";
}
?>