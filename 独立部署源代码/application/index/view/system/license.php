<?php if($id){ ?>
    <table class="table table-bordered table-sm" cellpadding="5">
        <tr>
            <th colspan="4" class="text-center table-easyui" height="40">授权信息</th>
        </tr>
        <tr>
            <td class="table-primary" style="width: 100px;">邮箱</td>
            <td><?=$email?></td>
            <td class="table-primary" style="width: 100px;">授权码</td>
            <td><?=$auth_key?></td>
        </tr>
        <tr>
            <td class="table-primary" style="width: 100px;">服务过期</td>
            <td><?=$vip_due_date?>&nbsp;<?=date('Y-m-d')>$vip_due_date?'<span class="badge badge-danger">已过期<span>':'<span class="badge badge-success">有效<span>'?></td>
            <td class="table-primary" style="width: 100px;">绑定时间</td>
            <td><?=$bind_time?></td>
        </tr>
        <tr>
            <td colspan="4" class="text-center p-3">
                <a class="easyui-linkbutton" href="javascript:;" data-options="iconCls:'fa fa-download',onClick:licenseModule.downloadReportAlgo">
                    刷新测评报告算法库
                </a>
                <a class="easyui-linkbutton" href="javascript:;" data-options="iconCls:'fa fa-chain-broken',onClick:licenseModule.unbind">
                    解绑授权
                </a>&nbsp;
                <a class="easyui-linkbutton" href="javascript:;" data-options="iconCls:'fa fa-refresh',onClick:licenseModule.refresh">
                    刷新授权
                </a>&nbsp;
                <a class="easyui-linkbutton" href="javascript:;" data-options="iconCls:'fa fa-money',onClick:licenseModule.buy">
                    续费授权
                </a>
            </td>
        </tr>
    </table>
<?php }else{ ?>
    <table class="table table-bordered table-sm" cellpadding="5">
        <tr>
            <td colspan="6" class="text-center p-3">
                <a class="easyui-linkbutton search-submit" href="javascript:;" data-options="iconCls:'fa fa-chain',onClick:licenseModule.bind">
                    绑定授权
                </a>&nbsp;
                <a class="easyui-linkbutton" href="javascript:;" data-options="iconCls:'fa fa-money',onClick:licenseModule.buy">
                    购买授权
                </a>
            </td>
        </tr>
    </table>
<?php } ?>
<script>
    var licenseModule = {
        unbind:function(){
            var target = this;
            var that = licenseModule;
            var href = '<?=url('index/System/license')?>';
            $.messager.confirm('提示', '确认解绑吗?', function(result){
                if(!result) return false;
                $.messager.progress({text:'处理中，请稍候...'});
                $.post(href, {action:'unbind'},function (res) {
                    $.messager.progress('close');
                    if(!res.code){
                        $.app.method.tip('提示信息', res.msg, 'error');
                    }else{
                        $.app.method.tip('提示信息', res.msg, 'info');
                        that.reload(target);
                    }
                },'json');
            });
        },
        bind:function(){
            var target = this;
            var that = licenseModule;
            var href = '<?=url('index/System/license')?>';
            var prompt = $.messager.prompt({
                title: '提示',
                msg: '请输入授权码:',
                fn: function(key){
                    if(key === undefined){
                        //cancel click
                        return;
                    }
                    if($.trim(key) == ''){
                        $.app.method.tip('提示信息', '授权码不能为空', 'error');
                        return;
                    }
                    $.messager.progress({text:'处理中，请稍候...'});
                    $.post(href, {auth_key:$.trim(key),action:'bind'},function (res) {
                        $.messager.progress('close');
                        if(!res.code){
                            $.app.method.tip('提示信息', res.msg, 'error');
                        }else{
                            $.app.method.tip('提示信息', res.msg, 'info');
                            that.reload(target);
                        }
                    },'json');
                }
            });
            //设置默认值
            prompt.find('.messager-input').val('');
        },
        refresh:function(){
            var target = this;
            var that = licenseModule;
            var href = '<?=url('index/System/license')?>';
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, {action:'refresh'},function (res) {
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.tip('提示信息', res.msg, 'error');
                }else{
                    $.app.method.tip('提示信息', res.msg, 'info');
                    that.reload(target);
                }
            },'json');
        },
        downloadReportAlgo:function(){
            var target = this;
            var that = licenseModule;
            var href = '<?=url('index/System/downloadReportAlgo')?>';
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, function (res) {
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.tip('提示信息', res.msg, 'error');
                }else{
                    $.app.method.tip('提示信息', res.msg, 'info');
                    that.reload(target);
                }
            },'json');
        },
        buy:function(){
            window.open("<?=$buy_url?>", "_blank");
        },
        reload:function(target){
            $(target).closest('.panel-body').panel('refresh');
        }
    };
</script>