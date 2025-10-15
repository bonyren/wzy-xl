<form method="post">
    <table class="table-form" cellpadding="5">
        <tr>
            <td class="field-label" style="width: 100px;">名称:</td>
            <td class="field-input">
                <input class="easyui-textbox" name="infos[name]"
                       data-options="required:true,width:'100%',validType:['length[1,32]']"
                       value="<?=$infos['name']?>"/>
            </td>
        </tr>
        <tr>
            <td class="field-label">图片(64x64)</td>
            <td class="field-input">
                <?=action('Figure/save', ['inputCtrlName'=>'infos[img_url]', 'figureUrl'=>$infos['img_url'], 'width'=>120], 'widget')?>
            </td>
        </tr>
        <tr>
            <td class="field-label">排序</td>
            <td class="field-input">
                <input class="easyui-numberbox" name="infos[sort]" value="<?=$infos['sort']?>"
                    data-options="required:true,
                        min:1,
                        max:1000,
                        width:'100%',
                        disabled:false">
            </td>
        </tr>
    </table>
</form>