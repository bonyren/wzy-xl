<?php
use app\Defs;
?>
<table id="subjectItemsDatagrid" class="easyui-treegrid" data-options="striped:true,
            nowrap:true,
            rownumbers:false,
            autoRowHeight:true,
            singleSelect:true,
            url:'<?=$urlHrefs['saveItems']?>',
            method:'post',
            toolbar:'#subjectItemsToolbar',
            pagination:false,
            border:false,
            lines:true,
            fit:true,
            fitColumns:true,
            title:'',
            idField:'id',
            treeField:'item_option',
            rowStyler:saveItemsModule.rowStyler">
    <thead>
    <tr>
        <th data-options="field:'item_option',fixed:true,width:450,formatter:saveItemsModule.formatItemOption">题目(选项)</th>
        <th data-options="field:'opt',fixed:true,width:120,formatter:saveItemsModule.formatOpt,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <th data-options="field:'sort',fixed:true,width:60,formatter:saveItemsModule.formatSort">排序</th>
        <th data-options="field:'type',fixed:true,width:60,formatter:saveItemsModule.formatType">类型</th>
        <th data-options="field:'weight',fixed:true,width:60,formatter:saveItemsModule.formatWeight">分数</th>
        <th data-options="field:'nature',fixed:true,width:60,formatter:saveItemsModule.formatNature">性质</th>
        <th data-options="field:'image',fixed:true,width:100">图片</th>
        <?php if($standards){ ?>
            <th data-options="field:'standards',align:'center',width:100">
            维度因子[
            <?php foreach($standards as $index=>$standard){ 
                echo $index>0?', ':'';
                echo $standard['latitude'];
            }?>
            ]
            </th>
        <?php } ?>
    </tr>
    </thead>
</table>
<div id="subjectItemsToolbar" class="p-1">
    <div>
        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ saveItemsModule.editItem(0); },iconCls:iconClsDefs.add">添加测量题目</a>
        <a href="javascript:void(0)" class="easyui-menubutton" data-options="plain:false,menu:'#tag_item_menu',iconCls:iconClsDefs.add">添加调查题目</a>
        <div id="tag_item_menu" style="width:150px;">
            <?php foreach(Defs::SUBJECT_ITEM_TAG_DEFS as $key=>$label){ ?>
                <div onclick="saveItemsModule.addTagItem('<?=$key?>');"><?=$label?></div>
                <div class="menu-sep"></div>
            <?php } ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    var saveItemsModule = {
        dialog:'#globel-dialog2-div',
        reload:function(){
            $('#subjectItemsDatagrid').treegrid('reload');
        },
        scrollTo:function(index){
            $('#subjectItemsDatagrid').treegrid('scrollTo', index);
        },
        rowStyler:function(row){
            var id = String(row.id);
            if(id.indexOf('_') == -1) {
                //题目
                return DG_ROW_CSS.rowInfo;
            }
        },
        formatItemOption:function(val, row){
            return '<span title="' + val + '">' + val + '</span>';
        },
        formatOpt:function(val, row){
            var btns = [];
            var id = String(row.id);
            if(id.indexOf('_') == -1) {
                //题目
                if(row.tag == '<?=Defs::SUBJECT_ITEM_TAG_NONE?>'){
                    btns.push('<a href="javascript:;" class="btn btn-light size-MINI radius my-1" onclick="saveItemsModule.editItem(' + row.id + ')" title="编辑"><i class="fa fa-pencil-square-o fa-lg">编辑</i></a>');
                }
                btns.push('<a href="javascript:;" class="btn btn-light size-MINI radius my-1" onclick="saveItemsModule.deleteItem(' + row.id + ')" title="删除"><i class="fa fa-trash-o fa-lg">删除</i></a>');
            }
            return btns.join(' ');
        },
        formatSort:function(val, row){
            if(val === undefined){
                return '';
            }else{
                return val + '<a href="javascript:;" class="btn btn-default size-MINI radius my-1" onclick="saveItemsModule.setItemSort(' + row.id + ',' + val + ')" title="编辑"><i class="fa fa-pencil"></i></a>';
            }
        },
        formatType:function(val, row){
            var typeDefs = <?=json_encode(Defs::QUESTION_HTML_TYPES)?>;
            if(val in typeDefs){
                return typeDefs[val];
            }else{
                return '';
            }
        },
        formatWeight:function(val, row){
            if(val === undefined){
                return '';
            }else{
                return val;
            }
        },
        formatNature:function(val, row){
            var natureDefs = <?=json_encode(Defs::SUBJECT_ITEM_OPTION_HTML_NATURES)?>;
            if(val === undefined){
                return '';
            }else{
                return natureDefs[val];
            }
        },
        /*
        formatStandard:function(val, row, field){
            if(val === undefined){
            }else{
                return '<span class="fa fa-check"></span>';
            }
        },*/
        addTagItem:function(tag){
            var that = this;
            var href = '<?=$urlHrefs['addTagItem']?>';
            $.messager.confirm('提示', '确认添加吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {tag:tag}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        //reload the datagrid list
                        subjectSaveModule.savedFlag = true;
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    }
                }, 'json');
            });
        },
        editItem:function(id){
            var that = this;
            var href = '<?=$urlHrefs['saveItem']?>';
            href = GLOBAL.func.addUrlParam(href, 'itemId', id);
            if(!id){
                title = "添加题目";
            }else{
                title = "修改题目";
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
                        var $form = $(that.dialog).find('form').eq(0);
                        var isValid = $form.form('validate');
                        if (!isValid) return;
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post(href, $form.serialize(), function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                //reload the datagrid list
                                subjectSaveModule.savedFlag = true;
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reload();
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
        deleteItem:function(id){
            var that = this;
            var href = '<?=$urlHrefs['delItem']?>';
            href = GLOBAL.func.addUrlParam(href, 'itemId', id);
            $.messager.confirm('提示', '确认删除吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        //reload the datagrid list
                        subjectSaveModule.savedFlag = true;
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    }
                }, 'json');
            });
        },
        setItemSort:function(id, sort){
            var that = this;
            var url = '<?=$urlHrefs['setItemSort']?>';
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
                    $.post(url,{ id: id, sort:value },function (res) {
                        if(!res.code){
                            $.app.method.alertError(null, res.msg);
                        }else{
                            $.app.method.tip('提示', res.msg, 'info');
                            that.reload();
                        }
                    },'json');
                }
            });
            //设置默认值
            prompt.find('.messager-input').val(sort);
        }
    };
</script>