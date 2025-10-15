<style>
    .appoint-time-schedule .l-btn-selected,
    .appoint-time-schedule .l-btn-selected:hover {
        color: #DAA520 !important;
    }
</style>
<div class="easyui-panel" data-options="fit:true,border:false">
    <header class="clearfix">
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){
            expertScheduleModule.set45AppointTime();
        }">设置为45分钟(咨询)预约片</a>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){
            expertScheduleModule.set15AppointTime();
        }">设置为15分钟(复诊)预约片</a>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){
            expertScheduleModule.cancelAppointTime();
        }">删除该预约时间片</a>
        <div class="float-right">
            <a href="javascript:;" class="easyui-linkbutton c1"></a>45分钟(咨询)时间片;
            <a href="javascript:;" class="easyui-linkbutton c6"></a>15分钟(复诊)时间片;
            <a href="javascript:;" class="easyui-linkbutton"></a>未分配时间片;
        </div>
    </header>
    <table class="table table-bordered appoint-time-schedule">
        <?php foreach(\app\index\logic\Defs::$appointDayDefs as $dayKey=>$weekDay){ ?>
            <tr>
                <td class="table-active" style="width: 15%;">
                    <?=$weekDay?><br />
                    <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){
                        expertScheduleModule.defaultSchedule(<?=$dayKey?>);
                    }" titile="重置为默认时间设置">重置</a>
                </td>
                <td style="text-align: left;">
                    <?php $i=0;foreach($appointIntervals as $intervalItem){ $i++;?>
                        <?php
                        $intervalType = 0;
                        $colorCls = '';
                        foreach($infos['appoint_day_times'] as $item){
                            $appointTimes = explode(',', $item['appoint_time']);
                            if($item['week_day'] == $dayKey && in_array($intervalItem, $appointTimes)){
                                //find
                                if(count($appointTimes) == 1){
                                    $intervalType = 1;//15 minutes
                                    $colorCls = 'c6';
                                }else if(count($appointTimes) == 3){
                                    $intervalType = 2;//45 minutes
                                    $colorCls = 'c1';
                                }
                            }
                        }
                        ?>
                        <a href="javascript:;" id="linkbutton-<?=$dayKey?>-<?=str_replace(':', '_', $intervalItem)?>" class="easyui-linkbutton <?=$colorCls?> my-1" data-options="toggle:true, onClick:function(){
                            expertScheduleModule.onAppointIntervalClick(<?=$dayKey?>, '<?=$intervalItem?>');
                        },width:98"><?=$intervalItem?></a>
                    <?php if($i%8 == 0){ echo '<br />'; } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<script type="text/javascript">
    var expertScheduleModule = {
        expertId:<?=$expertId?>,
        selectedWeekDay:null,
        selectedIntervals:[],
        onAppointIntervalClick:function(weekDay, value){
            if(expertScheduleModule.selectedIntervals.length>0 && weekDay != expertScheduleModule.selectedWeekDay){
                //清理其他天的选择
                expertScheduleModule.selectedIntervals.forEach(function(interval){
                    console.log('unselect', '#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_'));
                    $('#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_')).linkbutton('unselect');
                });
                expertScheduleModule.selectedIntervals.splice(0, expertScheduleModule.selectedIntervals.length);
                expertScheduleModule.selectedWeekDay = null;
            }
            var index = $.inArray(value, expertScheduleModule.selectedIntervals);
            if(index == -1){
                expertScheduleModule.selectedWeekDay = weekDay;
                expertScheduleModule.selectedIntervals.push(value);
            }else{
                expertScheduleModule.selectedIntervals.splice(index, 1);
                if(expertScheduleModule.selectedIntervals.length == 0){
                    expertScheduleModule.selectedWeekDay = null;
                }
            }
            console.log(expertScheduleModule.selectedWeekDay, expertScheduleModule.selectedIntervals);
        },
        defaultSchedule:function(weekDay){
            var that = this;
            var href = '<?=url('index/Expert/defaultSchedule')?>';
            $.messager.confirm('提示', '确认重置吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {expertId:expertScheduleModule.expertId, weekDay:weekDay}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        //如何刷新界面
                        $('#expertScheduleContainer').panel('refresh');
                    }
                }, 'json');
            });
        },
        set45AppointTime:function(){
            var that = this;
            if(expertScheduleModule.selectedIntervals.length != 3){
                $.app.method.alertWarning(null, '请选择3个连续的时间块');
                return;
            }
            var href = '<?=url('index/Expert/set45AppointTime')?>';
            $.messager.confirm('提示', '确认设置45分钟预约块吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {expertId:expertScheduleModule.expertId,
                        weekDay:expertScheduleModule.selectedWeekDay,
                        intervals:expertScheduleModule.selectedIntervals
                    }, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        //如何刷新界面
                        expertScheduleModule.selectedIntervals.forEach(function(interval){
                            $('#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_')).linkbutton('unselect');
                            $('#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_')).addClass('c1');
                        });
                        expertScheduleModule.selectedIntervals.splice(0, expertScheduleModule.selectedIntervals.length);
                        expertScheduleModule.selectedWeekDay = null;
                    }
                }, 'json');
            });
        },
        set15AppointTime:function(){
            var that = this;
            if(expertScheduleModule.selectedIntervals.length != 1){
                $.app.method.alertWarning(null, '请选择1个时间块');
                return;
            }
            var href = '<?=url('index/Expert/set15AppointTime')?>';
            $.messager.confirm('提示', '确认设置15分钟预约块吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {expertId:expertScheduleModule.expertId,
                    weekDay:expertScheduleModule.selectedWeekDay,
                    intervals:expertScheduleModule.selectedIntervals
                }, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        //如何刷新界面
                        expertScheduleModule.selectedIntervals.forEach(function(interval){
                            $('#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_')).linkbutton('unselect');
                            $('#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_')).addClass('c6');
                        });
                        expertScheduleModule.selectedIntervals.splice(0, expertScheduleModule.selectedIntervals.length);
                        expertScheduleModule.selectedWeekDay = null;
                    }
                }, 'json');
            });
        },
        cancelAppointTime:function(){
            var that = this;
            if(expertScheduleModule.selectedIntervals.length != 1 && expertScheduleModule.selectedIntervals.length != 3){
                $.app.method.alertWarning(null, '请完整选择45分钟3个连续时间块/15分钟1个时间块');
                return;
            }
            var href = '<?=url('index/Expert/cancelAppointTime')?>';
            $.messager.confirm('提示', '确认取消预约块吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {expertId:expertScheduleModule.expertId,
                    weekDay:expertScheduleModule.selectedWeekDay,
                    intervals:expertScheduleModule.selectedIntervals
                }, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        //如何刷新界面
                        expertScheduleModule.selectedIntervals.forEach(function(interval){
                            $('#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_')).linkbutton('unselect');
                            $('#linkbutton-' +  expertScheduleModule.selectedWeekDay + '-' + interval.replace(/:/g, '_')).removeClass('c1').removeClass('c6');
                        });
                        expertScheduleModule.selectedIntervals.splice(0, expertScheduleModule.selectedIntervals.length);
                        expertScheduleModule.selectedWeekDay = null;
                    }
                }, 'json');
            });
        }
    }
</script>