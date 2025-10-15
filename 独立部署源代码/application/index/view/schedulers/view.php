<div class="easyui-layout" fit="true" border="false">
    <div data-options="region:'north',border:false" style="height:150px;">
        <table class="table-form" cellpadding="5">
            <tr>
                <td width="120" class="field-label">名称</td>
                <td><?=$row['name']?></td>
                <td width="120" class="field-label">状态</td>
                <td><?=\app\cli\logic\Scheduler::STATUS[$row['disabled']]['label']?></td>
            </tr>
            <tr>
                <td class="field-label">Job</td>
                <td><?=$row['job']?></td>
                <td class="field-label">执行间隔</td>
                <td><?=$row['interval']?></td>
            </tr>
            <tr>
                <td class="field-label">开始时间</td>
                <td><?=$row['date_time_start']?></td>
                <td class="field-label">结束时间</td>
                <td><?=dateTimeFilter($row['date_time_end'])?></td>
            </tr>
        </table>
    </div>
    <div data-options="region:'center',title:'运行日志',border:false,href:'<?=url('schedulers/logs')?>?scheduler_id=<?=$row['id']?>'"></div>
</div>