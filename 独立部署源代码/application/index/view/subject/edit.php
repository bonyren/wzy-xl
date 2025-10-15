<div class="easyui-tabs" id="subjectEditTabs" data-options="fit:true,border:false,
    onSelect:function(title, index){
    }">
    <!----------------------------------------------------------------------------------------------------->
    <div title="基本信息" data-options="cache:true,
            iconCls:'fa fa-circle',
            href:'<?=$urlHrefs['save']?>',
            onClose:function(){}">
    </div>
    <!----------------------------------------------------------------------------------------------------->
        <div title="测前调查" data-options="cache:true,
            iconCls:'fa fa-circle',
            href:'<?=$urlHrefs['questionForm']?>',
            onClose:function(){}">
    </div>
    <?php if(!$edit_rule_disabled){ ?>
        <!----------------------------------------------------------------------------------------------------->
        <div title="维度因子" data-options="cache:false,
                iconCls:'fa fa-circle',
                href:'<?=$urlHrefs['saveStandard']?>'">
        </div>
        <!----------------------------------------------------------------------------------------------------->
        <div id="subject-item-panel" title="题目定义" data-options="cache:false,
                iconCls:'fa fa-circle',
                href:'<?=$urlHrefs['saveItems']?>'">
        </div>
        <!----------------------------------------------------------------------------------------------------->
        <div id="subject-result-panel" title="结果解读" data-options="cache:false,
                iconCls:'fa fa-circle',
                href:'<?=$urlHrefs['saveResult']?>'">
        </div>
    <?php } ?>
    <!----------------------------------------------------------------------------------------------------->
    <div title="报告设置" data-options="cache:true,
            iconCls:'fa fa-circle',
            href:'<?=$urlHrefs['saveReport']?>',
            onClose:function(){}">
    </div>
</div>
<script type="text/javascript">
    var subjectEditsModule = {
        dialog:'#globel-dialog-div',
        close:function(){
            $(subjectEditsModule.dialog).dialog('close');
        }
    };
</script>