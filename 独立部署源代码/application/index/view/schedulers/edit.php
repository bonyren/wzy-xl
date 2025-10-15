<form method="post" id="scheduler_edit_form">
    <table class="table-form" cellpadding="5">
        <tr>
            <td width="120" class="field-label">名称</td>
            <td>
                <input class="easyui-textbox" name="name" required="true" value="<?=$row['name']?>" style="width:100%;">
            </td>
        </tr>
        <tr>
            <td class="field-label">状态</td>
            <td>
                <select class="easyui-combobox" name="disabled" required="true" editable="false" panelHeight="auto" style="width:100%;">
                    <?php foreach (\app\cli\logic\Scheduler::STATUS as $v): ?>
                        <option value="<?=$v['value']?>" <?=$row['disabled']==$v['value']?'selected':''?>><?=$v['label']?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr id="scheduler_job_type_func">
            <td class="field-label">Job</td>
            <td>
                <select class="easyui-combobox" name="job" required="true" editable="false" panelHeight="auto" style="width:100%;">
                    <?php foreach (\app\cli\logic\Scheduler::JOBS as $key=>$v): ?>
                        <option value="<?=$key?>" <?=$row['job']==$key?'selected':''?>><?=$v?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="field-label">执行间隔</td>
            <td>
                <input name="interval" value="<?=$row['interval']?>" class="easyui-textbox" required="true" prompt="* * * * *" style="width:100%">
            </td>
        </tr>
        <tr>
            <td class="field-label">开始时间</td>
            <td>
                <input name="date_time_start" value="<?=dateTimeFilter($row['date_time_start'])?>" class="easyui-datetimebox" required="true" showSeconds="false" editable="false" style="width:100%">
            </td>
        </tr>
        <tr>
            <td class="field-label">结束时间</td>
            <td>
                <input name="date_time_end" value="<?=dateTimeFilter($row['date_time_end'])?>" class="easyui-datetimebox" showSeconds="false" style="width:100%">
            </td>
        </tr>
    </table>
</form>
