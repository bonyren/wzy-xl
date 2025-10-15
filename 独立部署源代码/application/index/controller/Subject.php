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

namespace app\index\controller;
use app\Defs;
use app\index\logic\Defs as IndexDefs;
use think\Db;
use think\Log;
use app\index\logic\Subject as SubjectLogic;
use app\index\service\OperationLogs;
use app\index\service\RequestContext;
use app\index\service\Wzyer as WzyerService;
use app\common\service\WException;

class Subject extends Common
{
    public function index(
        $type = Defs::SUBJECT_TYPE_PSYCHOLOGY,
        $search = [], 
        $page = 1, 
        $rows = DEFAULT_PAGE_ROWS, 
        $sort = '', 
        $order = ''){
        if($this->request->isGet()) {
            $this->assign('categories', SubjectLogic::I()->getAvailableCategories());
            $this->assign('urlHrefs', [
                'datagrid'=>url('index/Subject/index', ['type'=>$type]),
                'save'=>url('index/Subject/save', ['type'=>$type]),
                'import'=>url('index/Subject/import', ['type'=>$type])
            ]);
            return $this->fetch();
        }
        $search['type'] = $type;
        $data = SubjectLogic::I()->load($search, $page, $rows, $sort, $order);
        return json($data);
    }
    public function save($id=0, $type = Defs::SUBJECT_TYPE_PSYCHOLOGY){
        if($this->request->isGet()){
            $urlHrefs = [
                'save'=>url('index/Subject/save', ['id'=>$id, 'type'=>$type])
            ];
            $this->assign('id', $id);
            $this->assign('urlHrefs', $urlHrefs);
            $this->assign('categories', SubjectLogic::I()->getAvailableCategories());
            $categoryValue = '';
            if($id){
                //修改
                $formData = SubjectLogic::I()->getSubject($id);
                $categoryValue = implode(',', SubjectLogic::I()->getCategoryIds($id));
            }else{
                //新增
                $formData = SubjectLogic::I()->getDefaultSubject();
            }
            if(!$formData){
                return $this->fetch('common/missing');
            }
            $formData['category_value'] = $categoryValue;
            $this->assign('formData', $formData);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        try {
            if(empty($id)){
                //新增设置量表类型
                $formData['type'] = $type;
            }
            SubjectLogic::I()->saveSubject($id, $formData);
            return ajaxSuccess();
        }catch (WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function edit($id){
        $subjectLogic = SubjectLogic::I();
        $subject = $subjectLogic->getSubject($id);
        if(!$subject){
            return $this->fetch('common/missing');
        }
        //判断是否允许高级编辑量表
        $this->assign('edit_rule_disabled', ($subject['report_generator'] && !loginAsAdvanced()));
        $urlHrefs = [
            'save'=>url('index/Subject/save', ['id'=>$id]),
            'saveReport'=>url('index/Subject/saveReport', ['id'=>$id]),
            'saveResult'=>url('index/Subject/saveResult', ['id'=>$id]),
            'saveItems'=>url('index/Subject/saveItems', ['id'=>$id]),
            'saveStandard'=>url('index/Subject/saveStandard', ['id'=>$id]),
            'questionForm'=>url('index/Subject/questionForm', ['id'=>$id])
        ];
        $this->assign('urlHrefs', $urlHrefs);
        return $this->fetch();
    }
    public function view($id){
        $subjectLogic = SubjectLogic::I();
        $subject = $subjectLogic->getSubject($id);
        if(!$subject){
            return $this->fetch('common/missing');
        }
        $this->assign('categories', SubjectLogic::I()->getAvailableCategories());
        $this->assign('categoryIds', SubjectLogic::I()->getCategoryIds($id));
        $this->assign('subject', $subject);
        return $this->fetch();
    }
    //轮播:banner, 热门:popular, 精选:feature
    public function setLabel($subjectId, $label, $action){
        try{
            SubjectLogic::I()->setLabel($subjectId, $label, $action);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function setSort($subjectId, $sort){
        Db::table('subject')->where(['id'=>$subjectId])->setField('sort', $sort);
        return ajaxSuccess();
    }
    public function qrcode($id){
        if($this->request->isGet()){
            //展示
            $subject = SubjectLogic::I()->getSubject($id);
            if(!$subject){
                return $this->fetch('common/missing');
            }
            $this->assign('id', $id);
            $this->assign('name', $subject['name']);
            $this->assign('qrcode', $subject['qrcode']);
            return $this->fetch();
        }
        try{
            $path = SubjectLogic::I()->qrcode($id);
            return ajaxSuccess('生成二维码成功', $path);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function remove($id){
        try{
            SubjectLogic::I()->remove($id);
            return ajaxSuccess('删除成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function removeForce($id){
        try{
            SubjectLogic::I()->removeForce($id);
            return ajaxSuccess('删除成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function restore($id){
        try{
            SubjectLogic::I()->restore($id);
            return ajaxSuccess('恢复成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    /**维度管理
     * @param $id
     */
    public function saveStandard($id){
        if($this->request->isGet()){
            $this->assign('urlHrefs', [
                'standards'=>url('Subject/standards', ['subjectId'=>$id]),
                'setStandardSort'=>url('Subject/setStandardSort', ['subjectId'=>$id]),
                'editStandard'=>url('Subject/editStandard', ['subjectId'=>$id]),
                'deleteStandard'=>url('Subject/deleteStandard', ['subjectId'=>$id]),
                'standardItems'=>url('Subject/standardItems', ['subjectId'=>$id]),
                'mapping'=>url('Subject/saveMapping', ['subjectId'=>$id])
            ]);
            return $this->fetch();
        }
    }

    /**维度列表
     * @param $subjectId
     * @return \think\response\Json
     */
    public function standards($subjectId){
        $rows = SubjectLogic::I()->loadSubjectStandards($subjectId);
        return json($rows);
    }
    public function setStandardSort($subjectId, $standardId, $sort){
        Db::table('subject_standard')->where(['id'=>$standardId])->update(['sort'=>$sort]);
        return ajaxSuccess("操作成功");
    }
    public function saveMapping($subjectId, $standardId, $id=0){
        if($this->request->isGet()){
            return $this->fetch();
        }
    }    
    /**
     * 结果解读->维度因子
     *
     * @param  mixed $subjectId
     * @return void
     */
    public function standardsResult($subjectId){
        $rows = SubjectLogic::I()->loadSubjectStandards($subjectId, true);
        return json($rows);
    }
    /******************************************************************************************************************/
    public function editStandard($subjectId, $standardId=0){
        if($this->request->isGet()){
            if(empty($standardId)){
                //新增
                $formData = [
                    'latitude'=>'',
                    'remark'=>''
                ];
            }else{
                //修改
                $formData =  SubjectLogic::I()->getSubjectStandard($standardId);
                if(!$formData){
                    return $this->fetch('common/missing');
                }
            }
            $this->assign('formData', $formData);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        try{
            SubjectLogic::I()->saveSubjectStandard($subjectId, $standardId, $formData);
            return ajaxSuccess('添加成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function deleteStandard($subjectId, $standardId){
        try{
            SubjectLogic::I()->removeSubjectStandard($subjectId, $standardId);
            return ajaxSuccess('删除成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function standardItems($subjectId, $standardId){
        $rows = Db::table('subject_item')->alias('SI')
                    ->join('subject_item_standard SIS', "SI.id=SIS.item_id and SIS.standard_id={$standardId}", 'LEFT')
                    ->where(['SI.subject_id'=>$subjectId])
                    ->field('SI.id, SI.item, SIS.id as sis_id')
                    ->order('SI.sort asc, SI.id asc')
                    ->select();
        /*
        $rows = Db::table('subject_item_standard')->alias('SIS')
            ->join('subject_item SI', "SIS.item_id=SI.id")
            ->where(['SIS.subject_id'=>$subjectId, 'SIS.standard_id'=>$standardId])
            ->field('SI.item')
            ->order('SI.id asc')
            ->select();
        */
        $footer = '包含的项目序号：';
        $itemNos = [];
        foreach($rows as $index=>$row){
            if($row['sis_id']){
                $itemNos[] = '<span class="font-weight-bold">' . ($index+1) . '</span>';
            }
        }
        $footer .= implode(', ', $itemNos);
        return json([
            'footer'=>[['item'=>$footer]],
            'rows'=>$rows
        ]);
    }
    /******************************************************************************************************************/
    /**报告设置
     * @param $id
     * @return mixed|\think\response\Json
     */
    public function saveReport($id){
        if($this->request->isGet()){
            $urlHrefs = [
                'saveReport'=>url('index/Subject/saveReport', ['id'=>$id])
            ];
            $this->assign('urlHrefs', $urlHrefs);
            $formData = SubjectLogic::I()->getSubject($id);
            if(!$formData){
                return $this->fetch('common/missing');
            }
            $this->assign('formData', $formData);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        try {
            SubjectLogic::I()->saveSubjectReport($id, $formData);
            return ajaxSuccess();
        }catch (\Exception $e){
            return ajaxError($e->getMessage());
        }
    }
    /******************************************************************************************************************/
    /**结果定义
     * @param $id
     * @return mixed
     */
    public function saveResult($id){
        if($this->request->isGet()){
            $urlHrefs = [
                'standardsResult'=>url('index/Subject/standardsResult', ['subjectId'=>$id]),
                'editStandardPoint'=>url('index/Subject/editStandardPoint', ['subjectId'=>$id]),
                'deleteStandardPoint'=>url('index/Subject/deleteStandardPoint', ['subjectId'=>$id]),
                'saveResultClone'=>url('index/Subject/saveResultClone', ['subjectId'=>$id]),
                'latitudes'=>url('index/Subject/latitudes', ['subjectId'=>$id]),
                'setStandardLatitude'=>url('index/Subject/setStandardLatitude', ['subjectId'=>$id]),
                'delStandardLatitude'=>url('index/Subject/delStandardLatitude')
            ];
            $this->assign('urlHrefs', $urlHrefs);
            $rows = Db::table('subject_standard_latitude')->where(['subject_id'=>$id])->order('id asc')->select();
            $this->assign('subjectId', $id);
            $this->assign('rows',$rows);
            return $this->fetch();
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////    
    /**
     * 标准分转换
     *
     * @param  mixed $subjectId
     * @param  mixed $standardId
     * @return void
     */
    public function editStandardPoint($subjectId, $standardId){
        if($this->request->isGet()){
            if(empty($standardId)){
                //---整体---
                $subject = SubjectLogic::I()->getSubject($subjectId);
                if(empty($subject)){
                    return $this->fetch("common/missing");
                }
                $formData = [
                    'standard_weight_min'=>$subject['standard_weight_min'],
                    'standard_weight_max'=>$subject['standard_weight_max']
                ];
            }else{
                $standard = SubjectLogic::I()->getSubjectStandard($standardId);
                if(empty($standard)){
                    return $this->fetch("common/missing");
                }
                $formData = [
                    'standard_weight_min'=>$standard['standard_weight_min'],
                    'standard_weight_max'=>$standard['standard_weight_max']
                ];
            }
            $this->assign('formData', $formData);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        if(empty($standardId)){
            //---整体---
            Db::table('subject')->where('id', $subjectId)->update($formData);
        }else{
            Db::table('subject_standard')->where(['id' => $standardId])->update($formData);
        }
        return ajaxSuccess('添加成功');
    }
    public function deleteStandardPoint($subjectId, $standardId){
        //检查是否有规则判读
        $latitude = Db::table('subject_standard_latitude')->where(['subject_id'=>$subjectId, 
            'standard_id'=>$standardId, 
            'weight_type'=>Defs::LATITUDE_MEASURE_WEIGHT_STANDARD])->find();
        if($latitude){
            return ajaxError("该标准分有结果定义规则，请先删除该定义，再删除此标准分转换");
        }
        if(empty($standardId)){
            //---整体---
            Db::table('subject')->where(['id' => $subjectId])->update(['standard_weight_min'=>null, 'standard_weight_max'=>null]);
        }else{
            Db::table('subject_standard')->where(['id' => $standardId])->update(['standard_weight_min'=>null, 'standard_weight_max'=>null]);
        }
        return ajaxSuccess('删除成功');
    }
    public function saveResultClone($subjectId, $standardId){
        if($this->request->isGet()){
            $standards = Db::table('subject_standard')
                ->where(['subject_id'=>$subjectId, 'id'=>['neq', $standardId]])
                ->column('latitude', 'id');
            $standards[0] = Defs::SUBJECT_WHOLE_STANDARD;
            unset($standards[$standardId]);
            ksort($standards);
            $this->assign('standards', $standards);
            return $this->fetch();
        }
        $standardFrom = input('post.standard_from/d');
        $latitudeText = Db::table('subject_standard')->where('id', $standardId)->value('latitude');
        //清理
        Db::table('subject_standard_latitude')->where(['standard_id'=>$standardId])->delete();
        $latitudes = Db::table('subject_standard_latitude')->where(['standard_id'=>$standardFrom])->field(true)->select();
        foreach($latitudes as $latitude){
            unset($latitude['id']);
            $latitude['standard_id'] = $standardId;
            $latitude['latitude'] = $latitudeText;
            Db::table('subject_standard_latitude')->insert($latitude);
        }
        return ajaxSuccess();
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function latitudes($subjectId, $standardId){
        $rows = SubjectLogic::I()->loadLatitudes($subjectId, $standardId);
        return json($rows);
    }    
    /**
     * 测试维度匹配表达式的合法性
     *
     * @param  mixed $oldValue
     * @param  mixed $expression
     * @return void
     */
    public function checkStandardLatitudeExpression($oldValue, $expression){
		if ($oldValue == $expression) {
			return 'true';
		}
        $expression = htmlspecialchars_decode($expression);
        $totalWeight = 0;
        $averageWeight = 0;
        $positiveItemCount = 0;
        $negativeItemCount = 0;
        $positiveAverageWeight = 0;
        $weightDistribution = [];
        $i = 0;
        while($i<=6){
            if(!isset($weightDistribution[strval($i)])){
                $weightDistribution[strval($i)] = 0;
            }
            $i++;
        }
        $expression = preg_replace('/\s+/', '', $expression);//去掉中间的空格
        $expression = str_replace('${TW}', '$totalWeight', $expression);
        $expression = str_replace('${AW}', '$averageWeight', $expression);
        $expression = str_replace('${PIC}', '$positiveItemCount', $expression);
        $expression = str_replace('${NIC}', '$negativeItemCount', $expression);
        $expression = str_replace('${PAW}', '$positiveAverageWeight', $expression);
        $i = 0;
        while($i<=6){
            $expression = str_replace('${WD' . $i . '}', '$weightDistribution[strval(' . $i . ')]', $expression);
            $i++;
        }
        $expression = 'return ' . $expression . ';';
        try{
            $result = eval($expression);
        }catch(\ParseError $e){
            return 'false';
        }
		return 'true';
	}    

    public function setStandardLatitude($subjectId, $standardId, $id=0){
        if($this->request->isGet()){
            //维度定义
            $standardName = '';
            $standardWeightEnabled = false;
            if(empty($standardId)){
                //---整体---
                $standardName = Defs::SUBJECT_WHOLE_STANDARD;
                $subject = SubjectLogic::I()->getSubject($subjectId);
                if(empty($subject)){
                    return $this->fetch('common/missing');
                }
                if(floatval($subject['standard_weight_min']) || floatval($subject['standard_weight_max'])){
                    //设置了标准分
                    $standardWeightEnabled = true;
                }
            }else{
                $standard = SubjectLogic::I()->getSubjectStandard($standardId);
                if(empty($standard)){
                    return $this->fetch('common/missing');
                }
                $standardName = $standard['latitude'];
                if(floatval($standard['standard_weight_min']) || floatval($standard['standard_weight_max'])){
                    //设置了标准分
                    $standardWeightEnabled = true;
                }
            }
            $this->assign('standardName', $standardName);
            $this->assign('standardWeightEnabled', $standardWeightEnabled);

            if(empty($id)){
                //新增
                $formData = [
                    'id'=>0,
                    'standard_id'=>$standardId,
                    'weight_type'=>$standardWeightEnabled?Defs::LATITUDE_MEASURE_WEIGHT_STANDARD:Defs::LATITUDE_MEASURE_WEIGHT_ORIGINAL,
                    'expression'=>'',
                    'expression_json'=>'',
                    'stand_desc'=>'',
                    'warning_level'=>Defs::MEASURE_WARNING_UNKOWN_LEVEL,
                    'remark'=>''
                ];
            }else{
                //修改
                $formData = SubjectLogic::I()->getLatitude($id);
                if(!$formData){
                    return $this->fetch('common/missing');
                }
                $formData['expression'] = htmlspecialchars($formData['expression']);
            }
            $this->assign('formData', $formData);
            $this->assign('urlHrefs', [
                'checkStandardLatitudeExpression'=>url('index/Subject/checkStandardLatitudeExpression', ['oldValue'=>$formData['expression']])
            ]);
            return $this->fetch();
        }
        $formData = input('post.formData/a');
        try{
            SubjectLogic::I()->saveLatitude($subjectId, $standardId, $id, $formData);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function delStandardLatitude($id){
        try{
            SubjectLogic::I()->removeLatitude($id);
            return ajaxSuccess('删除成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    /**题目管理
     * @param $id
     * @return mixed
     */
    public function saveItems($id){
        if($this->request->isGet()){
            $urlHrefs = [
                'saveItems'=>url('index/Subject/saveItems', ['id'=>$id]),
                'setItemSort'=>url('index/Subject/setItemSort', ['subjectId'=>$id]),
                'saveItem'=>url('index/Subject/saveItem', ['subjectId'=>$id]),
                'addTagItem'=>url('index/Subject/addTagItem', ['subjectId'=>$id]),
                'delItem'=>url('index/Subject/delItem', ['subjectId'=>$id])
            ];
            $this->assign('urlHrefs', $urlHrefs);
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //维度, 权重
            $standards = SubjectLogic::I()->loadSubjectStandards($id);
            $this->assign('standards', $standards);
            return $this->fetch();
        }
        $rows = SubjectLogic::I()->loadSubjectItems($id);
        return json($rows);
    }
    public function setItemSort($subjectId, $id, $sort){
        Db::table('subject_item')->where(['id'=>$id])->update(['sort'=>$sort]);
        Db::table('subject')->where(['id'=>$subjectId])->setInc('item_version');
        return ajaxSuccess("操作成功");
    }
    public function addTagItem($subjectId, $tag){
        try{
            SubjectLogic::I()->addTagItem($subjectId, $tag);
            return ajaxSuccess("操作成功");
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function saveItem($subjectId, $itemId=0){
        if($this->request->isGet()){
            if(empty($itemId)){
                //新增
                $formData = [
                    'id'=>0,
                    'type'=>1,//单选
                    'item'=>'',
                    'image'=>'',
                    'remark'=>'',
                    'standards'=>[]
                ];
                $i=1;
                while($i<=12){
                    $formData['option_'.$i] = '';
                    $formData['weight_'.$i] = '';
                    $formData['nature_'.$i] = 0;
                    $formData['image_'.$i] = '';
                    $i++;
                }
            }else {
                //修改
                $formData = SubjectLogic::I()->getSubjectItem($subjectId, $itemId);
                if (!$formData) {
                    return $this->fetch('common/missing');
                }
            }
            $this->assign('formData', $formData);
            //维度, 权重
            $standards = SubjectLogic::I()->loadSubjectStandards($subjectId);
            $this->assign('standards', $standards);
            return $this->fetch();
        }
        /******************************************************************************************/
        $formData = input('post.formData/a');
        $formOptionData = input('post.formOptionData/a');
        $standardIds = input('post.standards/a');
        if(!$standardIds){
            $standardIds = [];
        }
        try{
            SubjectLogic::I()->saveSubjectItem($subjectId, $itemId, $formData, $formOptionData, $standardIds);
            return ajaxSuccess();
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    public function delItem($subjectId, $itemId){
        try{
            SubjectLogic::I()->removeSubjectItem($subjectId, $itemId);
            return ajaxSuccess('删除成功');
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
    }
    /*******************************************************************************************************************/
    /**模拟测试
     * @param $id
     * @return mixed|\think\response\Json
     */
    public function test($id){
        if($this->request->isGet()){
            $this->assign('urlHrefs', [
                'test'=>url('index/Subject/test', ['id'=>$id]),
                'quickReport'=>url('index/Subject/quickReport', ['subjectId'=>$id])
            ]);
            //维度, 权重
            $standards = Db::table('subject_standard')->where('subject_id', $id)->order('sort asc, id asc')->column('latitude, id');
            if(empty($standards)){
                $standards = [];
            }
            $this->assign('standards', $standards);
            return $this->fetch();
        }
        $items = Db::table('subject_item')->where(['subject_id'=>$id])
            ->field('*')
            ->order('sort asc, id asc')
            ->select();

        foreach($items as &$item){
            if($item['image']){
                $item['item'] = $item['item'] . '<br />' . '<img class="my-1" src="' . generateThumbnailUrl($item['image'], 100, '/static/img/dot.png') . '" style="max-width:100px;">';
            }
            $i = 1;
            while($i <= 12){
                $weightField = 'weight_' . $i;
                if(isset($item[$weightField])){
                    //不是null
                    $item[$weightField] = round($item[$weightField]/100, 2);  
                }
                $imageField = 'image_' . $i;
                if($item[$imageField]){
                    $item[$imageField] = '<img class="my-1" src="' . generateThumbnailUrl($item[$imageField], 100, '/static/img/dot.png') . '" style="max-width:100px;">';
                }
                $i++;
            }
            $latitudes = Db::table('subject_item_standard')->alias('SIS')->join('subject_standard SS', 'SIS.standard_id=SS.id')
                ->where(['SIS.subject_id'=>$id, 'SIS.item_id'=>$item['id']])->column('SS.latitude');
            if(empty($latitudes)){
                $latitudes = [];
            }
            $item['standards'] = implode(',', $latitudes);
        }
        return json($items);
    }
    public function quickReport($subjectId){
        $postData = input('post.data/a');
        if(empty($postData)){
            exit("数据集为空");
        }
        $rows = SubjectLogic::I()->generateTestResult($subjectId, $postData);
        Log::notice("quickReport generateTestResult: " . var_export($rows, true));
        /******************************************************************************************************************/
        $this->assign('rows', $rows);
        return $this->fetch();
    }
    /*******************************************************************************************************************/
    public function getSubjectComboData(){
        $conditions = ['delete_flag'=>0];
        $rows = Db::table('subject')->field('id,name')->where($conditions)->select();
        if (empty($rows)) {
            $rows = [];
        }
        return json($rows);
    }
    public function selector($type = Defs::SUBJECT_TYPE_PSYCHOLOGY, 
        $search=[], 
        $page=1, 
        $rows=DEFAULT_PAGE_ROWS, 
        $sort='', 
        $order=''){
        if($this->request->isGet()){
            $this->assign('maxAllowed', 0);//无限制
            $this->assign('subjects', []);
            $this->assign('subjectIds', []);
            $this->assign('urlHrefs', [
                'datagrid'=>url('index/Subject/selector', ['type'=>$type])
            ]);
            return $this->fetch();
        }
        //发布
        $search['status'] = IndexDefs::ENTITY_PUBLISH_STATUS;
        //类型
        $search['type'] = $type;
        $data = SubjectLogic::I()->load($search, $page, $rows, 'id', 'desc');
        return json($data);
    }
    public function import($type = Defs::SUBJECT_TYPE_PSYCHOLOGY, 
        $search=[], 
        $page=1, 
        $rows=DEFAULT_PAGE_ROWS, 
        $sort='', 
        $order=''){
        if($this->request->isGet()){
            $this->assign('maxAllowed', 1);
            $this->assign('subjects', []);
            $this->assign('subjectIds', []);
            $this->assign('urlHrefs', [
                'datagrid'=>url('index/Subject/import', ['type'=>$type])
            ]);
            return $this->fetch('selector');
        }
        try{
            $data = WzyerService::I()->apiSubjectList($type, $search, $page, $rows, $sort, $order);
        }catch(WException $e){
            return ajaxError($e->getMessage());
        }
        return json($data);
    }
    public function importSubjectFromPool($subjectIds){
        if(empty($subjectIds)){
            return ajaxError("导入的量表为空");
        }
        try{
            foreach($subjectIds as $subjectId){
                SubjectLogic::I()->importSubject($subjectId);
            }
        }catch(WException $e){
            Log::error("importSubjectFromPool error, " . $e->getMessage());
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess();
    }     
    /**
     * 克隆量表
     *
     * @param  mixed $subjectId
     * @return void
     */
    public function clone($subjectId){
        $subjectLogic = SubjectLogic::I();
        $subject = $subjectLogic->getSubject($subjectId);
        if(!$subject){
            return ajaxError("无法找到该量表");
        }
        try{ 
            SubjectLogic::I()->duplicateSubject($subjectId);
        }catch(\Exception $e){
            Log::error("duplicate error, " . $e->getMessage());
            return ajaxError($e->getMessage());
        }
        return ajaxSuccess("克隆成功");
    }
    public function questionForm($id){
        if($this->request->isGet()){
            $subject = SubjectLogic::I()->getSubject($id);
            if(!$subject){
                return $this->fetch('common/missing');
            }
            $questionForm = $subject['question_form'];
            if(empty($questionForm)){
                $questionForm = '';
            }
            if($questionForm){
                $questionItems = json_decode($questionForm, true);
                $questionForm = '';
                foreach($questionItems as $questionItem){
                    $questionForm .= '<li class="list-group-item question-form-item">
                                        <div class="question-form-item-opt">
                                            <button class="btn btn-primary size-MINI radius" onclick="subjectQuestionFormModule.editFieldItem(this)">编辑</button>
                                            <button class="btn btn-warning size-MINI radius" onclick="subjectQuestionFormModule.removeFieldItem(this)">删除</button>
                                        </div>';
                    $questionForm .= '<div class="question-form-item-content border-left" data-type="' . $questionItem['type'] . '">';
                    $questionForm .= $questionItem['html'];
                    $questionForm .= '</div>';
                    $questionForm .= '</li>';
                }
            }
            $this->assign('questionForm', $questionForm);
            $this->assign('urlHrefs', [
                'save'=>url('index/Subject/questionForm', ['id'=>$id])
            ]);
            return $this->fetch();
        }
        //$data = json_decode(file_get_contents('php://input'), true);
        $questionForm = file_get_contents('php://input');
        Db::table('subject')->where('id', $id)->setField('question_form', $questionForm);
        Db::table('subject')->where(['id'=>$id])->setInc('item_version');
        return ajaxSuccess();

    }
    public function reportDemoImages($id){
        if($this->request->isGet()){
            $subject = SubjectLogic::I()->getSubject($id);
            if(!$subject){
                return $this->fetch('common/missing');
            }
            $images = $subject['report_demo_images'];
            if(empty($images)){
                $images = [];
            }else{
                $images = explode(',', $images);
            }
            $this->assign('images', $images);
            $this->assign('urlHrefs', [
                'save'=>url('index/Subject/reportDemoImages', ['id'=>$id])
            ]);
            return $this->fetch();
        }
        $images = input('post.images/a');
        if(empty($images)){
            $images = '';
        }else{
            $images = implode(',', $images);
        }
        Db::table('subject')->where('id', $id)->setField('report_demo_images', $images);
        return ajaxSuccess();
    }
}