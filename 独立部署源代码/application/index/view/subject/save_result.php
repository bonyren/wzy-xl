<?php
use app\Defs;
?>
<div id="saveResultLayout" class="easyui-layout" data-options="fit:true">
    <div data-options="iconCls:'fa fa-compass',
		region:'west',
		title:'维度因子',
		split:false,
        collapsible:false,
		width:'45%'">
        <table id="saveResultStandardsDatagrid" class="easyui-datagrid" data-options="striped:true,
            nowrap:false,
            rownumbers:false,
            autoRowHeight:true,
            singleSelect:true,
            url:'<?=$urlHrefs['standardsResult']?>',
            method:'post',
            pagination:false,
            border:false,
            fit:true,
            fitColumns:<?=$loginMobile?'false':'true'?>,
            title:'',
            rowStyler:saveResultModule.standardRowStyler,
            onSelect:saveResultModule.onStandardSelect">
            <thead>
                <tr>
                    <th data-options="field:'opt',width:200,align:'center',formatter:saveResultModule.formatStandardOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">标准分转换</th>
                    <th data-options="field:'latitude',width:200,align:'center'">维度因子</th>
                    <th data-options="field:'item_count',width:100,align:'center'">项目数</th>
                    <th data-options="field:'weight_min_max',width:200,align:'center'">原始分区间</th>
                    <th data-options="field:'standard_weight_min_max',width:200,align:'center'">标准分区间</th>
                </tr>
            </thead>
        </table>
    </div>
    <div data-options="region:'center',split:false,title:'解读',iconCls:'fa fa-legal'">
        <table id="subjectResultDatagrid" class="easyui-datagrid" data-options="striped:true,
            nowrap:false,
            rownumbers:false,
            autoRowHeight:true,
            singleSelect:true,
            url:'',
            method:'post',
            toolbar:'#subjectResultToolbar',
            pagination:false,
            border:false,
            fit:true,
            fitColumns:<?=$loginMobile?'false':'true'?>,
            title:''">
            <thead>
            <tr>
                <th data-options="field:'opt',width:80,align:'center',formatter:saveResultModule.formatResultOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
                <th data-options="field:'latitude',width:60,align:'center'">维度因子</th>
                <th data-options="field:'weight_type',width:60,align:'center',formatter:saveResultModule.formatWeightType">分数类型</th>
                <th data-options="field:'expression',width:100,align:'center'">评判表达式</th>
                <th data-options="field:'stand_desc',width:200,align:'center'">结论解析</th>
                <th data-options="field:'warning_level',width:100,align:'center',formatter:saveResultModule.formatWarningLevel">预警</th>
                <th data-options="field:'remark',width:100,align:'center'">应对建议</th>
            </tr>
            </thead>
        </table>
        <div id="subjectResultToolbar" class="p-1">
            <div>
                <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ saveResultModule.editResult(0); },iconCls:iconClsDefs.add">添加结果定义</a>
                <!--
                &nbsp;
                <strong>预定义常量: </strong>
                总分: <span class="text-primary">${TW}</span>； 
                平均分: <span class="text-primary">${AW}</span>； 
                阳性项目数: <span class="text-primary">${PIC}</span>； 
                阴性项目数: <span class="text-primary">${NIC}</span>； 
                阳性平均分: <span class="text-primary">${PAW}</span>；
                -->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var saveResultModule = {
        dialog:'#globel-dialog2-div',
        standardIdSelected: null,
        standardRowStyler:function(index, row){
            if(row.standard_id == 0){
                //return DG_ROW_CSS.rowInfo;
            }
        },
        onStandardSelect:function(index, row){
            saveResultModule.standardIdSelected = row.standard_id;
            var title = row.latitude;
            $('#saveResultLayout').layout('panel', 'center').panel('setTitle', title);
            ///////////////////////////////////////////////////////////////////////////
            var url = '<?=$urlHrefs['latitudes']?>';
            url = GLOBAL.func.addUrlParam(url, 'standardId', row.standard_id);
            $('#subjectResultDatagrid').datagrid('load', url);
        },
        reloadResultStandards:function(){
            $('#saveResultStandardsDatagrid').datagrid('reload');
        },
        //分数映射
        formatStandardOpt:function(val, row){
            var btns = [];
            if(row.standard_weight_min_max){
                btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="saveResultModule.editStandardPoint(' + row.standard_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.latitude) + '\')" title="修改"><i class="fa fa-pencil-square-o fa-lg">修改</i></a>');
                btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="saveResultModule.deleteStandardPoint(' + row.standard_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.latitude) + '\')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
            }else{
                btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="saveResultModule.editStandardPoint(' + row.standard_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.latitude) + '\')" title="增加"><i class="fa fa-plus fa-lg">增加</i></a>');
            }
            //管理员使用
            //btns.push('<a href="javascript:;" class="btn btn-outline-secondary size-MINI radius my-1" onclick="saveResultModule.cloneResult(' + row.standard_id + ')" title="克隆结果定义"><i class="fa fa-clone fa-lg">克隆结果</i></a>');
            return btns.join(' ');
        },
        editStandardPoint:function(standardId, title){
            var that = this;
            var href = '<?=$urlHrefs['editStandardPoint']?>';
            href = GLOBAL.func.addUrlParam(href, 'standardId', standardId);
            title = "标准分转换 - (" + title + ")";
            $(that.dialog).dialog({
                title: title,
                width: <?=$loginMobile?"'100%'":450?>,
                height: 300,
                href: href,
                modal: true,
                cache:false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        var $form = $(that.dialog).find('form').eq(0);
                        if($form.length == 0){
                            $(that.dialog).dialog('close');
                            return;
                        }
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, $form.serialize(), function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reloadResultStandards();
                            }
                        }, 'json');
                    }
                },{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        deleteStandardPoint:function(standardId, title=''){
            var that = this;
            var href = '<?=$urlHrefs['deleteStandardPoint']?>';
            href = GLOBAL.func.addUrlParam(href, 'standardId', standardId);
            $.messager.confirm('提示', '确认删除"'+title+'"标准分转换吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reloadResultStandards();
                    }
                }, 'json');
            });
        },
        cloneResult:function(standardId){
            var that = this;
            var href = '<?=$urlHrefs['saveResultClone']?>';
            href = GLOBAL.func.addUrlParam(href, 'standardId', standardId);
            title = "克隆结果定义";
            $(that.dialog).dialog({
                title: title,
                width: <?=$loginMobile?"'100%'":450?>,
                height: 300,
                href: href,
                modal: true,
                cache:false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        var $form = $(that.dialog).find('form').eq(0);
                        if($form.length == 0){
                            $(that.dialog).dialog('close');
                            return;
                        }
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, $form.serialize(), function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reloadResult();
                            }
                        }, 'json');
                    }
                },{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        formatResultOpt:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="saveResultModule.editResult(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.latitude) + '\')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="saveResultModule.deleteResult(' + row.id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.latitude) + '\')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
            return btns.join(' ');
        },
        formatWeightType:function(val, row){
            return <?=json_encode(Defs::LATITUDE_MEASURE_WEIGHT_TYPES)?>[val];
        },
        formatWarningLevel:function(val, row){
            return <?=json_encode(Defs::MEASURE_WARNINGS_HTML)?>[val];
        },
        reloadResult:function(){
            $('#subjectResultDatagrid').datagrid('reload');
        },
        editResult:function(id, title=''){
            var that = this;
            if(saveResultModule.standardIdSelected === null){
                $.app.method.alertError(null, '请选择所在的维度');
                return;
            }
            var href = '<?=$urlHrefs['setStandardLatitude']?>';
            href = GLOBAL.func.addUrlParam(href, 'standardId', saveResultModule.standardIdSelected);
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            if(!id){
                title = "添加结果定义";
            }else{
                title = "修改结果定义 - " + title;
            }
            $(that.dialog).dialog({
                title: title,
                width: <?=$loginMobile?"'100%'":600?>,
                height: '90%',
                href: href,
                modal: true,
                cache:false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        if(!setStandardLatitudeModule.applyRule()){
                            $.app.method.alertError(null, "请设置评判表达式");
                            return;
                        }
                        var $form = $(that.dialog).find('form').eq(0);
                        if($form.length == 0){
                            $(that.dialog).dialog('close');
                            return;
                        }
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, $form.serialize(), function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reloadResult();
                            }
                        }, 'json');
                    }
                },{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        deleteResult:function(id, title=''){
            var that = this;
            var href = '<?=$urlHrefs['delStandardLatitude']?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $.messager.confirm('提示', '确认删除"'+title+'"吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reloadResult();
                    }
                }, 'json');
            });
        }
    };
</script>