<div class="m-2">
<?php $i=0;foreach($appointIntervals as $intervalItem){ $i++;?>
    <?php
    $orderLinkman = '';
    $orderNo = '';
    $intervalType = 0;
    $colorCls = '';
    foreach($appointOrders as $item){
        $appointTimes = explode(',', $item['appoint_time']);
        if(in_array($intervalItem, $appointTimes)){
            //find
            if(count($appointTimes) == 1){
                $orderLinkman = $item['linkman'];
                $orderNo = $item['order_no'];
                $intervalType = 1;//15 minutes
                $colorCls = 'c6';
                break;
            }else if(count($appointTimes) == 3){
                $orderLinkman = $item['linkman'];
                $orderNo = $item['order_no'];
                $intervalType = 2;//45 minutes
                $colorCls = 'c1';
                break;
            }
        }
    }
    ?>
    <?php if($orderNo){ ?>
		<!--
        <a href="javascript:;" id="splitbutton-<?=$date?>-<?=str_replace(':', '_', $intervalItem)?>" class="easyui-splitbutton <?=$colorCls?> my-1"
           data-options="menu:'#menu-<?=$date?>-<?=str_replace(':', '_', $intervalItem)?>', plain:false, width:120, onClick:function(){
                expertAppointmentModule.showAppointment('<?=$orderNo?>');
           }">
            <span title="<?=$orderLinkman?'[' . $orderLinkman . ']':''?>"><?=$intervalItem?></span>
        </a>
        <div id="menu-<?=$date?>-<?=str_replace(':', '_', $intervalItem)?>">
            <div data-options="iconCls:'fa fa-close'" onclick="expertAppointmentModule.cancel('<?=$orderNo?>', '<?=$orderLinkman?>')">取消预约</div>
            <div data-options="iconCls:'fa fa-check'" onclick="expertAppointmentModule.finish('<?=$orderNo?>', '<?=$orderLinkman?>')">完成预约</div>
        </div>
		-->
		<a href="javascript:;" id="linkbutton-<?=$date?>-<?=str_replace(':', '_', $intervalItem)?>" class="easyui-linkbutton <?=$colorCls?> my-1"
           data-options="plain:false, width:100, onClick:function(){}">
            <?=$intervalItem?>
        </a>
    <?php }else{ ?>
        <a href="javascript:;" id="linkbutton-<?=$date?>-<?=str_replace(':', '_', $intervalItem)?>" class="easyui-linkbutton my-1"
           data-options="plain:false, width:100, onClick:function(){}">
            <?=$intervalItem?>
        </a>
    <?php } ?>
    <?php if($i%8 == 0){ echo '<br />'; } ?>
<?php } ?>
</div>