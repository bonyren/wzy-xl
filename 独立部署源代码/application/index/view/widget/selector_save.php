<?php if(!$readonly){ ?>
<a class="easyui-linkbutton" href="javascript:void(0)" data-options="iconCls:'fa fa-plus-square-o',onClick:selectorSaveModule_<?=$uniqid?>.select">选择</a>
<div class="line my-1"></div>
<?php } ?>
<div id="select-section-<?=$uniqid?>">
    <?php foreach ($selectedRows as $selectedRow): ?>
        <span class="i-act-btn">
            <?php echo $selectedRow[$labelField]; ?>
            <?php if(!$readonly){ ?>
            <a href="javascript:void(0)" onclick="selectorSaveModule_<?=$uniqid?>.remove('<?=$selectedRow[$valueField]?>', this)">
                <i class="fa fa-close"></i>
            </a>
            <?php } ?>
        </span>
    <?php endforeach; ?>
</div>
<input type="hidden" id="select-value-<?=$uniqid?>" name="<?=$inputCtrlName?>" value="<?=$inputCtrlValue?>">
<script type="text/javascript">
    var selectorSaveModule_<?=$uniqid?> = {
        selectedValues:[],
        select:function(){
            var url = '<?=$selectUrl?>';
            var $dialog = QT.helper.genDialogId('selector-dialog');
            url = GLOBAL.func.addUrlParam(url, 'dialog_call', 1);
            url = GLOBAL.func.addUrlParam(url, 'multiple', <?=$multiple?1:0?>);
            $dialog.dialog({
                title: '请选择',
                width: <?=$loginMobile?"'100%'":"'90%'"?>,
                height: "90%",
                href: url,
                modal: true,
                border: true,
                buttons:[{
                    text:'确定',
                    iconCls:iconClsDefs.ok,
                    handler: function(){
                        var rows = $dialog.find('.easyui-datagrid').datagrid('getChecked');
                        if (!rows.length) {
                            $.app.method.tip('提示', '未选择任何数据', 'error');
                            return;
                        }
                        for(var i in rows){
                            var label = rows[i]['<?=$labelField?>'];
                            var value = String(rows[i]['<?=$valueField?>']);//转化为String
                            if($.inArray(value, selectorSaveModule_<?=$uniqid?>.selectedValues) == -1){
                                selectorSaveModule_<?=$uniqid?>.selectedValues.push(value);
                                $('#select-value-<?=$uniqid?>').val(selectorSaveModule_<?=$uniqid?>.selectedValues.join(','));
                                $('#select-section-<?=$uniqid?>').append('<span class="i-act-btn">' +
                                    label +
                                    '<a href="javascript:void(0)" onclick="selectorSaveModule_<?=$uniqid?>.remove(\''+value+'\', this)">' +
                                    '<i class="fa fa-close"></i>' +
                                    '</a>' +
                                    '</span>'
                                );
                            }
                        }
                        //关闭选择器弹窗
                        $dialog.dialog('close');
                    }
                },{
                    text:'取消',
                    iconCls:iconClsDefs.cancel,
                    handler: function(){
                        $dialog.dialog('close');
                    }
                }]
            });
            $dialog.dialog('center');
        },
        remove:function(value, that){
            selectorSaveModule_<?=$uniqid?>.selectedValues = $.grep(selectorSaveModule_<?=$uniqid?>.selectedValues, function(val, index){
                return val != value;
            });
            $('#select-value-<?=$uniqid?>').val(selectorSaveModule_<?=$uniqid?>.selectedValues.join(','));
            $(that).parent().remove();
        }
    };
    (function($){
        var nowValue = $('#select-value-<?=$uniqid?>').val();
        if(nowValue){
            selectorSaveModule_<?=$uniqid?>.selectedValues = nowValue.split(',');
        }
    })(jQuery);
</script>