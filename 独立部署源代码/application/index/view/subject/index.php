<?php
use app\index\logic\Defs as IndexDefs;
?>
<table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
    nowrap:false,
    rownumbers:false,
    autoRowHeight:true,
    singleSelect:true,
    <?php if(isset($_GET['multiple']) && $_GET['multiple']): ?>
    selectOnCheck:false,
    checkOnSelect:false,
    <?php else: ?>
    selectOnCheck:true,
    checkOnSelect:true,
    <?php endif; ?>
    url:'<?=$urlHrefs['datagrid']?>',
    method:'post',
    toolbar:'#<?=TOOLBAR_ID?>',
    pagination:true,
    pageSize:<?=DEFAULT_PAGE_ROWS?>,
    pageList:[10,20,30,50,80,100],
    fit:true,
    fitColumns:<?=$loginMobile?'false':'true'?>,
    queryParams:{
        init_load:1
    },
    onDblClickRow:function(index, row){
        /*
        <?=JVAR?>.view(row.id, row.name);
        */
    },
    onLoadSuccess:<?=JVAR?>.onLoadSuccess,
    onBeforeLoad:<?=JVAR?>.onBeforeLoad,
    border:false">
    <thead>
        <tr>
        <?php if(empty($_GET['dialog_call'])){ ?>
            <th rowspan="2" data-options="field:'btns',width:60,fixed:true,align:'center',formatter:<?=JVAR?>.formatOpt.bind(<?=JVAR?>),hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
        <?php }else if(isset($_GET['multiple']) && $_GET['multiple']){ ?>
            <th rowspan="2" data-options="field:'cb',checkbox:true">选择</th>
        <?php } ?>
        <?php 
            $colspan = 11;
            if (empty($_GET['dialog_call'])){
                $colspan += 2;
            }
        ?>
            <th colspan="<?=$colspan?>">属性</th>
            <th colspan="2">统计</th>
        </tr>
        <tr>
            <th data-options="field:'image_url',width:80,align:'center',formatter:<?=JVAR?>.formatImage.bind(<?=JVAR?>)">图片</th>
            <th data-options="field:'name',width:200,align:'left'">名称</th>
            <th data-options="field:'subtitle',width:100,align:'left'">副标题</th>
            <th data-options="field:'category',width:80,align:'left'">分类</th>
            <th data-options="field:'item_num',width:80,align:'center'">题目数</th>
            <th data-options="field:'current_price',width:80,align:'center',sortable:true">价格(元)</th>
            <th data-options="field:'status',width:80,align:'center',sortable:true,formatter:datagridFormatter.formatEntityStatus">状态</th>
            <th data-options="field:'rating',width:120,align:'center',sortable:true,formatter:<?=JVAR?>.formatRating.bind(<?=JVAR?>)">评分</th>
            <?php if (empty($_GET['dialog_call'])){ ?>
                <th data-options="field:'sort',width:80,align:'center',sortable:true,formatter:<?=JVAR?>.formatSortOpt.bind(<?=JVAR?>)">排序</th>
                <th data-options="field:'label_show',width:100,align:'center',formatter:<?=JVAR?>.formatLabelShow.bind(<?=JVAR?>)">轮播|热门|精选</th>
            <?php } ?>
            <th data-options="field:'img_qrcode',width:80,align:'center',formatter:<?=JVAR?>.formatQrcode.bind(<?=JVAR?>)">推送二维码</th>
            <th data-options="field:'banner_img',width:100,align:'center',formatter:<?=JVAR?>.formatBannerImage.bind(<?=JVAR?>)">轮播图</th>
            <th data-options="field:'uni_app',width:60,align:'center',formatter:GLOBAL.func.formatBoolean">小程序</th>
            <!----------------------------------------------------------------------------------------->
            <th data-options="field:'participants',width:80,align:'center',sortable:true">总次数</th>
            <th data-options="field:'total_amount',width:80,align:'center',sortable:true">总金额</th>
        </tr>
    </thead>
</table>
<div id="<?=TOOLBAR_ID?>" class="p-1">
    <form id="<?=FORM_ID?>" class="datagrid-search-form-container">
        <div class="datagrid-search-form-box">
            <input name="search[name]" class="easyui-textbox" prompt="量表名称" data-options="validType:['length[0,60]']" style="width:150px;">
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[category_id]" class="easyui-combobox" prompt="量表分类" style="width:150px;" data-options="editable:false,value:''">
                <?php foreach ($categories as $v): ?>
                    <option value="<?=$v['id']?>"><?=$v['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[label]" class="easyui-combobox" prompt="标签" style="width:150px;" data-options="editable:false,panelHeight:'auto',value:''">
                <?php foreach (\app\Defs::SUBJECT_LABELS as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <select name="search[status]" class="easyui-combobox" prompt="状态" style="width:100px;" data-options="editable:false,panelHeight:'auto',value:''">
                <?php foreach (IndexDefs::$entityStatusDefs as $k=>$v): ?>
                    <option value="<?=$k?>"><?=$v?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton search-submit" iconCls="fa fa-search" onclick="<?=JVAR?>.search()">搜索</a>
        </div>
        <div class="datagrid-search-form-box">
            <a href="javascript:;" class="easyui-linkbutton search-reset" iconCls="fa fa-rotate-left" onclick="<?=JVAR?>.reset()">重置</a>
        </div>
    </form>
    <?php if(empty($_GET['dialog_call']) && $loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
        <div class="line my-1"></div>
        <a class="easyui-linkbutton" iconCls="fa fa-plus-circle" onclick="<?=JVAR?>.edit(0)">新增量表</a>
        <a class="easyui-linkbutton" iconCls="fa fa-reply-all" onclick="<?=JVAR?>.importSubjectFromPool()">导入量表</a>
    <?php } ?>
</div>
<script type="text/javascript">
    var <?=JVAR?> = {
        dialog:'#globel-dialog-div',
        datagrid:'#<?=DATAGRID_ID?>',
        toolbar:'#<?=TOOLBAR_ID?>',
        searchForm: '#<?=FORM_ID?>',
        formatOpt:function(val, row, index){
            var html = '<a href="javascript:void(0)" id="<?=UNIQID?>-subject-operate-row-' + index + '"' +
                        'data-options="menu:\'#<?=UNIQID?>-subject-operate-row-menu-'  + index + '\',iconCls:\'fa fa-ellipsis-v\'" ></a>' +
                    '<div id="<?=UNIQID?>-subject-operate-row-menu-' + index + '" style="width:60px;">';
            /***只读操作******************************************************************/
            html += '<div data-options="iconCls:\'fa fa-eye\'" onclick="<?=JVAR?>.view('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">查看</div>';
            html += '<div data-options="iconCls:\'fa fa-flask\'" onclick="<?=JVAR?>.test('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">模拟</div>';
            html += '<div data-options="iconCls:\'fa fa-navicon\'" onclick="<?=JVAR?>.orders('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">订单</div>';
            html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcodeInfo('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) + '\')">二维码</div>';
            var text = row.qrcode ? '下载推送二维码' : '生成推送二维码';
            html += '<div data-options="iconCls:\'fa fa-qrcode\'" onclick="<?=JVAR?>.qrcode('+row.id+',\'' + row.qrcode +'\')">推送二维码</div>';
            /***读写操作******************************************************************/
            <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
                html += '<div class="menu-sep"></div>';
                html += '<div data-options="iconCls:\'fa fa-pencil-square-o\'" onclick="<?=JVAR?>.edit('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\',' + index + ')">编辑</div>';
                html += '<div data-options="iconCls:\'fa fa-clone\'" onclick="<?=JVAR?>.clone('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">克隆</div>';
                if(row.delete_flag == 1){
                    html += '<div data-options="iconCls:\'fa fa-mail-reply-all\'" onclick="<?=JVAR?>.restore('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">恢复</div>';
                }else{
                    html += '<div data-options="iconCls:\'fa fa-trash-o\'" onclick="<?=JVAR?>.del('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">删除</div>';
                }
                html += '<div data-options="iconCls:\'fa fa-trash\'" onclick="<?=JVAR?>.delForce('+row.id+',\'' + GLOBAL.func.escapeALinkStringParam(row.name) +'\')">硬删除</div>';
            <?php } ?>
            html += '<div data-options="iconCls:\'fa fa-navicon\'" onclick="<?=JVAR?>.reportDemoImages('+row.id+')">示例报告</div>';
            html += '</div>';
            return html;
        },
        formatRating:function(val, row){
            return '<input value="' + row.rating + '" type="text" class="<?=UNIQID?>-to-be-rating" />';
        },
        formatImage:function(val, row){
            return '<img class="img-thumbnail my-1" src="' + row.image_url + '" style="width:100px;">';
        },
        formatBannerImage:function(val, row){
            return '<img class="img-thumbnail my-1" src="' + row.banner_img + '" style="width:160px;">';
        },
        formatSortOpt:function(val, row){
            return '<span class="<?=UNIQID?>-to-be-parse"><input class="easyui-numberbox" value="'+row.sort+'" data-options="onChange:<?=JVAR?>.setSort,id:'+row.id+',min:0,max:9999,width:45"></span>';
        },
        formatLabelShow:function(val, row){
            var isBanner = false;
            var isPopular = false;
            var isFeature = false;
            //轮播
            if (row.label && row.label.search('banner') != -1) {
                isBanner = true;
            }
            //热门
            if (row.label && row.label.search('popular') != -1) {
                isPopular = true;
            }
            //精选
            if (row.label && row.label.search('feature') != -1) {
                isFeature = true;
            }
            return '<span class="<?=UNIQID?>-to-be-parse">' +
                '<input class="easyui-checkbox" data-options="onChange:function(checked){<?=JVAR?>.setLabel(this, \'banner\',checked);},id:'+row.id+',checked:'+(isBanner?'true':'false')+'"> ' +
                '<input class="easyui-checkbox" data-options="onChange:function(checked){<?=JVAR?>.setLabel(this, \'popular\',checked);},id:'+row.id+',checked:'+(isPopular?'true':'false')+'"> ' +
                '<input class="easyui-checkbox" data-options="onChange:function(checked){<?=JVAR?>.setLabel(this, \'featured\',checked);},id:'+row.id+',checked:'+(isFeature?'true':'false')+'">' +
                '</span>';
        },
        formatQrcode:function(val, row){
            return row.qrcode ? '<img class="img-thumbnail" src="' + row.qrcode + '" style="height:60px;">' : '';
        },
        onLoadSuccess:function(data){
            var that = <?=JVAR?>;
            $.parser.parse('.<?=UNIQID?>-to-be-parse');
            $(".<?=UNIQID?>-to-be-rating").rating({min:0, max:10, step:1, size:'xs', showCaption:false, animate:false, displayOnly:true});
            /**********************************/
            var rows = $(that.datagrid).datagrid('getRows');
            if(!rows){
                return;
            }
            var total = rows.length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-subject-operate-row-' + i).splitbutton();
            }
            $(that.datagrid).datagrid('fixRowHeight');
            var queryParams = $(that.datagrid).datagrid('options').queryParams;
            if(data.rows.length == 0 && queryParams.init_load){
            }
        },
        onBeforeLoad:function(){
            var that = <?=JVAR?>;
            $('.<?=UNIQID?>-to-be-parse').remove();
            $(".<?=UNIQID?>-to-be-rating").remove();
            var rows = $(that.datagrid).datagrid('getRows');
            if(!rows){
                return;
            }
            var total = rows.length;
            for(var i=0; i<total; i++){
                $('#<?=UNIQID?>-subject-operate-row-' + i).remove();
                $('#<?=UNIQID?>-subject-operate-row-menu-' + i).remove();
            }
        },
        reload:function(){
            var that = <?=JVAR?>;
            $(that.datagrid).datagrid('reload');
        },
        search:function(){
            var that = <?=JVAR?>;
            var isValid = $(that.searchForm).form('validate');
            if(!isValid){
                return;
            }
            var data = {};
            $.each($(that.toolbar).children('form').serializeArray(), function() {
                data[this['name']] = this['value'];
            });
            $(that.datagrid).datagrid('load', data);
        },
        reset:function(){
            var that = <?=JVAR?>;
            $(that.searchForm).form('reset');
            $(that.datagrid).datagrid('load', {});
        },
        setSort:function(newValue, oldValue){
            var subjectId = $(this).numberbox('options').id;
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Subject/setSort')?>',{subjectId:subjectId,
                    sort:newValue
                },
                function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        <?=JVAR?>.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                }, 'json');
        },
        setLabel:function(that, label, checked){
            var subjectId = $(that).checkbox('options').id;
            $.messager.progress({text:'处理中，请稍候...'});
            $.post('<?=url('index/Subject/setLabel')?>',{subjectId:subjectId,
                    label:label,
                    action:checked
                },
                function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        <?=JVAR?>.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                        //恢复原来的状态
                        <?=JVAR?>.reload();
                    }
                }, 'json');
        },
        test:function(id, title){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Subject/test')?>?';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $(that.dialog).dialog({
                title: '模拟测试 - ' + title,
                iconCls: 'fa fa-flask',
                width: <?=$loginMobile?"'100%'":"'60%'"?>,
                height: "100%",
                href: href,
                modal: true,
                onClose:$.noop,
                closable: true,
                buttons:[{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        edit:function(id, title='', index=0){
            var that = <?=JVAR?>;
            if(!id) {
                //新增
                var href = '<?=$urlHrefs['save']?>';
            }else {
                //修改
                var href = '<?=url('index/Subject/edit')?>';
                href = GLOBAL.func.addUrlParam(href, 'id', id);
            }
            $(that.dialog).dialog({
                title: id?('编辑量表 - ' + title):'新增量表',
                iconCls: id?'fa fa-pencil':'fa fa-plus-circle',
                width: <?=$loginMobile?"'100%'":"'90%'"?>,
                height: "100%",
                href: href,
                modal: true,
                cache: false,
                onBeforeClose:function(){
                    if($('video', $(that.dialog)).length) {
                        $('video', $(that.dialog))[0].pause();
                    }
                    return true;
                },
                onClose:function(){
                    //新建/编辑量表
                    if(subjectSaveModule.savedFlag){
                        that.reload();
                        //$(that.datagrid).datagrid('scrollTo', index);
                    }
                },
                onDestroy:function(){
                    console.log('onDestroy');
                },
                buttons:[]
            });
            $(that.dialog).dialog('center');
        },
        qrcode:function(id, qrcode){
            var that = <?=JVAR?>;
            if (qrcode) {
                QT.helper.view({
                    title:'二维码',
                    iconCls: 'fa fa-qrcode',
                    width:<?=$loginMobile?"'100%'":600?>,
                    height:'70%',
                    url:'<?=url('index/Subject/qrcode')?>?id='+id
                });
                return;
            }
            $.messager.confirm('提示','确定生成二维码吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Subject/qrcode')?>',{id:id},function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.qrcode(id, 1);
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        },
        qrcodeInfo:function(id, title){
            var that = <?=JVAR?>;
            var subjectUrl = '<?=url('mp/Subject/detail', '', true, true)?>';
            subjectUrl = GLOBAL.func.addUrlParam(subjectUrl, 'id', id);
            commonModule.qrcodeInfo(subjectUrl, title);
        },
        clone:function(id, title){
            var that = <?=JVAR?>;
            $.messager.confirm('提示','确定克隆"'+title+'"吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Subject/clone')?>',{subjectId:id},function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        },
        del:function(id, title){
            var that = <?=JVAR?>;
            $.messager.confirm('提示','确定删除"'+title+'"吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Subject/remove')?>',{id:id},function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        },
        delForce:function(id, title){
            var that = <?=JVAR?>;
            $.messager.confirm('提示','确定硬删除"'+title+'"吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Subject/removeForce')?>',{id:id},function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        },
        view:function(id, name){
            var that = <?=JVAR?>;
            var href = '<?=url('index/Subject/view')?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $(that.dialog).dialog({
                title: name + ' - 量表查看',
                iconCls: 'fa fa-eye',
                width: <?=$loginMobile?"'100%'":"'70%'"?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
                collapsible: false,
                minimizable: false,
                resizable: false,
                maximizable: false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        restore:function(id, title){
            var that = <?=JVAR?>;
            $.messager.confirm('提示','确定恢复"'+title+'"吗？',function(y){
                if(!y) { return; }
                $.messager.progress({text:'处理中，请稍候...'});
                $.post('<?=url('index/Subject/restore')?>',{id:id},function(res){
                    $.messager.progress('close');
                    if (res.code) {
                        $.app.method.tip('提示', res.msg, 'info');
                        that.reload();
                    } else {
                        $.app.method.alertError(null, res.msg);
                    }
                },'json');
            });
        },
        orders:function(id, title){
            var that = <?=JVAR?>;
            var iconCls = 'fa fa-navicon';
            var dialogTitle = title + ' - 测评订单';
            var href = '<?=url('index/SubjectOrder/orders')?>';
            href = GLOBAL.func.addUrlParam(href, 'subject_id', id);
            $(that.dialog).dialog({
                title: dialogTitle,
                iconCls: iconCls,
                width: <?=$loginMobile?"'100%'":"'80%'"?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
                maximizable: false,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'关闭',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        //导入量表
        importSubjectFromPool:function(){
            var that = <?=JVAR?>;
            var href = '<?=$urlHrefs['import']?>';
            $(that.dialog).dialog({
                title: '导入量表',
                iconCls: 'fa fa-reply-all',
                width: <?=$loginMobile?"'100%'":"'70%'"?>,
                height: '100%',
                cache: false,
                href: href,
                modal: true,
                onClose: $.noop,
                closable: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        if(subjectSelectorModule.subjects_selector_ids.length == 0){
                            $(that.dialog).dialog('close');
                            return;
                        }
                        $.messager.progress({text:'处理中，请稍候...'});
                        $.post('<?=url('index/Subject/importSubjectFromPool')?>', {subjectIds:subjectSelectorModule.subjects_selector_ids}, function(res){
                            $.messager.progress('close');
                            if(!res.code){
                                $.app.method.alertError(null, res.msg);
                            }else{
                                $.app.method.tip('提示', res.msg, 'info');
                                $(that.dialog).dialog('close');
                                that.reload();
                            }
                        }, 'json');
                    }
                },{
                    text:'取消',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $(that.dialog).dialog('close');
                    }
                }]
            });
            $(that.dialog).dialog('center');
        },
        reportDemoImages:function(id){
            var that = <?=JVAR?>;
            var iconCls = 'fa fa-navicon';
            var href = '<?=url('index/Subject/reportDemoImages')?>';
            href = GLOBAL.func.addUrlParam(href, 'id', id);
            $(that.dialog).dialog({
                title: '示例报告图片',
                iconCls: iconCls,
                width: <?=$loginMobile?"'90%'":"450"?>,
                height: '70%',
                cache: false,
                href: href,
                modal: true,
                maximizable: false,
                onClose: $.noop,
                closable: true,
                buttons:[]
            });
            $(that.dialog).dialog('center');
        },
    };
</script>