<?php
// +----------------------------------------------------------------------
// | WZYCODING [ SIMPLE SOFTWARE IS THE BEST ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018~2025 wzycoding All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( https://spdx.org/licenses/GPL-2.0.html )
// +----------------------------------------------------------------------
// | Author: wzycoding <wzycoding@qq.com>
// +----------------------------------------------------------------------
namespace app\index\tagLib;
use think\template\TagLib;
defined('THINK_PATH') or exit();
/**
 * easyUI标签库
 */
class EasyUI extends TagLib{
	// 标签定义
	protected $tags   =  array(
		// 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
		'datagrid'     => array('attr'=>'id,style,options,fields','close'=>0),
		'treegrid'     => array('attr'=>'id,style,options,fields','close'=>0),
		'propertygrid' => array('attr'=>'id,style,options','close'=>0),
	);

	/**
	 * easyui - datagrid
	 * 格式： <easyui:datagrid id="id" options="options" fields="fields" style="" />
	 * @param array $tag 标签属性
	 * @return string|void
	 */
	public function tagdatagrid($tag) {
		$id    = !empty($tag['id']) ? $tag['id'] : strtolower(request()->controller().'_'.request()->action().'_datagrid');
		$style = !empty($tag['style']) ? $tag['style'] : '';
		//默认参数
		$dataOptions = array(
			'border'       => false,
			'fit'          => true,
			'fitColumns'   => true,
			'rownumbers'   => true,
			'singleSelect' => true,
			'striped'      => true,
			'pagination'   => true,
			'pageList'     => array(20,30,50,80,100),
			'pageSize'     => DEFAULT_PAGE_ROWS,
		);
		$options = $tag['options'] ? $this->autoBuildVar($tag['options']) : 'array()';
		$fields  = $tag['fields'] ? $this->autoBuildVar($tag['fields']) : 'null';
		
		$parseStr = '<table id="'. $id .'" class="easyui-datagrid" data-options=\'<?php $dataOptions = array_merge('. var_export($dataOptions, true). ', '. $options .');if(isset($dataOptions[\'toolbar\']) && substr($dataOptions[\'toolbar\'],0,1) != \'#\'): unset($dataOptions[\'toolbar\']); endif; echo trim(json_encode($dataOptions), \'{}[]\').((isset('. $options .'[\'toolbar\']) && substr('. $options .'[\'toolbar\'],0,1) != \'#\')?\',"toolbar":\'.'. $options .'[\'toolbar\']:null); ?>\' style="'. $style .'"><thead><tr>';
		$parseStr .= '<?php if(is_array('. $fields .')):foreach ('. $fields .' as $key=>$arr):if(isset($arr[\'formatter\'])):unset($arr[\'formatter\']);endif;echo "<th data-options=\'".trim(json_encode($arr), \'{}[]\').(isset('. $fields .'[$key][\'formatter\'])?",\"formatter\":".'. $fields .'[$key][\'formatter\']:null)."\'>".$key."</th>";endforeach;endif; ?>';
		$parseStr .= '</tr></thead></table>';
		
		return $parseStr;
	}
	
	/**
	 * easyui - treegrid
	 * 格式： <easyui:treegrid id="id" options="options" fields="fields" style="" />
	 * @param array $tag 标签属性
	 * @return string|void
	 */
	public function tagtreegrid($tag) {
		$id    = !empty($tag['id']) ? $tag['id'] : strtolower(request()->controller().'_'.request()->action().'_treegrid');
		$style = !empty($tag['style']) ? $tag['style'] : '';
		//默认参数
		$dataOptions = array(
			'border'       => false,
			'fit'          => true,
			'fitColumns'   => true,
			'rownumbers'   => true,
			'singleSelect' => true,
			'animate'      => true
		);
		$options = $tag['options'] ? $this->autoBuildVar($tag['options']) : 'array()';
		$fields  = $tag['fields'] ? $this->autoBuildVar($tag['fields']) : 'null';
		
		$parseStr = '<table id="'. $id .'" class="easyui-treegrid" data-options=\'<?php $dataOptions = array_merge('. var_export($dataOptions, true). ', '. $options .');if(isset($dataOptions[\'toolbar\']) && substr($dataOptions[\'toolbar\'],0,1) != \'#\'): unset($dataOptions[\'toolbar\']); endif; echo trim(json_encode($dataOptions), \'{}[]\').((isset('. $options .'[\'toolbar\']) && substr('. $options .'[\'toolbar\'],0,1) != \'#\')?\',"toolbar":\'.'. $options .'[\'toolbar\']:null); ?>\' style="'. $style .'"><thead><tr>';
		$parseStr .= '<?php if(is_array('. $fields .')):foreach ('. $fields .' as $key=>$arr):if(isset($arr[\'formatter\'])):unset($arr[\'formatter\']);endif;echo "<th data-options=\'".trim(json_encode($arr), \'{}[]\').(isset('. $fields .'[$key][\'formatter\'])?",\"formatter\":".'. $fields .'[$key][\'formatter\']:null)."\'>".$key."</th>";endforeach;endif; ?>';
		$parseStr .= '</tr></thead></table>';
		
		return $parseStr;
	}
	
	/**
	 * easyui - propertygrid
	 * 格式： <easyui:propertygrid id="id" options="options" style="" />
	 * @param array $tag 标签属性
	 * @return string|void
	 */
	public function tagpropertygrid($tag) {
		$id    = !empty($tag['id']) ? $tag['id'] : strtolower(request()->controller().'_'.request()->action().'_propertygrid');
		$style = !empty($tag['style']) ? $tag['style'] : '';
		//默认参数
		$dataOptions = array(
			'border'        => false,
			'fit'           => true,
			'showHeader'    => true,
			'columns'       => array(array(array('field'=>'name','title'=>'属性名称','width'=>80,'sortable'=>true),array('field'=>'value','title'=>'属性值','width'=>200))),
			'showGroup'     => true,
			'scrollbarSize' => 0,
		);
		$options = $tag['options'] ? $this->autoBuildVar($tag['options']) : 'array()';
		
		$parseStr = '<table id="'. $id .'" class="easyui-propertygrid" data-options=\'<?php $dataOptions = array_merge('. var_export($dataOptions, true). ', '. $options .');if(isset($dataOptions[\'toolbar\']) && substr($dataOptions[\'toolbar\'],0,1) != \'#\'): unset($dataOptions[\'toolbar\']); endif; echo trim(json_encode($dataOptions), \'{}[]\').((isset('. $options .'[\'toolbar\']) && substr('. $options .'[\'toolbar\'],0,1) != \'#\')?\',"toolbar":\'.'. $options .'[\'toolbar\']:null); ?>\' style="'. $style .'"></table>';
		
		return $parseStr;
	}
}