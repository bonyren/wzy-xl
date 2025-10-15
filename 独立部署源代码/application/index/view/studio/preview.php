<?php
use app\index\logic\Defs as IndexDefs;
?>
<div id="studioPreviewLayout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'<?=$loginMobile?'north':'west'?>',minWidth:200,title:'/配置/',collapsible:false,border:false" 
    style="<?=$loginMobile?'height:50%;':'width:40%;'?>">
        <table class="table table-bordered table-sm" cellpadding="5">
            <tr>
                <td class="table-active" style="width: 15%;">名称</td>
                <td><?=$studio['store_name']?></td>
            </tr>
            <tr>
                <td class="table-active">介绍</td>
                <td><?=nl2br($studio['store_desc'])?></td>
            </tr>
            <tr>
                <td class="table-active">联系</td>
                <td><?=nl2br($studio['store_contact'])?></td>
            </tr>
            <tr>
                <td class="table-active">网址</td>
                <td>
                    <a id="my-h5-store-home-url-anchor" href="<?=SITE_URL.'/mp'?>" target="_blank">
                        <span class="text-info"><?=SITE_URL.'/mp'?></span>
                    </a>(请将此链接挂载到您的微信公众号)
                    <button id="copy-my-h5-store-home-url" class="btn btn-secondary size-MINI" onclick="return false;">复制</button>
                </td>
            </tr>
            <tr>
                <td class="table-active">首页模块</td>
                <td>
                    <?php foreach(IndexDefs::STORE_INDEX_SECTIONS as $value=>$label){ ?>
                        <input class="easyui-checkbox" label="<?=$label?>" data-options="labelWidth:60,labelPosition:'after',disabled:true,checked:<?=in_array($value, $studio['store_index_sections'])?'true':'false'?>" />
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td class="table-active">底部导航栏</td>
                <td>
                    <?php foreach(IndexDefs::STORE_BOTTOM_TAB as $value=>$label){ ?>
                        <input class="easyui-checkbox" label="<?=$label?>" data-options="labelWidth:45,labelPosition:'after',disabled:true,checked:<?=in_array($value, $studio['store_bottom_tabs'])?'true':'false'?>" />
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="text-center">
                    <a class="easyui-linkbutton" href="javascript:;" data-options="iconCls:'fa fa-qrcode',
                        onClick:function(){
                            studioPreviewModule.qrcodeInfo();
                        }">咨询室二维码
                    </a>
                <?php if($loginCurMenuPriv == IndexDefs::AUTHORIZE_READ_WRITE_TYPE){ ?>
                    <a class="easyui-linkbutton" href="javascript:;" data-options="iconCls:'fa fa-pencil',
                        onClick:function(){
                            studioPreviewModule.modifyStudioSetting();
                        }">修改
                    </a>
                <?php } ?>
                </td>
            </tr>
        </table>
    </div>
    <div data-options="region:'center',title:'/首页预览，完整版请手机端微信扫描咨询室二维码体验/',border:false">
        <div class="d-flex flex-wrap justify-content-center align-items-center bg-light" 
            style="position:relative;height:100%;">
            <iframe name="studio-preview" width="375" height="98%" frameborder="1" 
                align="middle" scrolling="auto" src="<?=$url?>">
            </iframe>
            <div style="position:absolute;z-index:999;height:100%;width:375px;background-color:rgba(255,255,255,0.3);">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
        //!必须挂载到window(全局)空间，否则多次打开该界面会导致多次初始化
    if(!window.storeHomeUrlClipboard){
        window.storeHomeUrlClipboard = new ClipboardJS('#copy-my-h5-store-home-url', {
            target: function(trigger) {
                return document.getElementById('my-h5-store-home-url-anchor');
            }
        });
        window.storeHomeUrlClipboard.on('success', function(e) {
            $.messager.tip("复制成功");
        });
        window.storeHomeUrlClipboard.on('error', function(e) {
            console.log(e);
        });
    }
    var studioPreviewModule = {
        dialog: '#globel-dialog-div',
        qrcodeInfo:function(id, title){
            commonModule.qrcodeInfo('<?=SITE_URL.'/mp/'?>', '<?=$studio['store_name']?>');
        },
        modifyStudioSetting:function(){
            var that = this;
            var href = '<?=url('index/Studio/setting')?>';
            href = GLOBAL.func.addUrlParam(href, 'callback_cancel', '$("#globel-dialog-div").dialog("close");');
            href = GLOBAL.func.addUrlParam(href, 'callback_submit', 'studioPreviewModule.reloadPanel();');
            $(that.dialog).dialog({
                title: '修改微信H5咨询室设置',
                iconCls: 'fa fa-pencil',
                width: <?=$loginMobile?"'100%'":"'45%'"?>,
                height: '80%',
                cache: false,
                href: href,
                modal: true,
                onClose: function(){
                },
                closable: true,
                buttons:[]
            });
            $(that.dialog).dialog('center');
        },
        reloadPanel:function(){
            $("#globel-dialog-div").dialog("close");
            if(document.getElementById('m-content')){
                $('#m-content').panel('refresh');
            }else{
                window.baseModule.reloadPanel();
            }
        }
    };
</script>