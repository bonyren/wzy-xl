<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="iconCls:'fa fa-compass',
		region:'west',
		title:'维度因子',
		split:false,
        collapsible:false,
		width:'45%'">
        <table id="standardsDatagrid" class="easyui-datagrid" data-options="striped:true,
            nowrap:false,
            rownumbers:false,
            autoRowHeight:true,
            singleSelect:true,
            url:'<?=$urlHrefs['standards']?>',
            method:'post',
            toolbar:'#standardsToolbar',
            pagination:false,
            border:false,
            fit:true,
            fitColumns:<?=$loginMobile?'false':'true'?>,
            title:'',
            onSelect:saveStandardModule.onStandardSelect">
            <thead>
            <tr>
                <th data-options="field:'opt',width:100,align:'center',formatter:saveStandardModule.formatStandardOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
                <th data-options="field:'latitude',width:100,align:'center'">维度</th>
                <th data-options="field:'sort',width:60,formatter:saveStandardModule.formatStandardSort">排序</th>
                <th data-options="field:'remark',width:200,align:'left'">备注</th>
            </tr>
            </thead>
        </table>
        <div id="standardsToolbar" class="p-1">
            <div>
                <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ saveStandardModule.editStandard(0); },iconCls:iconClsDefs.add">添加维度因子</a>
            </div>
        </div>
    </div>
    <div data-options="region:'center',split:false,title:'题目项目',iconCls:'fa fa-cubes'">
        <table id="standardItemsDatagrid" class="easyui-datagrid" data-options="striped:true,
            nowrap:false,
            rownumbers:false,
            autoRowHeight:true,
            singleSelect:true,
            showFooter:true,
            url:'',
            method:'post',
            pagination:false,
            border:false,
            fit:true,
            fitColumns:true,
            title:''">
            <thead>
            <tr>
                <th data-options="field:'idx',width:10,align:'center', formatter:saveStandardModule.styleIdx">序号</th>
                <th data-options="field:'item',width:100,align:'left', styler:saveStandardModule.styleItem">题目</th>
            </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    var saveStandardModule = {
        dialog:'#globel-dialog2-div',
        styleIdx:function(val, row, index){
            return String(index+1);
        },
        styleItem:function(val, row, index){
            if(row.sis_id){
                return {
                    class: 'bg-info text-white'
                };
            }
        },
        //维度操作
        formatStandardOpt:function(val, row){
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-outline-primary size-MINI radius my-1" onclick="saveStandardModule.editStandard(' + row.standard_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.latitude) + '\')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
            btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius my-1" onclick="saveStandardModule.deleteStandard(' + row.standard_id + ',\'' + GLOBAL.func.escapeALinkStringParam(row.latitude) + '\')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
            return btns.join(' ');
        },
        formatStandardSort:function(val, row){
            if(val === undefined){
                return '';
            }else{
                return val + '<a href="javascript:;" class="btn btn-default size-MINI radius my-1" onclick="saveStandardModule.setStandardSort(' + row.standard_id + ',' + val + ')" title="编辑"><i class="fa fa-pencil"></i></a>';
            }
        },
        setStandardSort:function(standardId, sort){
            var that = this;
            var url = '<?=$urlHrefs['setStandardSort']?>';
            var prompt = $.messager.prompt({
                title: '提示',
                msg: '请输入序号，序号越小越排前',
                fn: function(value){
                    if(value === undefined){
                        //cancel click
                        return;
                    }
                    if(!$.isNumeric(value)){
                        $.app.method.alertError(null, "请输入数字格式");
                        return;
                    }
                    $.post(url,{ standardId: standardId, sort:value },function (res) {
                        if(!res.code){
                            $.app.method.alertError(null, res.msg);
                        }else{
                            $.app.method.tip('提示', res.msg, 'info');
                            that.reloadStandards();
                        }
                    },'json');
                }
            });
            //设置默认值
            prompt.find('.messager-input').val(sort);
        },
        reloadStandards:function(){
            saveStandardModule.standardIdSelected = 0;
            $('#standardItemsDatagrid').datagrid('loadData', []);
            $('#standardsDatagrid').datagrid('reload');
        },
        editStandard:function(standardId, title=''){
            var that = this;
            var href = '<?=$urlHrefs['editStandard']?>';
            href = GLOBAL.func.addUrlParam(href, 'standardId', standardId);
            if(!standardId){
                title = "添加维度";
            }else{
                title = "修改维度 - " + title;
            }
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
                                that.reloadStandards();
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
        deleteStandard:function(standardId, title=''){
            var that = this;
            var href = '<?=$urlHrefs['deleteStandard']?>';
            href = GLOBAL.func.addUrlParam(href, 'standardId', standardId);
            $.messager.confirm('提示', '确认删除'+title+'吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reloadStandards();
                        //清空右边
                        $('#standardItemsDatagrid').datagrid('loadData', {'rows':[], 'footer':[]});
                    }
                }, 'json');
            });
        },
        onStandardSelect:function(index, row){
            saveStandardModule.standardIdSelected = row.standard_id;
            var url = '<?=$urlHrefs['standardItems']?>';
            url = GLOBAL.func.addUrlParam(url, 'standardId', row.standard_id);
            $('#standardItemsDatagrid').datagrid('load', url);
        },
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        standardIdSelected: 0,
    };
</script>