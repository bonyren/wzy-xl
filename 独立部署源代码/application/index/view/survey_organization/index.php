<?php
use app\index\logic\Defs as IndexDefs;
?>
<div id="organizationLayout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',minWidth:200" style="width:20%;">
        <div class="easyui-layout" data-options="fit:true">
        <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
            <div class=" bg-light border-bottom p-1" data-options="region:'north',height:40,border:false">
                <a href="#" class="easyui-linkbutton" iconCls="fa fa-plus-square-o" title="增加" data-options="plain:false,
                    onClick:function(){organizationModule.add();}">
                </a>
                <a href="#" class="easyui-linkbutton" iconCls="fa fa-pencil-square-o" title="编辑" data-options="plain:false,
                    onClick:function(){organizationModule.edit();}">
                </a>
                <a href="#" class="easyui-linkbutton" iconCls="fa fa-trash-o" title="删除" data-options="plain:false,
                    onClick:function(){organizationModule.delete();}">
                </a>
                <a href="#" class="easyui-linkbutton" iconCls="fa fa-arrow-up" title="上移" data-options="plain:false,
                    onClick:function(){organizationModule.up();}">
                </a>
                <a href="#" class="easyui-linkbutton" iconCls="fa fa-arrow-down" title="下移" data-options="plain:false,
                    onClick:function(){organizationModule.down();}">
                </a>
            </div>
        <?php } ?>
            <div data-options="region:'center',border:false">
                <ul id="organizationTree" class="easyui-tree" style="height:100%;" data-options="url:'<?=$urlHrefs['index']?>',
                    animate:true,
                    lines:true,
                    border:false,
                    formatter:organizationModule.formatText,
                    onClick:function(node){
                    },
                    onSelect:function(node){
                        organizationModule.onSelected(node);
                    },
                    onLoadSuccess:function(node,data){
                        organizationModule.init();
                    },
                    dnd:true,
                    onBeforeDrag:function(node){
                        //console.log('onBeforeDrag', node);
                        return node.id==0?false:true;
                    },
                    onStartDrag:function(node){
                        //console.log('onStartDrag');
                    },
                    onStopDrag:function(node){
                        //console.log('onStopDrag');
                    },
                    onDragEnter:function(target, source){
                        var node = $(this).tree('getNode', target);
                        //console.log('onDragEnter', node);
                        return source.parent_id==node.id?false:true;
                    },
                    onDragOver:function(target, source){
                        var node = $(this).tree('getNode', target);
                        //console.log('onDragOver', node);
                        return source.parent_id==node.id?false:true;
                    },
                    onBeforeDrop:function(target, source, point){
                        var node = $(this).tree('getNode', target);
                        //console.log('onBeforeDrop', node);
                        if (!node) {
                            return false;
                        } else {
                            return window.confirm('确定移动到【'+node.text+'】组织下吗？');
                        }
                    },
                    onDrop:function(target, source, point){
                        var node = $(this).tree('getNode', target);
                        //console.log('onDrop', node);
                        organizationModule.changeLevel(source.id, node.id);
                        return false;
                    }">
                </ul>
            </div>
        </div>
    </div>
    <div data-options="region:'center',title:'普查组织'">
    </div>
</div>
<script>
    var organizationModule = {
        tree:'#organizationTree',
        currentOrganizationId: 0,
        formatText:function(node){
            return node.text;
        },
        init:function(){
            var that = organizationModule;
            if(that.currentOrganizationId == 0) {
                //default
                var nodes = $(that.tree).tree('getRoots');
                var childNodes = $(that.tree).tree('getChildren', nodes[0].target);
                $(that.tree).tree('select', nodes[0].target);
            }else{
                var nodes = $(that.tree).tree('getRoots');
                var cloneNodes = [].concat(nodes);
                var node = null;
                while(node = cloneNodes.shift()){
                    if(node.id == that.currentOrganizationId){
                        $(that.tree).tree('select', node.target);
                        //find the last selected node
                        break;
                    }
                    var childNodes = $(that.tree).tree('getChildren', node.target);
                    childNodes.forEach(function(childNode){
                        cloneNodes.push(childNode);
                    });
                }
            }
        },
        add:function(){
            var that = organizationModule;
            var parentNode = $(that.tree).tree('getSelected');
            if(!parentNode){
                $.app.method.tip('提示信息', "请选择上层组织", 'error');
                return;
            }
            var parentId = parentNode.id;
            var parentText = parentNode.text;
            var href = '<?=$urlHrefs['organizationAdd']?>';
            href = GLOBAL.func.addUrlParam(href, 'parentId', parentId);
            var prompt = $.messager.prompt({
                title: '提示',
                msg: '在[' + parentText + ']下增加新组织，请输入名称:',
                fn: function(name){
                    if(name === undefined){
                        //cancel click
                        return;
                    }
                    if($.trim(name) == ''){
                        $.app.method.tip('提示信息', '名称不能为空', 'error');
                        return;
                    }
                    $.messager.progress({text:'处理中，请稍候...'});
                    $.post(href, {name:$.trim(name) },function (res) {
                        $.messager.progress('close');
                        if(!res.code){
                            $.app.method.tip('提示信息', res.msg, 'error');
                        }else{
                            $.app.method.tip('提示信息', res.msg, 'info');
                            that.reload();
                        }
                    },'json');
                }
            });
            //设置默认值
            prompt.find('.messager-input').val('');
        },
        edit:function(){
            var that = organizationModule;
            var selectedNode = $(that.tree).tree('getSelected');
            if(!selectedNode){
                $.app.method.tip('提示信息', "请选择要修改的组织", 'error');
                return;
            }
            var id = selectedNode.id;
            if(id == 0){
                $.app.method.tip('提示信息', "不允许修改根组织", 'error');
                return;
            }
            var href = '<?=$urlHrefs['organizationUpdate']?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);

            var prompt = $.messager.prompt({
                title: '提示',
                msg: '请输入组织名称:',
                fn: function(name){
                    if(name === undefined){
                        //cancel click
                        return;
                    }
                    if($.trim(name) == ''){
                        $.app.method.tip('提示信息', '名称不能为空', 'error');
                        return;
                    }
                    $.messager.progress({text:'处理中，请稍候...'});
                    $.post(href, {name:$.trim(name) },function (res) {
                        $.messager.progress('close');
                        if(!res.code){
                            $.app.method.tip('提示信息', res.msg, 'error');
                        }else{
                            $.app.method.tip('提示信息', res.msg, 'info');
                            that.reload();
                        }
                    },'json');
                }
            });
            //设置默认值
            var nameText = selectedNode.text.replace(/\[\d*\]/g, '');
            prompt.find('.messager-input').val(nameText);
        },
        delete:function(){
            var that = organizationModule;
            var selectedNode = $(that.tree).tree('getSelected');
            if(!selectedNode){
                $.app.method.tip('提示信息', "请选择要删除的组织", 'error');
                return;
            }
            var id = selectedNode.id;
            var text = selectedNode.text;
            if(0 == id){
                $.app.method.tip('提示信息', "不允许删除根组织", 'error');
                return;
            }
            var childNodes = $(that.tree).tree('getChildren', selectedNode.target);
            if(childNodes.length != 0){
                $.app.method.tip('提示信息', "请先删除该组织下的子组织", 'error');
                return;
            }
            var href = '<?=$urlHrefs['organizationDelete']?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $.messager.confirm('提示信息', '确定要删除[' + text + ']吗？', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {}, function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.tip('提示信息', res.msg, 'error');
                    }else{
                        $.app.method.tip('提示信息', res.msg, 'info');
                        that.currentOrganizationId = 0;
                        that.reload();
                    }
                }, 'json');
            });
        },
        up:function(){
            var that = organizationModule;
            var selectedNode = $(that.tree).tree('getSelected');
            if(!selectedNode){
                $.app.method.tip('提示信息', "请选择要上移的组织", 'error');
                return;
            }
            var id = selectedNode.id;
            if(0 == id){
                $.app.method.tip('提示信息', "不允许上移根组织", 'error');
                return;
            }
            var href = '<?=$urlHrefs['organizationUp']?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, {}, function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.tip('提示信息', res.msg, 'error');
                }else{
                    that.reload();
                }
            }, 'json');
        },
        down:function(){
            var that = organizationModule;
            var selectedNode = $(that.tree).tree('getSelected');
            if(!selectedNode){
                $.app.method.tip('提示信息', "请选择要下移的组织", 'error');
                return;
            }
            var id = selectedNode.id;
            if(0 == id){
                $.app.method.tip('提示信息', "不允许下移根组织", 'error');
                return;
            }
            var href = '<?=$urlHrefs['organizationDown']?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $.post(href, {}, function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.tip('提示信息', res.msg, 'error');
                }else{
                    that.reload();
                }
            }, 'json');
        },
        reload:function(){
            var that = organizationModule;
            $(that.tree).tree('reload');
        },
        changeLevel:function(id, parentId){
            var that = organizationModule;
            var href = '<?=url('index/SurveyOrganization/changeLevel')?>';
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, {id:id, parentId:parentId}, function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.tip('提示信息', res.msg, 'error');
                }else{
                    that.reload();
                }
            }, 'json');
        },
        onSelected:function(node){
            var that = organizationModule;
            organizationModule.currentOrganizationId = node.id;
            var href = '<?=url('index/SubjectOrder/orders', ['survey_id'=>$surveyId])?>';
            href = GLOBAL.func.addUrlParam(href, 'survey_organization_id', node.id);
            $('#organizationLayout').layout('panel','center').panel('refresh', href);
            $('#organizationLayout').layout('panel','center').panel('setTitle', node.breadcrumb)
        }
    };
</script>