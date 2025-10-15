<?php
use \app\Defs;
?>
<div class="form-container">
    <form id="subject-report-form" class="form-body">
        <table class="table-form" cellpadding="5">
            <tr>
                <td class="field-label">报告组成</td>
                <td colspan="3">
                    <div class="easyui-checkgroup" data-options="name:'formData[report_elements][]',data:subjectSaveReportModule.reportElementsData,value:[<?=$formData['report_elements']?>],labelWidth:120"></div>
                </td>
            </tr>
            <tr>
                <td class="field-label">视频</td>
                <td>
                    <?=action('Video/save', ['inputCtrlName'=>'formData[video_url]', 'videoUrl'=>$formData['video_url'], 'width'=>300], 'widget')?>
                </td>
                <td class="field-label">音频</td>
                <td>
                    <?=action('Audio/save', ['inputCtrlName'=>'formData[audio_url]', 'audioUrl'=>$formData['audio_url'], 'width'=>300], 'widget')?>
                </td>
            </tr>
            <?php for($i=1; $i<=6; $i++): ?>
                <tr>
                    <td colspan="4" class="form-tip">专家建议-<strong><?=$i?></strong></td>
                </tr>
                <tr>
                    <td>
                        <?=action('Figure/save', ['inputCtrlName'=>'formData[report_image'.$i.']', 'figureUrl'=>$formData['report_image'.$i], 'width'=>120], 'widget')?>
                    </td>
                    <td colspan="3">
                        <div class="easyui-texteditor" name="formData[report_story<?=$i?>]"
                             data-options="width:'95%',height:150,validType:['length[1,1000]'],toolbar:['bold','italic','strikethrough','underline','-','justifyleft','justifycenter','justifyright','justifyfull','-','insertorderedlist','insertunorderedlist','outdent','indent','-','formatblock','fontname','fontsize']">
                            <?=htmlspecialchars_decode($formData['report_story'.$i])?>
                        </div>
                    </td>
                </tr>
            <?php endfor; ?>
        </table>
    </form>
    <div class="form-toolbar">
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.save,
                    onClick:function(){
                        subjectSaveReportModule.save();
                    }">保存
        </a>
    </div>
</div>
<script type="text/javascript">
    var subjectSaveReportModule = {
        reportElementsData:[],
        save:function(){
            var isValid = $('#subject-report-form').form('validate');
            if(!isValid){
                return;
            }
            var href = '<?=$urlHrefs['saveReport']?>';
            $.messager.progress({text:'处理中，请稍候...'});
            $.post(href, $('#subject-report-form').serialize(), function(res){
                $.messager.progress('close');
                if(!res.code){
                    $.app.method.alertError(null, res.msg);
                }else{
                    $.app.method.tip('提示', res.msg, 'info');
                }
            }, 'json');
        }
    };
    <?php
        $elementsData = [];
        foreach(Defs::REPORT_ELEMENTS as $value=>$label){
            $elementsData[] = [
                'value'=>$value,
                'label'=>$label,
                'disabled'=>false
            ];
        }
        echo 'subjectSaveReportModule.reportElementsData=' . json_encode($elementsData) . ';';
    ?>
</script>