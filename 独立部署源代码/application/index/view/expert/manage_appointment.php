<table id="expertAppointmentDatagrid" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:false,
    singleSelect:true,
    url:'<?=url('index/Expert/manageAppointment')?>',
    method:'post',
    toolbar:'#expertAppointmentToolbar',
    pagination:true,
    pageSize:10,
    pageList:[10,20,30,50,80,100],
    border:false,
    fit:true,
    fitColumns:true,
    title:'',
    onLoadSuccess:function(data){
        $.each(data.rows, function(i, row){
            $('#appointment-interval-' + i).panel({height:250});
        });
    }
    ">
    <thead>
    <tr>
        <th data-options="field:'week_day_text',width:200,align:'center',styler:expertAppointmentModule.styleDate">日期</th>
        <th data-options="field:'xxx',width:1250,align:'center',formatter:expertAppointmentModule.formatIntervals"></th>
    </tr>
    </thead>
</table>
<div id="expertAppointmentToolbar" class="p-1">
    <div>
        <a href="javascript:;" class="easyui-linkbutton c1"></a>45分钟(咨询)时间片;
        <a href="javascript:;" class="easyui-linkbutton c6"></a>15分钟(复诊)时间片;
        <a href="javascript:;" class="easyui-linkbutton"></a>未分配时间片;
    </div>
</div>
<script type="text/javascript">
    var expertAppointmentModule = {
        dialog: '#globel-dialog-div',
        dialog2:'#globel-dialog2-div',
        datagrid: '#expertAppointmentDatagrid',
        reload:function(){
            $(expertAppointmentModule.datagrid).datagrid('reload');
        },
        formatIntervals:function(val, row, index){
            var href = '<?=url('index/Expert/manageAppointmentInterval', ['expertId'=>$expertId])?>';
            href += href.indexOf('?') == -1?'?date='+row.date:'&date='+row.date;
            return '<div id="appointment-interval-' + index + '" class="easyui-panel" data-options="height:250,href:\''+href+'\'"></div>';
        },
        styleDate:function(val, row, index){
            return {
                class:'font-weight-bold'
            };
        },
        showAppointment:function(orderNo){
            if($.trim(orderNo) == ''){
                return;
            }
            var that = this;
            var href = '<?=url('index/AppointOrder/view')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $(that.dialog2).dialog({
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
                buttons:[{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog2).dialog('center');
        },
        change:function(){
            alert('change');
        },
        cancel:function(orderNo, name){
            //alert('cancel');
            var that = this;
            var href = '<?=url('index/AppointOrder/cancel')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $.messager.confirm('提示', '确认取消来自['+name+']订单吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        expertAppointmentModule.reload();
                    }
                }, 'json');
            });
        },
        finish:function(orderNo, name){
            //alert('finish');
            var that = this;
            var href = '<?=url('index/AppointOrder/finish')?>';
            href += href.indexOf('?') != -1 ? '&orderNo=' + orderNo : '?orderNo='+orderNo;
            $.messager.confirm('提示', '确认完成来自['+name+']订单吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        expertAppointmentModule.reload();
                    }
                }, 'json');
            });
        }
    }
</script>