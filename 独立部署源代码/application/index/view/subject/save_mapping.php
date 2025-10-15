<table id="subjectStandardMappingDatagrid" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    url:'<?=$urlHrefs['mapping']?>',
    method:'post',
    toolbar:'#subjectStandardMappingToolbar',
    pagination:false,
    border:false,
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    title:'',
    onClickCell: subjectStandardMappingModule.onClickCell,
    onEndEdit: subjectStandardMappingModule.onEndEdit,">
    <thead>
    <tr>
        <th colspan="2">原始分</th>
        <th colspan="2">标准分</th>
    </tr>
    <tr>
        <th data-options="field:'weight_min',width:100,align:'left',editor:{type:'numberbox',options:{min:0,precision:2}}">最小值</th>
        <th data-options="field:'weight_max',width:100,align:'left',editor:{type:'numberbox',options:{min:0,precision:2}}">最大值</th>
        <th data-options="field:'standard_weight_min',width:100,align:'left',editor:{type:'numberbox',options:{min:0,precision:2}}">最小值</th>
        <th data-options="field:'standard_weight_max',width:100,align:'left',editor:{type:'numberbox',options:{min:0,precision:2}}">最大值</th>
    </tr>
    </thead>
</table>
<div id="subjectStandardMappingToolbar" class="p-1">
    <div>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="subjectStandardMappingModule.new()" data-options="iconCls:iconClsDefs.add">增加</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="subjectStandardMappingModule.reload()" data-options="iconCls:iconClsDefs.refresh">刷新</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="subjectStandardMappingModule.accept()" data-options="iconCls:iconClsDefs.ok">确定</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="subjectStandardMappingModule.reject()" data-options="iconCls:iconClsDefs.cancel">取消</a>
    </div>
</div>
<script>
    var subjectStandardMappingModule = {
        datagrid: '#subjectStandardMappingDatagrid',
        reload:function () {
            var that = subjectStandardMappingModule;
            $(that.datagrid).datagrid('load', {});
        },
        editIndex:undefined,
        endEditing:function () {
            var that = subjectStandardMappingModule;
            if (that.editIndex == undefined){
                return true
            }
            if ($(that.datagrid).datagrid('validateRow', that.editIndex)){
                $(that.datagrid).datagrid('endEdit', that.editIndex);
                that.editIndex = undefined;
                return true;
            } else {
                return false;
            }
        },
        onClickCell:function (index, field) {
            var that = subjectStandardMappingModule;
            if (that.editIndex != index){
                if (that.endEditing()){
                    $(that.datagrid).datagrid('selectRow', index).datagrid('beginEdit', index);
                    var ed = $(that.datagrid).datagrid('getEditor', {index:index,field:field});
                    if (ed){
                        ($(ed.target).data('textbox') ? $(ed.target).textbox('textbox') : $(ed.target)).focus();
                    }
                    that.editIndex = index;
                } else {
                    setTimeout(function(){
                        $(that.datagrid).datagrid('selectRow', that.editIndex);
                    },0);
                }
            }
        },
        onEndEdit:function (index, data, changes) {
            var that = subjectStandardMappingModule;
            var oldrow = $(that.datagrid).datagrid('getEditors', index);
            if($.isEmptyObject(changes)){
                return false;
            }
            changes.id = data.id;
            var href = '<?=$urlHrefs['mapping']?>';
            $.post(href, changes, function(res){
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                    $(that.datagrid).datagrid('updateRow',{
                        index:index,
                        row:{
                            weight_min:oldrow[0].oldHtml,
                            weight_max:oldrow[1].oldHtml,
                            standard_weight_min:oldrow[2].oldHtml,
                            standard_weight_max:oldrow[3].oldHtml,
                        }
                    });
                }else{
                    that.reload();
                    that.editIndex = undefined;
                }
                that.accept();
            },'json');
        },
        new:function(){
            var that = subjectStandardMappingModule;
            that.accept();
            var href = '<?=$urlHrefs['mapping']?>';
            $.post(href, {id:0}, function(res){
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    that.reload();
                    that.editIndex = undefined;
                }
            },'json');
        },
        accept:function () {
            var that = subjectStandardMappingModule;
            if (that.endEditing()){
                $(that.datagrid).datagrid('acceptChanges');
            }
        },
        reject:function () {
            var that = subjectStandardMappingModule;
            $(that.datagrid).datagrid('rejectChanges');
            that.editIndex = undefined;
        }
    }
</script>