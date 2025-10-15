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
use app\mp\service\Subjects;
use think\Log;
use think\Db;
use think\Cookie;

class Index extends Base{
    public function index(){
        //测评推荐
        $map = ['delete_flag'=>0];
        $map['field'] = 'id,name,subtitle,current_price,items,participants_show,label,image_url,banner_img';
        $rows = Subjects::I()->getList($map, 1, 1000);

        $banners = [];
        $populars = [];
        $featureds = [];

        foreach ($rows as &$row) {
            $row['participants'] = $row['participants_show'];
            if(empty($row['banner_img'])){
                //用量表图片代替轮播
                $row['banner_img'] = $row['image_url'];
            }
            //轮播图
            if(false !== strpos($row['label'], 'banner')) {
                $banners[] = $row;
            }
            //热门测评
            if (false !== strpos($row['label'], 'popular')) {
                $populars[] = $row;
            }
            //精选测评
            if(false !== strpos($row['label'], 'featured')) {
                $featureds[] = $row;
            }
        }
        foreach($populars as &$popular){
            $popular['image_url'] = generateThumbnailUrl($popular['image_url'], 300);
        }
        foreach($featureds as &$featured){
            $categoryNames = Db::table('subject_category_relate')->alias('SCR')
                ->join('categories C', 'SCR.category_id=C.id and SCR.subject_id='.$featured['id'])
                ->column('C.name');
            $featured['category_names'] = $categoryNames;
            $featured['image_url'] = generateThumbnailUrl($featured['image_url'], 300);
        }
        $this->assign([
            'banners' => $banners,
            'populars' => $populars,
            'featureds' => $featureds,
        ]);
        return $this->fetch('index/index');
    }

}