<?php
use app\Defs;
?>
<form>
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" width="150">维度因子</td>
            <td>
                <strong><?=$standardName?></strong>
            </td>
        </tr>
        <tr>
            <td class="field-label">评判分数类型</td>
            <td>
                <select class="easyui-combobox" name="formData[weight_type]" data-options="required:true,editable:false,width:'100%',value:'<?=$formData['weight_type']?>',panelHeight:'auto'">
                    <?php foreach (Defs::LATITUDE_MEASURE_WEIGHT_TYPES as $type=>$text){
                        if($type == Defs::LATITUDE_MEASURE_WEIGHT_STANDARD && !$standardWeightEnabled){
                            continue;
                        } 
                        ?>
                        <option value="<?=$type?>"><?=$text?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="field-label">评判表达式</td>
            <td>
                <!--
                <p>
                    <strong>预定义常量</strong><br/>
                    总分: <span class="text-primary">${TW}</span>； 
                    平均分: <span class="text-primary">${AW}</span>； 
                    阳性项目数: <span class="text-primary">${PIC}</span>； 
                    阴性项目数: <span class="text-primary">${NIC}</span>； 
                    阳性平均分: <span class="text-primary">${PAW}</span>；
                </p>
                <p>
                    <strong>逻辑运算符</strong><br/>
                    逻辑与: <span class="text-primary">&&</span>；
                    逻辑或: <span class="text-primary">||</span>；
                </p>
                <p>
                    <strong>关系运算符</strong><br/>
                    大于: <span class="text-primary">></span>；
                    大于等于: <span class="text-primary">>=</span>；
                    小于: <span class="text-primary"><</span>；
                    小于等于: <span class="text-primary"><=</span>；
                    等于: <span class="text-primary">=</span>；
                    不等于: <span class="text-primary">!=</span>；
                </p>
                -->
                <div id="expression-formula"></div>
                <input type="hidden" id="expression-json-input" name="formData[expression_json]" value="">
                <!--
                <textarea class="easyui-textbox" name="formData[expression]"
                       data-options="required:true,width:'100%',height:70,multiline:true,validType:['length[0,256]', 'remote[\'<?=$urlHrefs['checkStandardLatitudeExpression']?>\', \'expression\']']"><?=$formData['expression']?></textarea>
                -->
            </td>
        </tr>
        <tr>
            <td class="field-label">结论解析</td>
            <td>
                <textarea class="easyui-textbox" name="formData[stand_desc]"
                       data-options="required:true,width:'100%',height:150,multiline:true,validType:['length[0,1000]']"><?=$formData['stand_desc']?></textarea>
            </td>
        </tr>
        <tr>
            <td class="field-label">测评预警</td>
            <td>
                <select class="easyui-combobox" name="formData[warning_level]" data-options="required:true,
                    editable:false,
                    showItemIcon:true,
                    width:'100%',
                    value:'<?=$formData['warning_level']?>'">
                        <option value="<?=Defs::MEASURE_WARNING_UNKOWN_LEVEL?>" iconCls="">未定义</option>
                        <option value="<?=Defs::MEASURE_WARNING_GREEN_LEVEL?>" iconCls="icons-green">无异常</option>
                        <option value="<?=Defs::MEASURE_WARNING_YELLOW_LEVEL?>" iconCls="icons-yellow">警觉</option>
                        <option value="<?=Defs::MEASURE_WARNING_RED_LEVEL?>" iconCls="icons-red">严重</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="field-label">应对建议</td>
            <td>
                <textarea class="easyui-textbox" name="formData[remark]"
                       data-options="width:'100%',height:200,multiline:true,validType:['length[0,1000]']"><?=$formData['remark']?></textarea>
            </td>
        </tr>
    </table>
</form>
<script type="text/javascript">
    var setStandardLatitudeModule = (function($){
        function init(ruleObj){
            var rules = [];
            if(ruleObj){
                rules.push(ruleObj);
            }
            var fields = [
                {field:'${TW}',title:'总分'},
                {field:'${AW}',title:'平均分'},
                {field:'${PIC}',title:'阳性项目数'},
                {field:'${NIC}',title:'阴性项目数'},
                {field:'${PAW}',title:'阳性平均分'}
            ];
            var i = 0;
            while(i<=6){
                fields.push({
                    field:'${WD' + i + '}',
                    title:`分数${i}项目数`
                });
                i++;
            }
            $('#expression-formula').filterbuilder({
                rules: rules,
                fields: fields,
                groupMenus: [
                    { name: 'condition', text: '增加条件' },
                    { name: 'group', text: '增加条件组' }
                ],
                groupOperators: [
                    { op: 'and', text: 'And 组' },
                    { op: 'or', text: 'Or 组' }
                ],
                operators:[
                    { op: '=', text: '=' },
                    { op: '!=', text: '!=' },
                    { op: '<', text: '<' },
                    { op: '<=', text: '<=' },
                    { op: '>', text: '>' },
                    { op: '>=', text: '>=' }
                ]
            });
        }
        return {
            init:function(ruleObj){
                init(ruleObj);
            },
            applyRule:function(){
                var ruleObj = $('#expression-formula').filterbuilder('getRules');
                if(ruleObj.children.length == 0){
                    //表达式为空, {"op":"and","children":[]}
                    return false;
                }
                $('#expression-json-input').val(JSON.stringify(ruleObj));
                console.log(ruleObj);
                //return false;
                return ruleObj;
            }
        };
    })(jQuery);
    setStandardLatitudeModule.init(<?=$formData['expression_json']?>);
</script>