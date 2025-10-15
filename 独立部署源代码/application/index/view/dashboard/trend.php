<div id="trendChart" style="position:relative;width:100%;height:100%">
    <?php if(!$loginMobile){ ?>
        <div style="position: absolute;z-index:1000;top:30px;left:45px;"">
            <input id="dateBeginInput" class="easyui-datebox" data-options="width:120,onChange: trendChartModule.onDateChanged" value="<?=$beginDate?>"/>
            -
            <input id="dateEndInput" class="easyui-datebox" data-options="width:120,onChange: trendChartModule.onDateChanged" value="<?=$endDate?>"/>
        </div>
    <?php } ?>
    <div id="trendChartContainer" style="width:100%;height:100%"></div>
</div>
<script type="text/javascript">
    var trendChartModule = {
        width:300,
        height:600,
        chart: null,
        init: function(){
            trendChartModule.reLoadChart();
        },
        onDateChanged: function(newValue, oldValue){
            trendChartModule.reLoadChart();
        },
        reLoadChart: function(){
            <?php if(!$loginMobile){ ?>
                var beginDate = $('#dateBeginInput').datebox('getValue');
                var endDate = $('#dateEndInput').datebox('getValue');
            <?php }else{ ?>
                var beginDate = '<?=$beginDate?>';
                var endDate = '<?=$endDate?>';
            <?php } ?>
            $.post('<?=url('index/Dashboard/trend')?>', {beginDate:beginDate, endDate:endDate}, function(res){
                trendChartModule.chart = $('#trendChartContainer').highcharts({
                    chart: {
                        type: 'line',
                        margin: [ 80, 50, 120, 80]
                    },
                    credits: {
                        enabled: false
                    },
                    title: {
                        text: '<?=systemSetting('general_organisation_name')?>运营数据趋势图',
                        align:'center'
                    },
                    legend: {
                        align: 'right',
                        x: -30,
                        verticalAlign: 'top',
                        y: 30,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    xAxis: {
                        labels: {
                            rotation: - 45,
                            align: 'right',
                            style: {
                                fontSize: '13px',
                                fontFamily: 'Verdana, sans-serif'
                            }
                        },
                        categories: res.dates
                    },
                    yAxis: {
                        title: {
                            text: '人数'
                        }
                    },
                    tooltip: {
                        pointFormat: '{series.name} <b>{point.y:,.0f}</b> 人数'
                    },
                    series: [{
                        name: '新增用户数',
                        color: '#EE9A49',
                        data: res.newUsers,
                        dataLabels: {
                                enabled: true,
                                rotation: - 90,
                                color: '#FFFFFF',
                                align: 'right',
                                x: 4,
                                y: 10,
                                style: {
                                    fontSize: '13px',
                                    fontFamily: 'Verdana, sans-serif',
                                    textShadow: '0 0 3px black'
                                }
                            }
                        },
                        {
                            name: '评测订单数',
                            color: '#006633',
                            data: res.evaluateOrders,
                            dataLabels: {
                                enabled: true,
                                rotation: - 90,
                                color: '#FFFFFF',
                                align: 'right',
                                x: 4,
                                y: 10,
                                style: {
                                    fontSize: '13px',
                                    fontFamily: 'Verdana, sans-serif',
                                    textShadow: '0 0 3px black'
                                }
                            }
                        },
                        {
                            name: '预约订单数',
                            color: '#000000',
                            data: res.appointOrders,
                            dataLabels: {
                                enabled: true,
                                rotation: - 90,
                                color: '#FFFFFF',
                                align: 'right',
                                x: 4,
                                y: 10,
                                style: {
                                    fontSize: '13px',
                                    fontFamily: 'Verdana, sans-serif',
                                    textShadow: '0 0 3px black'
                                }
                            }
                        }
                    ]
                });
                trendChartModule.resizeChart();
            }, 'json');
        },
        resizeChart:function(){
            if(trendChartModule.chart) {
                var w1 = trendChartModule.width;
                var h1 = trendChartModule.height;
                if(w1 && h1 && w1>100) {
                    var chart = $('#trendChartContainer').highcharts();
                    chart.setSize(w1, h1, false);
                    chart.reflow();
                }
            }
        }
    };
    $(window).off('resize.trendChart').on('resize.trendChart',function() {
        setTimeout(function() {
            var w1 = $("#trendChart").width();
            var h1 = $("#trendChart").height();
            // console.log('trendChart', 'trendChart resize event', w1, h1);
            trendChartModule.width = w1;
            trendChartModule.height = h1;
            trendChartModule.resizeChart();
        },200);
    });
    $.parser.onComplete = function(){
        var w1 = $("#trendChart").width();
        var h1 = $("#trendChart").height();
        // console.log('trendChart', 'parser.onComplete', w1, h1);
        trendChartModule.width = w1;
        trendChartModule.height = h1;

        trendChartModule.init();
        $.parser.onComplete = $.noop;
    };
</script>