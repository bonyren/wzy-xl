<?php
use app\index\service\OperationLogs as OperationLogsService;
?>
<table class="table table-bordered table-sm" cellpadding="5">
    <tr>
        <td class="table-active" style="width: 20%;">类别</td>
        <td><?=OperationLogsService::CATEGORY_DEFS[$log['category']]?></td>
    </tr>
    <tr>
        <td class="table-active">类型</td>
        <td><?=OperationLogsService::OPT_DEFS[$log['type']]?></td>
    </tr>
    <tr>
        <td class="table-active">时间</td>
        <td><?=$log['entered']?></td>
    </tr>
    <tr>
        <td class="table-active">标题</td>
        <td><?=$log['title']?></td>
    </tr>
    <tr>
        <td class="table-active">内容</td>
        <td><?=$log['content']?></td>
    </tr>
    <tr>
        <td class="table-active">操作人</td>
        <td><?=$log['changed_by']?></td>
    </tr>
    <tr>
        <td class="table-active">设备</td>
        <td><?=\app\Defs::DEVICE_DEFS[$log['device']]?></td>
    </tr>
    <tr>
        <td class="table-active">Ip</td>
        <td><?=$log['ip']?></td>
    </tr>
</table>