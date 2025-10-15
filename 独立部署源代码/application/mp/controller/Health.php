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
namespace app\mp\controller;
use app\Defs;
use app\mp\service\Subjects;
use think\Db;
use think\Log;

class Health extends Base
{
    public function index() {}
    public function category($categoryId=0, $name='',$page=1, $rows=DEFAULT_PAGE_ROWS){
        if ($this->request->isGet()) {
            $categories = Subjects::I()->getCategories();
            $this->assign('categories', $categories);
            $this->assign('name', $name);
            $this->assign('category_url', url('mp/Health/category'));
            $this->assign('pageTitle', '健康测评');
            return $this->fetch('subject/category');
        }
        $map = [];
        $map['field'] = 'id, name, subtitle, current_price, items, participants_show as participants, image_url';
        $map['where'] = ['type'=>Defs::SUBJECT_TYPE_HEALTH];
        $map['search'] = ['category_id'=>$categoryId, 'name'=>$name];
        $result = Subjects::I()->getList($map, $page, $rows, true);
        $total = $result['total'];
        $totalReturn = ($page-1)*$rows + count($result['rows']);
        foreach($result['rows'] as &$row){
            $categoryNames = Db::table('subject_category_relate')->alias('SCR')->join('categories C', 'SCR.category_id=C.id and SCR.subject_id='.$row['id'])->column('C.name');
            $row['category_names'] = $categoryNames;
            $row['image_url'] = generateThumbnailUrl($row['image_url'], 300);
            $row['participants'] = formatTimes($row['participants']);
        }
        return json(['rows'=>$result['rows'], 'page_end'=>($totalReturn>=$total)]);
    }
}