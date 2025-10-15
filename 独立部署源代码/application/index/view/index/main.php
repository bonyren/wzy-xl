<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',
        collapsible:true,
        border:false,
        iconCls:'fa fa-pie-chart',
        title:'',
        collapsed:false,
        href:'<?=$urlHrefs['dashboard']?>'" style="height: 30%;">
    </div>
    <div data-options="region:'center',border:false">
        <div class="easyui-tabs" data-options="fit:true,border:false">
            <div data-options="title:'数据统计',iconCls:'fa fa-line-chart',href:'<?=$urlHrefs['trend']?>'"></div>
            <div data-options="title:'最新预约',iconCls:'fa fa-clock-o',href:'<?=$urlHrefs['latestAppointments']?>'"></div>
            <div data-options="title:'最新测评',iconCls:'fa fa-balance-scale',href:'<?=$urlHrefs['latestSubjects']?>'"></div>
            <div data-options="title:'事件',iconCls:'fa fa-list-ul',href:'<?=$urlHrefs['eventLogs']?>'"></div>
        </div>
    </div>
</div>