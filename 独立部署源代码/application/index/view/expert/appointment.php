<div class="easyui-tabs" data-options="fit:true,tabPosition:'top',justified:false,border:false,tabWidth:200,headerWidth:200">
    <div title="预约订单" data-options="cache:false,iconCls:'fa fa-clock-o',border:false,href:'<?=url('index/AppointOrder/index', ['expertId'=>$expertId])?>'">
    </div>
    <div title="预约时间表" data-options="cache:false,iconCls:'fa fa-table',border:false,href:'<?=url('index/Expert/manageAppointment', ['expertId'=>$expertId])?>'">
    </div>
</div>