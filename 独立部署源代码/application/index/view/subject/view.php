<table class="table table-bordered table-sm" cellpadding="5">
    <tr>
        <td colspan="2" class="table-tr-caption">基本信息</td>
    </tr>
    <tr>
        <td class="table-active" style="width: 20%;">量表编号</td>
        <td><?=$subject['sn']?></td>
    </tr>
	<tr>
        <td class="table-active" style="width: 15%;">项目图片</td>
        <td><img src="<?=generateThumbnailUrl($subject['image_url'], 100)?>" class="img-thumbnail" style="width: 120px;"></td>
    </tr>
	<tr>
        <td class="table-active">项目名称</td>
		<td><?=$subject['name']?></td>
	</tr>
	<tr>
        <td class="table-active">分类</td>
		<td>
		<?php
		foreach ($categories as $category){ 
			$selected = in_array($category['id'], $categoryIds);
		?>
			<span class="badge <?=$selected?'badge-success':'badge-default'?>"><?=$category['name']?></span>
		<?php 
		} 
		?>
		</td>
	</tr>
	<tr>
        <td class="table-active">价格（元）</</td>
		<td><?=$subject['current_price']?></td>
	</tr>
	<tr>
        <td class="table-active">预期完成时间（分钟）</td>
		<td><?=$subject['expect_finish_time']?></td>
	</tr>
	<tr>
        <td class="table-active">测评介绍</td>
		<td><?=htmlspecialchars_decode($subject['subject_desc'])?></td>
	</tr>
	<tr>
        <td class="table-active">轮播图</td>
		<td><img src="<?=generateThumbnailUrl($subject['banner_img'], 100)?>" class="img-thumbnail" style="width: 120px;"></td>
	</tr>
	<tr>
        <td colspan="2" class="table-tr-caption">业务统计</td>
    </tr>
    <tr>
        <td class="table-active">测评总次数</td>
        <td><?=$subject['participants']?></td>
    </tr>
    <tr>
        <td class="table-active">测评总金额</td>
        <td><?=$subject['total_amount']?></td>
    </tr>
</table>