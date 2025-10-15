<table class="appoint-schedule-table">
    <tr>
        <td class="blank">时间区间</td>
        <?php foreach($dateFields as $dateField){ ?>
            <td class="title"><?=$dateField?></td>
        <?php } ?>
    </tr>
    <?php foreach($appointments as $appointment){ ?>
        <tr>
            <td class="time"><?=$appointment['interval']?></td>
            <?php foreach($appointment['dateOrders'] as $date=>$orders){ ?>
                <td class="drop" data-date="<?=$date?>" data-interval="<?=$appointment['interval']?>">
                    <?php foreach($orders as $order){ ?>
                        <div class="item text-l" title="<?=$order['order_no']?>" data-order-no="<?=$order['order_no']?>">
                            <a href="javascript:;" class="easyui-linkbutton" data-options="plain:true,iconCls:'fa fa-info-circle',onClick:function(){
                                appointmentScheduleModule.showAppointment('<?=$order['order_no']?>');
                            }"></a>
                            <?=$order['linkman']?>-<?=$order['appoint_duration']?>分钟
                            <?=\app\index\logic\Defs::$orderStatusHtmlDefs[$order['status']]?>
                        </div>
                    <?php } ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>

<style type="text/css">
    table.appoint-schedule-table{
        background:#E0ECFF;
        width:100%;
    }
    .appoint-schedule-table td{
        background:#fafafa;
        color:#444;
        text-align:center;
        padding:2px;
    }
    .appoint-schedule-table td{
        background:#E0ECFF;
    }
    .appoint-schedule-table td.blank{
        width: 100px;
    }
    .appoint-schedule-table td.drop{
        background:#fafafa;
        width:100px;
    }
    .appoint-schedule-table td.over{
        background:#FBEC88;
    }
    .appoint-schedule-table .item{
        border:1px solid #499B33;
        background:#fafafa;
        color:#444;
        margin-top: 5px;
        margin-left: 5px;
    }
    .appoint-schedule-table .assigned{
        border:1px solid #BC2A4D;
    }
    .appoint-schedule-table .trash{
        background-color:red;
    }

</style>
<script>
    $(function(){
        $('.appoint-schedule-table .item').draggable({
            revert:true,
            proxy:'clone'
        });
        $('.appoint-schedule-table td.drop').droppable({
            accept: '.item',
            onDragEnter:function(){
                $(this).addClass('over');
            },
            onDragLeave:function(){
                $(this).removeClass('over');
            },
            onDrop:function(e,source){
                $(this).removeClass('over');
                $(this).append(source);
                var orderNo = $(source).data('orderNo');
                var date = $(this).data('date');
                var interval = $(this).data('interval');
                console.log(orderNo, interval);
                var href = '<?=url('index/AppointOrder/changeTime')?>';
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {orderNo:orderNo,appointDate:date,appointTime:interval}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                        //无法撤销
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                    }
                }, 'json');
            }
        });
    });
    var appointmentScheduleModule = {
        dialog:'#globel-dialog2-div',
        showAppointment:function(orderNo){
            var that = this;
            var href = '<?=url('index/AppointOrder/view')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $(that.dialog).dialog({
                title: orderNo + ' - 订单查看',
                iconCls: 'fa fa-eye',
                width: <?=$loginMobile?"'100%'":"'70%'"?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
                collapsible: false,
                minimizable: false,
                resizable: false,
                maximizable: false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        }
    };
</script>