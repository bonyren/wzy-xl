<?php
use app\Defs;
use app\index\logic\Defs as IndexDefs;
?>
<div class="easyui-tabs" data-options="fit:true,border:false">
    <div data-options="title:'订单信息',cache:false,iconCls:'fa fa-circle',border:false,selected:true">
        <table class="table table-bordered">
            <tr>
                <td class="table-active">测评订单号</td>
                <td style="width: 40%;"><?=$orderInfos['order_no']?></td>
                <td class="table-active" style="width: 15%;">测评金额</td>
                <td><?=$orderInfos['order_amount']?></td>
            </tr>
            <tr>
                <td class="table-active">订单时间</td>
                <td><?=$orderInfos['order_time']?></td>
                <td class="table-active">完成时间</td>
                <td><?=$orderInfos['finish_time']?></td>
            </tr>
            <tr>
                <td class="table-active">订单状态</td>
                <td>
                    <?=IndexDefs::$subjectOrderStatusHtmlDefs[$orderInfos['finished']]?>
                </td>
                <td class="table-active">支付状态</td>
                <td>
                    <?=Defs::PAYS_HTML[$orderInfos['pay_status']]?>
                </td>
            </tr>
        </table>
    </div>
    <div data-options="title:'客户信息',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=$urlHrefs['customer']?>'">
    </div>
    <div data-options="title:'量表信息',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=$urlHrefs['subject']?>'">
    </div>
    <?php if($orderInfos['question_form']){ ?>
        <div data-options="title:'测前调查',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=$urlHrefs['orderQuestionForm']?>'">
        </div>
    <?php } ?>
    <div data-options="title:'评估结果',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=$urlHrefs['order']?>'">
    </div>
    <?php if($orderInfos['cb_order_id']){ ?>
        <div data-options="title:'所属组合',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=$urlHrefs['combination']?>'">
        </div>
    <?php } ?>
    <?php if($orderInfos['survey_order_id']){ ?>
        <div data-options="title:'所属普查',cache:false,iconCls:'fa fa-circle',border:false,href:'<?=$urlHrefs['survey']?>'">
        </div>
    <?php } ?>
</div>