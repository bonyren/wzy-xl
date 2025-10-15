<?php
use app\index\logic\Defs as IndexDefs;
?>
<div class="form-container">
    <form id="studio-setting-form" class="form-body" method="post">
        <table class="table-form" cellpadding="5">
            <tr>
                <td class="field-label" style="width: 20%;">名称</td>
                <td class="field-input">
                    <input class="easyui-textbox" name="formData[store_name]" value="<?=$formData['store_name']?>"
                        data-options="required:true,width:'100%',validType:['length[2,60]']">
                </td>
            </tr>
            <tr>
                <td class="field-label">描述</td>
                <td class="field-input">
                    <textarea class="easyui-textbox" name="formData[store_desc]"
                        data-options="width:'100%',height:200,validType:['length[2,200]'],multiline:true,"><?=$formData['store_desc']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="field-label">联系方式</td>
                <td class="field-input">
                    <textarea class="easyui-textbox" name="formData[store_contact]"
                        data-options="width:'100%',height:60,validType:['length[2,200]'],multiline:true,"><?=$formData['store_contact']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="field-label">首页模块</td>
                <td class="field-input">
                    <div class="easyui-checkgroup" data-options="name:'formData[store_index_sections][]',data:studioSettingModule.indexSectionsData,value:[<?=$formData['store_index_sections']?>],labelWidth:100"></div>
                </td>
            </tr>
            <tr>
                <td class="field-label">底部导航栏</td>
                <td class="field-input">
                    <div class="easyui-checkgroup" data-options="name:'formData[store_bottom_tabs][]',data:studioSettingModule.bottomTabsData,value:[<?=$formData['store_bottom_tabs']?>],labelWidth:100"></div>
                </td>
            </tr>
        </table>
    </form>
    <div class="form-toolbar">
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:iconClsDefs.save,
                    onClick:function(){
                        studioSettingModule.save();
                    }">保存
        </a>
        &nbsp;
        <?php if(!empty($_GET['callback_cancel'])){ ?>
        <a class="easyui-linkbutton" href="javascript:;"  data-options="iconCls:'fa fa-close',
                    onClick:function(){
                        studioSettingModule.cancel();
                    }">取消
        </a>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    var studioSettingModule = {
        indexSectionsData:[],
        bottomTabsData:[],
        init:function(){
            <?php
                $indexSectionsData = [];
                foreach(IndexDefs::STORE_INDEX_SECTIONS as $value=>$label){
                    $indexSectionsData[] = [
                        'value'=>$value,
                        'label'=>$label,
                        'disabled'=>false
                    ];
                }
                echo 'studioSettingModule.indexSectionsData=' . json_encode($indexSectionsData) . ';';
                $bottomTabsData = [];
                foreach(IndexDefs::STORE_BOTTOM_TAB as $value=>$label){
                    $bottomTabsData[] = [
                        'value'=>$value,
                        'label'=>$label,
                        'disabled'=>$value==IndexDefs::STORE_BOTTOM_TAB_INDEX?true:false
                    ];
                }
                echo 'studioSettingModule.bottomTabsData=' . json_encode($bottomTabsData) . ';';
            ?>
        },
        save:function(){
            $('#studio-setting-form').form('submit', {
                url:'<?=url('index/Studio/setting')?>',
                iframe: false,
                onSubmit: function(){
                    var isValid = $(this).form('validate');
                    if (!isValid) return false;
                    $.messager.progress({text:'处理中，请稍候...'});
                    return true;
                },
                success:function(data){
                    $.messager.progress('close');
                    var res = eval('(' + data + ')');
                    if(!res.code){
                        $.app.method.alertError(null, res.msg);
                    }else{
                        $.app.method.tip('提示', res.msg, 'info');
                        <?php if(!empty($_GET['callback_submit'])){ ?>
                            eval('<?=$_GET['callback_submit']?>');
                        <?php } ?>
                    }
                }
            });
        },
        cancel:function(){
            <?php if(!empty($_GET['callback_cancel'])){ ?>
                eval('<?=$_GET['callback_cancel']?>');
            <?php } ?>
        }
    };
    studioSettingModule.init();
</script>