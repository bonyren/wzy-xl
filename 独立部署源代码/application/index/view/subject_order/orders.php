<?php
use app\Defs;
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="
    striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['orders']?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    onDblClickRow:function(index, row){
        <?=JVAR?>.view(row.order_no);
    },
    onLoadSuccess:function(data){
        <?=JVAR?>.convert(data);
        <?=JVAR?>.merge(data);
        <?=JVAR?>.init();
    },
    onBeforeLoad:<?=JVAR?>.onBeforeLoad,
    border:false">
    <thead>
    <tr>
        <?php if($combination_id){ ?>
            <th data-options="field:'nickname',width:80,align:'left'">微信昵称</th>
            <th data-options="field:'combination_order_finished',width:60,align:'left',formatter:GLOBAL.func.formatBoolean">是否完成</th>
        <?php } ?>
        <?php if($survey_id){ ?>
            <th data-options="field:'survey_personal_data',width:200,align:'left'">个人资料</th>
            <th data-options="field:'survey_order_finished',width:60,align:'left',formatter:GLOBAL.func.formatBoolean">是否完成</th>
        <?php } ?>
        <th data-options="field:'btns',width:60,fixed:true,align:'center',formatter:<?=JVAR?>.formatOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'order_no',width:120,align:'left'">订单号</th>
        <th data-options="field:'nickname',width:80,align:'left'">昵称</th>
        <th data-options="field:'real_name',width:80,align:'left'">姓名</th>
        <th data-options="field:'subject_name',width:160,align:'left'">测评量表</th>
        <?php if(!$combination_id && !$survey_id){ ?>
        <th data-options="field:'belongs_to',width:120,align:'left'">普查/组合</th>
        <?php } ?>
        <th data-options="field:'time_cost',width:150,align:'left'">时间/耗时(分钟)</th>
        <th data-options="field:'order_amount',width:80,align:'left'">金额(元)</th>
        <th data-options="field:'completion',width:80,align:'left'">完成度</th>
        <th data-options="field:'status',width:80,align:'left',formatter:<?=JVAR?>.formatStatus">订单状态</th>
        <th data-options="field:'pay_status',width:80,align:'left',formatter:<?=JVAR?>.formatPayStatus">支付状态</th>
        <th data-options="field:'warning_level',width:60,align:'left',formatter:<?=JVAR?>.formatWarningLevel">预警</th>
    </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-toolbar-search-form" method="post" action="<?=$urlHrefs['export']?>">
        <div class="datagrid-toolbar-search-form-fields border-right mr-1">
            <input name="search[order_no]" class="easyui-textbox" prompt="订单号" data-options="validType:['length[0,32]'],width:120" />
            <select class="easyui-combobox" id="orders_subject_id_<?=UNIQID?>" name="search[subject_id]" style="width:200px;"
                data-options="prompt:'测评量表',limitToList:true,valueField:'id',textField:'name',url:'<?=url('index/Subject/getSubjectComboData')?>'">
            </select>
            <input name="search[nickname]" class="easyui-textbox" prompt="用户昵称" data-options="validType:['length[0,40]']" style="width:120px;">
            <input name="search[real_name]" class="easyui-textbox" prompt="用户姓名" data-options="validType:['length[0,16]']" style="width:120px;">
            <select name="search[status]" class="easyui-combobox" prompt="订单状态" editable="false" data-options="panelHeight:'auto',value:''" style="width:120px;">
                <?php foreach (IndexDefs::$subjectOrderStatusDefs as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
            <!------------------------------------------------------>
            <select name="search[pay_status]" class="easyui-combobox" prompt="支付状态" editable="false" data-options="panelHeight:'auto',value:''" style="width:120px;">
                <?php foreach (Defs::PAYS as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
            <!------------------------------------------------------>
            <select name="search[warning_level]" class="easyui-combobox" prompt="预警级别" editable="false" data-options="panelHeight:'auto',value:''" style="width:120px;">
                <?php foreach (Defs::MEASURE_WARNINGS as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
            <!------------------------------------------------------>
            <span class="ml-1"></span>
        </div>
        <div class="datagrid-toolbar-search-form-btns">
            <a class="easyui-linkbutton search-submit" iconCls="fa fa-search" onclick="<?=JVAR?>.search()">搜索</a>
            <a class="easyui-linkbutton search-reset" iconCls="fa fa-rotate-left" onclick="<?=JVAR?>.reset()">重置</a>
            <a class="easyui-linkbutton" iconCls="fa fa-share-square-o" onclick="<?=JVAR?>.export()">导出</a>
        </div>
    </form>
</div>
<script>
    var <?=JVAR?> = {
        dialog:'#globel-dialog-div',
        datagrid:'#<?=DATAGRID_ID?>',
        toolbar:'#<?=TOOLBAR_ID?>',
        searchForm:'#<?=FORM_ID?>',
        init:function(){
            var that = <?=JVAR?>;
            if($(<?=JVAR?>.dialog).closest('.window').is(':visible')){
                <?=JVAR?>.dialog = '#globel-dialog2-div';
            }
            /**********************************/
            var total = $(that.datagrid).datagrid('getRows').length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-subject-orders-operate-row-' + i).splitbutton();
            }
            $(that.datagrid).datagrid('fixRowHeight');
            //$(that.datagrid).datagrid('resize');
        },
        formatOpt:function(val, row, index){
            var html = '<a href="javascript:void(0)" id="<?=UNIQID?>-subject-orders-operate-row-' + index + '"' +
                        'data-options="menu:\'#<?=UNIQID?>-subject-orders-operate-row-menu-'  + index + '\',iconCls:\'fa fa-ellipsis-v\'" ></a>' +
                    '<div id="<?=UNIQID?>-subject-orders-operate-row-menu-' + index + '" style="width:60px;">';
            if(!parseInt(row.cb_order_id) && !parseInt(row.survey_order_id)){
                //不是组合和普查
                html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcode(\''+row.order_no+'\',\'' + GLOBAL.func.escapeALinkStringParam(row.subject_name) + '\')">测评二维码</div>';
            }
            html += '<div data-options="iconCls:\'fa fa-eye\'" onclick="<?=JVAR?>.view(\''+row.order_no+'\')">订单详情</div>';
            html += '<div data-options="iconCls:\'fa fa-gavel\'" onclick="<?=JVAR?>.detail(\''+row.order_no+'\')">评估结果</div>';
            if(parseInt(row.finished)) {
                html += '<div data-options="iconCls:\'fa fa-file-text-o\'" onclick="<?=JVAR?>.report(\''+row.order_no+'\')">测评报告</div>';
                html += '<div data-options="iconCls:\'fa fa-file-pdf-o\'" onclick="<?=JVAR?>.reportPdf(\''+row.order_no+'\')">下载pdf报告</div>';
            }
            html += '</div>';
            return html;
        },
        onBeforeLoad:function(){
            var that = <?=JVAR?>;
            var rows = $(that.datagrid).datagrid('getRows');
            if(!rows){
                return;
            }
            var total = rows.length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-subject-orders-operate-row-' + i).remove();
                $('#<?=UNIQID?>-subject-orders-operate-row-menu-' + i).remove();
            }
        },
        convert:function(data){
            var that = <?=JVAR?>;
            /*
            data.rows.forEach(function(v,i){
                $(that.datagrid).datagrid('updateRow',{
                    index:i,
                    row:{
                        //评估起止时间
                        //times:v.order_time + (v.finish_time ? '<br />'+v.finish_time : ''),
                        //完成度
                        completion:'<div class="my-3">'+v.test_items+'/'+v.total_items + '</div>',
                    }
                });
            });*/
        },
        merge:function(data){
            var that = <?=JVAR?>;
            <?php if($combination_id){ ?>
                var cb_order_id = 0;
                var rowspan = 0;
                data.rows.forEach(function(v,i){
                    if(!cb_order_id) {
                        cb_order_id = v.cb_order_id;
                        rowspan = 1;
                    }else if(cb_order_id == v.cb_order_id){
                        ++rowspan;
                        if(i == data.rows.length - 1){
                            //最后
                            if(rowspan > 1){
                                //merge
                                $(that.datagrid).datagrid('mergeCells',{
                                    index:i-(rowspan-1),
                                    field:'combination_order_finished',
                                    rowspan:rowspan
                                });
                                $(that.datagrid).datagrid('mergeCells',{
                                    index:i-(rowspan-1),
                                    field:'nickname',
                                    rowspan:rowspan
                                });
                                $(that.datagrid).datagrid('mergeCells',{
                                    index:i-(rowspan-1),
                                    field:'real_name',
                                    rowspan:rowspan
                                });
                            }
                        }
                    }else{
                        if(rowspan > 1){
                            //merge
                            $(that.datagrid).datagrid('mergeCells',{
                                index:i-rowspan,
                                field:'combination_order_finished',
                                rowspan:rowspan
                            });
                            $(that.datagrid).datagrid('mergeCells',{
                                index:i-rowspan,
                                field:'nickname',
                                rowspan:rowspan
                            });
                            $(that.datagrid).datagrid('mergeCells',{
                                index:i-rowspan,
                                field:'real_name',
                                rowspan:rowspan
                            });
                        }
                        cb_order_id = v.cb_order_id;
                        rowspan = 1;
                    }
                });
            <?php } ?>
            <?php if($survey_id){ ?>
                var survey_order_id = 0;
                var rowspan = 0;
                data.rows.forEach(function(v,i){
                    if(!survey_order_id) {
                        survey_order_id = v.survey_order_id;
                        rowspan = 1;
                    }else if(survey_order_id == v.survey_order_id){
                        ++rowspan;
                        if(i == data.rows.length - 1){
                            //最后
                            if(rowspan > 1){
                                //merge
                                $(that.datagrid).datagrid('mergeCells',{
                                    index:i-(rowspan-1),
                                    field:'survey_order_finished',
                                    rowspan:rowspan
                                });
                                $(that.datagrid).datagrid('mergeCells',{
                                    index:i-(rowspan-1),
                                    field:'survey_personal_data',
                                    rowspan:rowspan
                                });
                                $(that.datagrid).datagrid('mergeCells',{
                                    index:i-(rowspan-1),
                                    field:'nickname',
                                    rowspan:rowspan
                                });
                                $(that.datagrid).datagrid('mergeCells',{
                                    index:i-(rowspan-1),
                                    field:'real_name',
                                    rowspan:rowspan
                                });
                            }
                        }
                    }else{
                        if(rowspan > 1){
                            //merge
                            $(that.datagrid).datagrid('mergeCells',{
                                index:i-rowspan,
                                field:'survey_order_finished',
                                rowspan:rowspan
                            });
                            $(that.datagrid).datagrid('mergeCells',{
                                index:i-rowspan,
                                field:'survey_personal_data',
                                rowspan:rowspan
                            });
                            $(that.datagrid).datagrid('mergeCells',{
                                index:i-rowspan,
                                field:'nickname',
                                rowspan:rowspan
                            });
                            $(that.datagrid).datagrid('mergeCells',{
                                index:i-rowspan,
                                field:'real_name',
                                rowspan:rowspan
                            });
                        }
                        survey_order_id = v.survey_order_id;
                        rowspan = 1;
                    }
                });
            <?php } ?>
        },
        formatStatus:function(val, row){
            return <?=json_encode(IndexDefs::$subjectOrderStatusHtmlDefs, JSON_UNESCAPED_SLASHES)?>[row.finished];
        },
        formatPayStatus:function(val, row){
            return <?=json_encode(Defs::PAYS_HTML, JSON_UNESCAPED_SLASHES)?>[val];
        },
        formatChannel:function(val){
            return <?=json_encode(Defs::CHANNELS_HTML, JSON_UNESCAPED_SLASHES)?>[val];
        },
        formatWarningLevel:function(val, row){
            if(parseInt(val) == 0) return '';
            return <?=json_encode(Defs::MEASURE_WARNINGS_HTML)?>[val];
        },
        reload:function(){
            var that = <?=JVAR?>;
            $(that.datagrid).datagrid('reload');
        },
        search:function(){
            var that = <?=JVAR?>;
            var isValid = $(that.searchForm).form('validate');
            if(!isValid){
                return;
            }
            var data = {};
            $.each($(that.toolbar).children('form').serializeArray(), function() {
                data[this['name']] = this['value'];
            });
            $(that.datagrid).datagrid('load', data);
        },
        reset:function(){
            var that = <?=JVAR?>;
            $(that.toolbar).find('.easyui-textbox').textbox('clear');
            $(that.toolbar).find('.easyui-combobox').combobox('reset');
            $(that.datagrid).datagrid('load', {});
        },
        qrcode:function (order_no, title){
            var that = <?=JVAR?>;
            var testUrl = '<?=url('mp/Subject/test', '', true, true)?>';
            testUrl = GLOBAL.func.addUrlParam(testUrl, 'order_no', order_no);
            commonModule.qrcodeInfo(testUrl, order_no + ' - 测评二维码', $(that.dialog));
        },
        view:function(order_no){
            var that = <?=JVAR?>;
            var href = '<?=url('index/subjectOrder/view')?>?order_no='+order_no;
            $(that.dialog).dialog({
                title: order_no + ' - 测评订单查看',
                width: "<?=$loginMobile?'100%':'60%'?>",
                height: "100%",
                href: href,
                iconCls:'fa fa-eye',
                modal: true,
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
        },
        detail:function(order_no){
            var that = <?=JVAR?>;
            var href = '<?=url('subjectOrder/orderDetail')?>';
            href = GLOBAL.func.addUrlParam(href, 'order_no', order_no);
            $(that.dialog).dialog({
                title: '评估结果',
                width: "<?=$loginMobile?'100%':'60%'?>",
                height: "<?=$loginMobile?'100%':'95%'?>",
                href: href,
                iconCls:'fa fa-leaf',
                modal: true,
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
        },
        report:function (order_no){
            var that = <?=JVAR?>;
            var href = '<?=url('subjectOrder/orderReport')?>?order_no='+order_no;
            $(that.dialog).dialog({
                title: '量表测评报告',
                width: "<?=$loginMobile?'100%':'60%'?>",
                height: "100%",
                href: href,
                iconCls:'fa fa-file-text-o',
                modal: true,
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
        },
        reportPdf:function(order_no){
            var that = <?=JVAR?>;
            var href = '<?=url('subjectOrder/orderReportPdf')?>?order_no='+order_no;
            window.open(href);
        },
        export:function(){
            var that = <?=JVAR?>;
            $(that.searchForm).submit();
        }
    };
</script>