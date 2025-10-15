<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'north',collapsible:false,border:false" style="height:60%;">
        <table id="progressLogsDatagrid_<?=$uniqid?>" class="easyui-datagrid" data-options="striped:true,
            nowrap:false,
            rownumbers:false,
            singleSelect:true,
            url:'<?=$urlHrefs['index']?>',
            toolbar:'#<?=TOOLBAR_ID?>',
            pagination:true,
            pageSize:<?=DEFAULT_PAGE_ROWS?>,
            fit:true,
            fitColumns:<?=$loginMobile?'false':'true'?>,
            onDblClickRow:function(idx,row){progressLogsModule_<?=$uniqid?>.view(row.progress_log_id)},
            onLoadSuccess:progressLogsModule_<?=$uniqid?>.onLoaded,
            border:false">
            <thead>
            <tr>
                <?php if(!$readOnly){ ?>
                <th data-options="field:'operate',width:50,align:'center',formatter:progressLogsModule_<?=$uniqid?>.operate">操作</th>
                <?php } ?>
                <th data-options="field:'occur_date',width:100">发生日期</th>
                <th data-options="field:'title',width:300">标题</th>
                <th data-options="field:'files',width:300">附件</th>
                <th data-options="field:'realname',width:150">提交人</th>
            </tr>
            </thead>
        </table>
        <div id="<?=TOOLBAR_ID?>">
            <form id="progressLogsToolbarForm_<?=$uniqid?>">
                时间: <input class="easyui-datebox" name="search[entered]" type="text" data-options="width:120" />
                <a class="easyui-linkbutton" data-options="iconCls:'fa fa-search',
                    onClick:function(){ progressLogsModule_<?=$uniqid?>.search(); }">搜索
                </a>
                <a class="easyui-linkbutton" data-options="iconCls:'fa fa-reply',
                    onClick:function(){ progressLogsModule_<?=$uniqid?>.reset(); }">重置
                </a>
            </form>
        </div>
    </div>
    <div data-options="region:'center',border:true">
        <?php if(!$readOnly){ ?>
        <form id="progressLogsAddForm_<?=$uniqid?>" method="post">
            <table class="table-form" cellpadding="5">
                <tr>
                    <td class="field-label">
                        发生日期：
                    </td>
                    <td class="field-input">
                        <input class="easyui-datebox" name="infos[occur_date]" data-options="editable:false" value="<?=dateFilter($bindValues['curDate'])?>" />
                        <?php if (empty($src)): ?>
                        <span class="ml-10">
                            <input class="easyui-checkbox" name="infos[show_timeline]" value="1">
                            显示到时间轴
                        </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="field-label">标题</td>
                    <td class="field-input">
                        <input class="easyui-textbox" name="infos[title]" data-options="
                            width:'95%',
                            required:true,
                            prompt:'不超过100个字',
                            validType:['length[1,100]']" />
                    </td>
                </tr>
                <tr>
                    <td class="field-label">内容</td>
                    <td class="field-input">
                        <textarea id="entry_<?=$uniqid?>" class="easyui-textbox" name="infos[entry]" data-options="label:'',
                            width:'95%',
                            multiline:true,
                            disabled:false,
                            validType:['length[1,60000]']"></textarea>
                    </td>
                </tr>
                <tr>
                    <td width="120" class="field-label">附件:</td>
                    <td>
                        <input type="hidden" id="progressLogsAttacheIds_<?=$uniqid?>" name="infos[attaches]" value=""/>
                        <div id="progressLogsAttachsPanel_<?=$uniqid?>" style="width:100%" class="easyui-panel" data-options="border:false,
                            minimizable:false,
                            maximizable:false,
                            href:'<?=$urlHrefs['attachments']?>'">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <a href="javascript:;" class="easyui-linkbutton" data-options="onClick:function(){ progressLogsModule_<?=$uniqid?>.add(); },iconCls:iconClsDefs.save">保存</a>
                    </td>
                </tr>
            </table>
        </form>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
var progressLogsModule_<?=$uniqid?> = {
    progressLogsAttacheIds:[],
    dialog:'#globel-dialog-div',
    datagrid:'#progressLogsDatagrid_<?=$uniqid?>',
    onLoaded:function(data){
        var that = progressLogsModule_<?=$uniqid?>;
        data.rows.forEach(function(v,i){
            $.get('<?=url('index/Upload/viewAttaches')?>',{
                attachmentType:'<?=\app\index\logic\Upload::ATTACH_PROGRESS_LOGS?>',
                externalId:v.progress_log_id,
                uiStyle:'<?=\app\index\controller\Upload::ATTACHES_UI_LINK_STYLE?>'
            },function(res){
                $(that.datagrid).datagrid('updateRow',{
                    index: i,
                    row: {
                        title:'<button type="button" class="btn btn-outline-link" onclick="progressLogsModule_<?=$uniqid?>.view('+v.progress_log_id+')">'+v.title+'</button>',
                        files:res
                    }
                });
            });
        })
    },
    operate:function(val, row){
        var btns = [];
        btns.push('<a href="javascript:;" class="btn btn-outline-secondary size-MINI radius" onclick="progressLogsModule_<?=$uniqid?>.edit(' + row.progress_log_id + ')" title="编辑"><i class="fa fa-pencil-square-o fa-lg"></i></a>');
        btns.push('<a href="javascript:;" class="btn btn-outline-danger size-MINI radius" onclick="progressLogsModule_<?=$uniqid?>.del(' + row.progress_log_id + ')" title="删除"><i class="fa fa-trash-o fa-lg"></i></a>');
        return btns.join(' ');
    },
    reload:function(){
        $(this.datagrid).datagrid('reload');
    },
    load:function(){
        $(this.datagrid).datagrid('load');
    },
    search:function(){
        var that = this;
        var queryParams = $(that.datagrid).datagrid('options').queryParams;
        //reset the query parameter
        $.each($("#progressLogsToolbarForm_<?=$uniqid?>").serializeArray(), function() {
            delete queryParams[this['name']];
        });
        $.each($("#progressLogsToolbarForm_<?=$uniqid?>").serializeArray(), function() {
            queryParams[this['name']] = this['value'];
        });
        that.load();
    },
    reset:function(){
        var that = this;
        $("#progressLogsToolbarForm_<?=$uniqid?>").form('reset');
        var queryParams = $(that.datagrid).datagrid('options').queryParams;
        for(var key in queryParams){
            delete queryParams[key];
        }
        that.load();
    },
    add:function(){
        var that = this;
        var href = '<?=$urlHrefs['add']?>';
        $('#progressLogsAddForm_<?=$uniqid?>').form('submit', {
            url: href,
            iframe: false,
            onSubmit: function(){
                var isValid = $(this).form('validate');
                if (!isValid) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                return true;
            },
            success: function(data){
                var res = eval('(' + data + ')');  // change the JSON string to javascript object
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                    $("#progressLogsAddForm_<?=$uniqid?>").form('reset');
                    that.load();
                    progressLogsModule_<?=$uniqid?>.progressLogsAttacheIds.splice(0, progressLogsModule_<?=$uniqid?>.progressLogsAttacheIds.length);
                    $("#progressLogsAttachsPanel_<?=$uniqid?>").panel('refresh');
                }
            }
        });
    },
    edit:function(id){
        var that = this;
        var href = '<?=url('ProgressLogs/edit')?>?src=<?=$src?>&id='+id;
        QT.helper.dialog('修改',href,true,function($dialog){
            var $form = $dialog.find('form:eq(0)');
            if(!$form.form('validate')){
                return false;
            }
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, $form.serialize(), function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg);
                    $dialog.dialog('close');
                    that.reload();
                }
            }, 'json');
        },1000,"95%",'progress_log_edit_dialog');
    },
    del:function(progressLogId){
        var that = this;
        var href = '<?=$urlHrefs['delete']?>';
        href += href.indexOf('?') != -1 ? '&progressLogId=' + progressLogId : '?progressLogId='+progressLogId;
        $.messager.confirm('提示', '确认删除吗?', function(result){
            if(!result) return false;
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, {}, function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                    that.reload();
                }
            }, 'json');
        });
    },
    view:function(id){
        QT.helper.view({
            url:'<?=url('ProgressLogs/view')?>?id='+id,
            width:1000,
            height:'95%',
            dialog:'progress_log_view_dialog'
        });
    },
    onAttachmentsUploaded:function(files){
        $.each(files, function(i,v){
            progressLogsModule_<?=$uniqid?>.progressLogsAttacheIds.push(v.attachment_id);
        });
        $('#progressLogsAttacheIds_<?=$uniqid?>').val(progressLogsModule_<?=$uniqid?>.progressLogsAttacheIds.join(','));
    }
};
$.parser.onComplete = function(context){
    $('#entry_<?=$uniqid?>').textbox('autoHeight');
    $.parser.onComplete = $.noop;
}
</script>