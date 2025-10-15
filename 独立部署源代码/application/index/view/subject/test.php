<?php
use app\Defs;
?>
<div id="subjectTestLayout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'west',width:'100%',split:true">
        <table id="subjectTestDatagrid" class="easyui-datagrid" data-options="striped:true,
                    nowrap:false,
                    rownumbers:false,
                    autoRowHeight:true,
                    singleSelect:true,
                    url:'<?=$urlHrefs['test']?>',
                    method:'post',
                    pagination:false,
                    border:false,
                    fit:true,
                    fitColumns:<?=$loginMobile?'false':'true'?>,
                    title:'',
                    toolbar:'#subjectTestToolbar',
                    onLoadSuccess:subjectTestModule.onLoadSuccess">
            <thead>
            <tr>
                <!--
                <th data-options="field:'id',width:60,align:'center'" rowspan="2">序号</th>
                -->
                <th data-options="field:'item',width:200" rowspan="2">题目</th>
                <th data-options="field:'options',width:200,formatter:subjectTestModule.formatOptions" rowspan="2">选项</th>
                <?php if(!empty($standards)){ ?>
                    <th data-options="field:'standards',width:100" rowspan="2">维度</th>
                <?php } ?>
                <th data-options="align:'center'" colspan="2">结果</th>
            </tr>
            <tr>
                <th data-options="field:'test_weight',width:100,align:'center',formatter:subjectTestModule.formatTestWeight">分数</th>
                <th data-options="field:'test_nature',width:100,align:'center',formatter:subjectTestModule.formatTestNature">性质</th>
            </tr>
            </thead>
        </table>
        <div id="subjectTestToolbar">
            <div class="m-1">
                <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:'fa fa-bullseye',
                            onClick:function(){
                                subjectTestModule.quickReport();
                            }">查看评估结果
                </a>
            </div>
        </div>
    </div>
    <div data-options="region:'center',split:true,title:'测评结果',height:'40%'">
            <header>
                    <div class="m-1">
                        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:'fa fa-mail-reply',
                            onClick:function(){
                                subjectTestModule.backTest();
                            }">返回模拟测试
                        </a>
                    </div>
            </header>
            <div id="subject-quick-report">

            </div>

    </div>
</div>
<script>
    var subjectTestModule = {
        subjectItems:[],
        onLoadSuccess:function(rows){
            $.each(rows.rows, function(index, row){
                subjectTestModule.subjectItems.push(row);
            });
            $.parser.parse('.to-be-parse-test');
        },
        formatOptions:function(val, row, rowIndex){
            var index = 1;
            var outputHtml = '<form class="to-be-parse-test">';
            if(row.type == <?=Defs::QUESTION_TEXT?>){
                //填写
                outputHtml += '<p class="my-1"><input class="easyui-textbox" name="subject_item_' + row['id'] + '" style="width:100%;"></p>';
            }else if(row.type == <?=Defs::QUESTION_CHECKBOX?>){
                //多选
                while (index <= 12) {
                    var optionKey = 'option_' + index;
                    if (!row[optionKey]) {
                        break;
                    }
                    var itemOptionId = row['id'] + '_' + index + '_' + rowIndex;
                    outputHtml += '<p class="my-1"><input class="easyui-checkbox" name="subject_item_' + row['id'] + '" value="' + index + '" data-options="onChange:function(checked){subjectTestModule.onOptionChange(\'' + itemOptionId + '\',checked);},checked:' + (index == 1 ? 'false' : 'false') + ',labelPosition:\'after\'" label="' + row[optionKey] + '">';
                    if(row[imageKey]){
                        outputHtml += row[imageKey];
                    }
                    outputHtml += '</p>';
                    index++;
                }
            }else if(row.type == <?=Defs::QUESTION_RADIO?>) {
                //单选
                while (index <= 12) {
                    var optionKey = 'option_' + index;
                    var imageKey = 'image_' + index;
                    if (!row[optionKey] && !row[imageKey]) {
                        break;
                    }
                    var itemOptionId = row['id'] + '_' + index + '_' + rowIndex;
                    outputHtml += '<p class="my-1"><input class="easyui-radiobutton" name="subject_item_' + row['id'] + '" value="' + index + '" data-options="onChange:function(checked){subjectTestModule.onOptionChange(\'' + itemOptionId + '\',checked);},checked:' + (index == 1 ? 'false' : 'false') + ',labelPosition:\'after\'" label="' + row[optionKey] + '">';
                    if(row[imageKey]){
                        outputHtml += row[imageKey];
                    }
                    outputHtml += '</p>';
                    index++;
                }
            }
            outputHtml += '</form>';
            return outputHtml;
        },
        formatTestWeight:function(val, row, rowIndex){
            return '<p id="test-weight-' + row.id + '" class="font-weight-bolder text-success"></p>';
        },
        formatTestNature:function(val, row, rowIndex){
            return '<p id="test-nature-' + row.id + '" ></p>';
        },
        onOptionChange:function(itemOptionId, checked){
            var that = this;
            var sections = itemOptionId.split('_');
            var itemId = sections[0];
            var optionId = sections[1];
            var rowIndex = sections[2];
            var subjectItem = subjectTestModule.subjectItems[rowIndex];
            if(subjectItem.type == <?=Defs::QUESTION_RADIO?>) {
                //单选
                if (!checked) {
                    return;
                }
                $('#test-weight-' + itemId).text(subjectItem['weight_' + optionId]);
                $('#test-nature-' + itemId).html(<?=json_encode(Defs::SUBJECT_ITEM_OPTION_HTML_NATURES)?>[subjectItem['nature_' + optionId]]);
            }else if(subjectItem.type == <?=Defs::QUESTION_CHECKBOX?>){
                //多选
                var optionIds = [];
                var weight = 0;
                var name = 'subject_item_' + itemId;
                $('input[name="' + name + '"]:checked').each(function(i){
                    optionIds.push($(this).val());
                    weight += subjectItem['weight_' + $(this).val()];
                });
                $('#test-weight-' + itemId).text(weight);
                $('#test-nature-' + itemId).text('');
            }
        },
        quickReport:function(){
            //检查所有的题是否都完成
            var completed = true;
            var data = {};
            $.each(subjectTestModule.subjectItems, function(index, row){
                var name = 'subject_item_' + row.id;
                if(row.type == <?=Defs::QUESTION_RADIO?>) {
                    //单选
                    if (!$('input[name="' + name + '"]:checked').val()) {
                        completed = false;
                        return false;
                    }
                    var val = $('input[name="' + name + '"]:checked').val();
                    data[row.id] = {
                        type: row.type,
                        value: val
                    };
                }else if(row.type == <?=Defs::QUESTION_CHECKBOX?>){
                    //多选, 数组
                    if (!$('input[name="' + name + '"]:checked').val()) {
                        completed = false;
                        return false;
                    }
                    var vals = [];
                    $('input[name="' + name + '"]:checked').each(function(i){
                        vals.push($(this).val());
                    });
                    data[row.id] = {
                        type: row.type,
                        value: vals
                    };
                }else if(row.type == <?=Defs::QUESTION_TEXT?>){
                    //填写
                    if(!$('input[name="' + name + '"]').val()){
                        completed = false;
                        return false;
                    }
                    var val = $('input[name="' + name + '"]').val();
                    data[row.id] = {
                        type: row.type,
                        value: val
                    };
                }
            });
            if(!completed){
                $.app.method.alertWarning(null, "请先答完所有题目");
                return;
            }
            $.messager.progress({text:'处理中，请稍候...'});
            var url = '<?=$urlHrefs['quickReport']?>';
            $.post(url, {data:data}, function (res) {
                $.messager.progress('close');
                $('#subject-quick-report').html(res);

                $('#subjectTestLayout').layout('collapse', 'west');
            }, 'html');
        },
        backTest:function(){
            $('#subjectTestLayout').layout('expand', 'west');
        }
    };
</script>
