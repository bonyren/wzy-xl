<div class="easyui-layout" fit="true" border="false">
    <div data-options="region:'west',title:'角色信息',collapsible:false" style="width:30%">
        <h3 class="m-2"><?=$bindValues['infos']['role_name']?></h3>
    </div>
    <div data-options="region:'center',title:'节点访问权限'">
        <ul id="authUserNodeTree" class="easyui-tree" data-options="url:'<?=$urlHrefs['roleNodes']?>',
            onlyLeafCheck:true,
            lines:true,
            checkbox:true,
            onCheck:adminRoleAuthorizeModule.onCheck"></ul>
    </div>
</div>
<script type="text/javascript">
    var adminRoleAuthorizeModule = {
        onCheck:function(node, checked){
            if(!checked){
                return;
            }
            var parentNode = $('#authUserNodeTree').tree('getParent', node.target);
            var nodes = $('#authUserNodeTree').tree('getChildren', parentNode.target);
            for(var i=0; i<nodes.length; i++){
                if(node.id != nodes[i].id){
                    $('#authUserNodeTree').tree('uncheck', nodes[i].target);
                }
            }
        }
    };
</script>