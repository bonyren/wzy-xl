<form>
	<table class="table-form" cellpadding="5">
		<tr>
			<td width="100" class="field-label">上级菜单</td>
			<td>
                <input class="easyui-combotree" name="data[pid]" value="<?=intval($row['pid'])?>" style="width:100%;"
                       data-options="url:'<?=$tree_data_url?>'">
            </td>
		</tr>
		<tr>
			<td class="field-label">名称</td>
			<td><input class="easyui-textbox" required="true" name="data[name]" value="<?=$row['name']?>" style="width:100%"></td>
		</tr>
		<tr>
			<td class="field-label">排序</td>
			<td><input class="easyui-numberbox" min="0" max="10000" name="data[order_id]" value="<?=$row['order_id']?>" style="width:100%"></td>
		</tr>
		<tr>
			<td class="field-label">控制器</td>
			<td><input class="easyui-textbox" name="data[c]" value="<?=$row['c']?>" style="width:100%"></td>
		</tr>
		<tr>
			<td class="field-label">方法名</td>
			<td><input class="easyui-textbox" name="data[a]" value="<?=$row['a']?>" style="width:100%"></td>
		</tr>
		<tr>
			<td class="field-label">附加参数</td>
			<td><input class="easyui-textbox" name="data[params]" value="<?=$row['params']?>" style="width:100%"></td>
		</tr>
		<tr>
			<td class="field-label">iconCls</td>
			<td><input class="easyui-textbox" name="data[icon_cls]" value="<?=$row['icon_cls']?>" style="width:100%"></td>
		</tr>
	</table>
</form>