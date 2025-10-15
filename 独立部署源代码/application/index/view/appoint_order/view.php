<?php
use app\index\logic\Defs as IndexDefs;
?>
<div class="easyui-tabs" data-options="fit:true,border:false">
    <div data-options="title:'订单信息',cache:false,iconCls:'fa fa-circle',border:false,selected:true">
        <table class="table table-bordered">
            <tr>
                <td class="table-active" style="width: 15%;">预约订单号</td>
                <td style="width: 40%;"><?=$orderInfos['order_no']?></td>
                <td class="table-active" style="width: 15%;">预约金额</td>
                <td><?=$orderInfos['order_amount']?></td>
            </tr>
            <tr>
                <td class="table-active">订单时间</td>
                <td><?=$orderInfos['order_time']?></td>
                <td class="table-active">完成时间</td>
                <td><?=$orderInfos['finish_time']?></td>
            </tr>
            <tr>
                <td class="table-active">预约时间</td>
                <td class="text-success"><?=$orderInfos['appoint_time_full']?></td>
                <td class="table-active">咨询方式</td>
                <td>
                    <?=IndexDefs::$appointModeHtmlDefs[$orderInfos['appoint_mode']]?>
                </td>
            </tr>
            <tr>
                <td class="table-active">预约人</td>
                <td><?=$orderInfos['linkman']?></td>
                <td class="table-active">电话</td>
                <td><?=$orderInfos['cellphone']?></td>
            </tr>
            <tr>
                <td class="table-active">收款金额</td>
                <td><?=$orderInfos['order_amount']?></td>
                <td class="table-active">退款金额</td>
                <td><?=$orderInfos['refund_amount']?></td>
            </tr>
            <tr>
                <td class="table-active">预约备注信息</td>
                <td colspan="3"><?=nl2br($orderInfos['remark'])?></td>
            </tr>
            <tr>
                <td class="table-active">支付时间</td>
                <td><?=$orderInfos['pay_time']?></td>
                <td class="table-active">支付状态</td>
                <td><?=\app\Defs::PAYS_HTML[$orderInfos['pay_status']]?></td>
            </tr>
            <tr>
                <td class="table-active" style="width: 120px;">状态</td>
                <td colspan="3"><?=IndexDefs::$orderStatusHtmlDefs[$orderInfos['status']]?></td>
            </tr>
        </table>
    </div>
    <div data-options="title:'客户信息',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=url('index/Customer/view', ['customerId'=>$orderInfos['customer_id']])?>'">
    </div>
    <div data-options="title:'专家信息',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=url('index/Expert/view', ['expertId'=>$orderInfos['expert_id']])?>'">
    </div>
</div>