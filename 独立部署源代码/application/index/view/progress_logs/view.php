<table class="table-form" cellpadding="5">
    <tr>
        <td width="120" class="field-label">标题</td>
        <td><?=$row['title']?></td>
    </tr>
    <tr>
        <td class="field-label">日期</td>
        <td ><?=$row['occur_date']?></td>
    </tr>
    <tr>
        <td class="field-label">附件</td>
        <td>
            <div style="width:100%" class="easyui-panel" data-options="border:false,href:'<?=$attachment_url?>'"></div>
        </td>
    </tr>
    <tr>
        <td class="form-tip" colspan="2">内容</td>
    </tr>
    <tr>
        <td class="field-input" colspan="2">
            <?=nl2br($row['entry'])?>
        </td>
    </tr>
</table>