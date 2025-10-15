<div class="easyui-layout" data-options="fit:true,border:false">
    <div data-options="region:'north',height:'30%',title:'已选量表',border:false,split:false,collapsible:false">
        <div style="padding:5px; width: 100%;" id="subject-selector-container">
        <?php
        foreach($subjects as $subject){
        ?>
            <a id="subject_button_<?=$subject['id']?>" style="margin:5px;" href="javascript:void(0)" class="easyui-splitbutton" data-options="menu:'#subject_menu_<?=$subject['id']?>',plain:false"><?=$subject['name']?></a>
            <div id="subject_menu_<?=$subject['id']?>">
                <div data-options="iconCls:'icon-cancel'" onclick="subjectSelectorModule.removeFromSelector(<?=$subject['id']?>)">移除</div>
            </div>
        <?php } ?>
        </div>
    </div>
    <div data-options="region:'center',title:'待选量表池',border:true,split:false,collapsible:false">
        <table id="<?=DATAGRID_ID?>" class="easyui-datagrid" data-options="striped:true,
            nowrap:false,
            rownumbers:false,
            border:false,
            toolbar:'#<?=TOOLBAR_ID?>',
            autoRowHeight:false,
            singleSelect:true,
            selectOnCheck:false,
            checkOnSelect:false,
            pagination:true,
            pageSize:<?=DEFAULT_PAGE_ROWS?>,
            pageList:[10,20,30,50,80,100],
            sortName:'rating',
            sortOrder:'desc',
            url:'<?=$urlHrefs['datagrid']?>',
            method:'post',
            fit:true,
            fitColumns:<?=$loginMobile?'false':'true'?>,
            onLoadError:function(){
                console.log('onLoadError');
            },
            onLoadSuccess:function(data){
                subjectSelectorModule.onLoadSuccess(data);
            }
            ">
            <thead>
            <tr>
                <th data-options="field:'opt',width:100,align:'center',formatter:subjectSelectorModule.operate,hstyler:GLOBAL.func.hstyleOpt,styler:GLOBAL.func.hstyleOpt">操作</th>
                <th data-options="field:'image_url',width:80,align:'center',formatter:subjectSelectorModule.formatImage.bind(subjectSelectorModule)">图片</th>
                <th data-options="field:'sn',width:100,align:'center'">编号</th>
                <th data-options="field:'name',width:200,align:'center'">量表</th>
                <th data-options="field:'category',width:100,align:'center'">分类</th>
                <th data-options="field:'item_num',width:100,align:'center'">题目数量</th>
                <th data-options="field:'rating',width:80,align:'center',sortable:true,formatter:subjectSelectorModule.formatRating.bind(subjectSelectorModule)">评分</th>
                <th data-options="field:'standalone_vip',width:100,align:'center',formatter:subjectSelectorModule.formatVip">会员专享</th>
            </tr>
            </thead>
        </table>
        <div id="<?=TOOLBAR_ID?>">
            <form class="easyui-form datagrid-search-form-container">
                <div class="datagrid-search-form-box">
                    <input name="search[name]" class="easyui-textbox" prompt="量表名称" data-options="validType:['length[0,60]']" style="width:150px;">
                </div>
                <div class="datagrid-search-form-box">
                    <select name="search[vip]" class="easyui-combobox" prompt="提供方式" style="width:150px;" data-options="editable:false,value:''">
                        <option value="0">免费</option>
                        <option value="1">会员专享</option>
                    </select>
                </div>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="fa fa-search" onclick="subjectSelectorModule.query(this)">查询</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="fa fa-reply" onclick="subjectSelectorModule.reset(this)">重置</a>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var subjectSelectorModule = {
        maxAllowed: <?=$maxAllowed??'0'?>,
        subjects_selector_ids: <?=json_encode($subjectIds)?>,
        datagrid:'#<?=DATAGRID_ID?>',
        onLoadSuccess:function(data){
            if(data.code === 0){
                //异常
                $.app.method.alertError(null, data.msg);
                return;
            }
            $(".to-be-rating").rating({min:0, max:10, step:1, size:'xs', showCaption:false, animate:false, displayOnly:true});
        },
        query: function(that){
            let $form = $(that).parent('form');
            if(!$form.form('validate')){
                return;
            }
            var queryParams = $(this.datagrid).datagrid('options').queryParams;
            //reset the query parameter
            $.each($form.serializeArray(), function() {
                queryParams[this['name']] = this['value'];
            });
            $(this.datagrid).datagrid('load');

        },
        reset: function(that){
            if(that){
                $(that).closest('form').form("reset");
            }
            var queryParams = $(this.datagrid).datagrid('options').queryParams;
            //reset the query parameter
            $.each($(that).closest('form').serializeArray(), function() {
                delete queryParams[this['name']];
            });
            $(this.datagrid).datagrid('load');
        },
        reload: function(){
            $(this.datagrid).datagrid('reload');
        },
        operate: function (val, row) {
            var btns = [];
            btns.push('<a href="javascript:;" class="btn btn-primary size-MINI" onclick="subjectSelectorModule.addToSelector('+row.id+',\'' + row.name + '\')"><span class="fa fa-arrow-circle-up">选择</span></a>');
            return btns.join(' | ');
        },
        formatImage:function(val, row){
            return '<img class="img-thumbnail" src="' + row.image_url + '" style="height:60px;">';
        },
        formatRating:function(val, row){
            return '<input value="' + row.rating + '" type="text" class="to-be-rating" />';
        },
        formatVip:function(val, row){
            if(!val){
                return '<span class="badge badge-success"><i class="fa fa-diamond"></i>免费</span>';
            }
            return '<span class="badge badge-warning"><i class="fa fa-diamond"></i>会员专享</span>';
        },
        addToSelector:function(id, name){
            if($.inArray(id, subjectSelectorModule.subjects_selector_ids) != -1){
                return;
            }
            if(subjectSelectorModule.maxAllowed && subjectSelectorModule.subjects_selector_ids.length >= subjectSelectorModule.maxAllowed){
                $.app.method.alert(null, `超出允许选择的最大数量${subjectSelectorModule.maxAllowed}`);
                return;
            }
            var selectorHtml = '<a id="subject_button_'+id+'" style="margin:5px;" href="javascript:void(0)" class="easyui-splitbutton" data-options="menu:\'#subject_menu_' + id + '\',plain:false">' + name + '</a> \
                <div id="subject_menu_' + id + '"> \
                    <div data-options="iconCls:\'icon-cancel\'" onclick="subjectSelectorModule.removeFromSelector(' + id + ')">删除</div> \
                </div>';
            $('#subject-selector-container').append(selectorHtml);
            $('#subject_button_' + id).splitbutton();
            subjectSelectorModule.subjects_selector_ids.push(id);
        },
        removeFromSelector:function(id){
            var pos = subjectSelectorModule.subjects_selector_ids.indexOf(id);
            if(pos != -1){
                subjectSelectorModule.subjects_selector_ids.splice(pos, 1);
            }
            $('#subject_button_' + id).splitbutton('destroy');
        }
    };
</script>