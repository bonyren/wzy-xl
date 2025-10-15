
<table id="system-setting-propertygrid" class="easyui-propertygrid"
       data-options="border:false,
       fit:true,
       fitColumns:true,
       nowrap:false,
       showHeader:true,
       columns:[
            [
                {field:'name',title:'配置项',width:'20%',styler:systemSettingModule.titleStyler},
                {field:'value',title:'配置值',width:'70%'}
            ]
       ],
       showGroup:true,
       scrollbarSize:0,
       url:'<?=$urlHrefs['setting']?>',
       onClickRow:systemSettingModule.onClickRow,
       toolbar:systemSettingModule.toolbar">
</table>

<script type="text/javascript">
    var systemSettingModule = {
        dialog:       '#globel-dialog-div',
        propertygrid: '#system-setting-propertygrid',
        data: {},
        //toolbar
        toolbar: [
            {text: '保存', iconCls: 'fa fa-save', handler: function(){systemSettingModule.save();}},
            {text: '刷新', iconCls: 'fa fa-refresh', handler: function(){systemSettingModule.refreshManually();}},
            {text: '导出', iconCls: 'fa fa-mail-forward', handler: function(){systemSettingModule.export();} },
            {text: '导入', iconCls: 'fa fa-mail-reply', handler: function(){systemSettingModule.import();}},
            {text: '重置', iconCls: 'fa fa-recycle', handler: function(){systemSettingModule.default();}},
            /*
            {text: '测试邮箱设置(先保存)', iconCls: 'fa fa-flask', handler:function(){systemSettingModule.testMailbox();}}
            */
        ],
        titleStyler: function(value, row, index){
            return 'font-weight:200;font-size:20px;color:#586069 !important;';
        },
        //记录当前选项的位置，失去焦点的时候可以用来定位
        onClickRow: function(index){
            systemSettingModule.data = {index: index, field: 'value'};
        },
        refresh: function(){
            $(this.propertygrid).propertygrid('reload');
        },
        refreshManually:function(){
            var that = this;
            $.messager.progress({text:'处理中，请稍候...'});
            that.refresh();
            setTimeout(function(){
                $.messager.progress('close');
            }, 1000);
        },
        save: function(){
            var that = this;
            var data = [];
            //$(that.propertygrid).propertygrid('acceptChanges');
            var rows = $(that.propertygrid).propertygrid('getChanges');
            for(var i=0; i<rows.length; i++){
                data.push({'key': rows[i]['key'], 'value': rows[i]['value']});
            }
            //console.log(data);
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=$urlHrefs['settingSave']?>', {data: data}, function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                    that.refresh();
                }
            }, 'json');
        },
        image: function(){
            var that = this;
            var option = this.data;
            $.app.method.uploadImage("<?=$urlHrefs['fileUpload']?>",
                function(res){
                    if(res.code){
                        var url = res.data.absolute_url;
                        //直接赋值
                        $(that.propertygrid).propertygrid('selectRow', option.index).propertygrid('beginEdit', option.index);
                        var ed = $(that.propertygrid).propertygrid('getEditor', {index:option.index,field:option.field});
                        $(ed.target).prop('src', url);
                    }else{
                        $.app.method.tip('提示', (res.msg || 'failed to upload'), 'error');
                    }
                },
                function(filename){  //上传验证函数
                    if(!filename.match(/\.jpg$|\.png$|\.bmp$/i)){
                        $.app.method.tip('提示', 'Upload file suffix not allowed', 'error');
                        return false;
                    }
                    return true;
                }
            );
        },
        export: function(){
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=$urlHrefs['settingExport']?>', function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                    window.location.href = res.data;
                }
            }, 'json');
        },
        import: function(){
            var that = this;
            $.messager.confirm('提示', '该操作将清空所有数据，确定要继续吗？', function(result){
                if(!result) return false;
                $.app.method.uploadOne('<?=$urlHrefs['importUpload']?>',
                    function(json){
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post('<?=$urlHrefs['settingImport']?>', {filename: json.data.save_name}, function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                that.refresh();
                            }
                        }, 'json');
                    },
                    function(filename){  //上传验证函数
                        /*
                        if(!filename.match(/\.data$/)){
                            $.app.method.tip('Information', '上传文件后缀不允许', 'error');
                            return false;
                        }*/
                        return true;
                    }
                );
            });
        },
        default: function(){
            var that = this;
            $.messager.confirm('提示', '确定要恢复出厂设置吗', function(result){
                if(!result) return true;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=$urlHrefs['settingDefault']?>', function(res){
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        that.refresh();
                    }
                }, 'json');
            })
        },
        testMailbox:function (){
            var that = this;
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=$urlHrefs['settingTestMailbox']?>', function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.alert(null, '邮箱设置正常');
                }
            }, 'json');
        }
    };
</script>