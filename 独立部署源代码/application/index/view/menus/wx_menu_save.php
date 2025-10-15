<form>
	<table class="table-form" cellpadding="5">
		<tr>
			<td class="field-label" width="150">上级菜单</td>
			<td>
                <select class="easyui-combobox" editable="false" name="data[pid]" style="width:100%;"
					data-options="value:'<?=$pid?>'">
                    <option value="0"></option>
                    <?php foreach ($parents as $v): ?>
                    <option value="<?=$v['id']?>"><?=$v['name']?></option>
                    <?php endforeach; ?>
                </select>
            </td>
		</tr>
		<tr>
			<td class="field-label">菜单名称</td>
			<td>
				<input class="easyui-textbox" required="true" name="data[name]" value="<?=$row['name']??''?>" style="width:100%;">

			</td>
		</tr>
		<tr>
			<td class="field-label">排序</td>
			<td>
                <input class="easyui-numberbox" min="0" max="10000" name="data[sort]" value="<?=$row['sort']??''?>" style="width:100%">
			</td>
		</tr>
		<tr>
			<td class="field-label">菜单类型</td>
			<td>
                <?php foreach (\app\Defs::WX_MENU_TYPES as $k=>$v): ?>
                <input class="easyui-radiobutton" name="data[type]"
                       data-options="labelPosition:'after',labelWidth:100,label:'<?=$v?>',value:'<?=$k?>',checked:<?=($row['type']??'')==$k?'true':'false'?>">
                <?php endforeach; ?>
            </td>
		</tr>
		<tr>
			<td class="field-label">url</td>
			<td><input class="easyui-textbox" prompt="【跳转网页/小程序】必填" name="data[url]" value="<?=$row['url']??''?>" style="width:100%;"></td>
		</tr>
		<tr>
			<td class="field-label">小程序appid</td>
			<td><input class="easyui-textbox" prompt="【小程序】必填" name="data[appid]" value="<?=$row['appid']??''?>" style="width:100%;"></td>
		</tr>
		<tr>
			<td class="field-label">小程序路径</td>
			<td><input class="easyui-textbox" prompt="【小程序】必填" name="data[pagepath]" value="<?=$row['pagepath']??''?>" style="width:100%;"></td>
		</tr>
        <tr>
            <td class="field-label">key</td>
            <td><input class="easyui-textbox" prompt="【发送消息】必填" name="data[key]" value="<?=$row['key']??''?>" style="width:100%;"></td>
        </tr>
        <tr>
            <td class="field-label">消息内容</td>
            <td><input class="easyui-textbox" prompt="【发送消息】必填" name="data[key]" value="<?=$row['msg']??''?>" style="width:100%;"></td>
        </tr>
	</table>
</form>