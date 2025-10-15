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
use think\Controller;
use think\Log;
use think\Debug;
use think\Request;
use app\index\logic\Defs as IndexDefs;
use app\index\logic\Messages as MessagesLogic;

class Messages extends Common{
    public function index($page=1,$rows=DEFAULT_PAGE_ROWS,$sort='',$order=''){
        if(request()->isGet()){
            $urlHrefs = [
                'index'=>url('index/Messages/index'),
                'content'=>url('index/Messages/content')
            ];
            $this->assign('urlHrefs', $urlHrefs);
            return $this->fetch();
        }
        $messagesLogic = MessagesLogic::newObj();
        return json($messagesLogic->load($this->loginUserId, $page, $rows, $sort, $order));
    }
    public function markRead($messageId){
        $messagesLogic = MessagesLogic::newObj();
        $messagesLogic->markRead($messageId);
        return ajaxSuccess();
    }
    public function markAllRead(){
        $messagesLogic = MessagesLogic::newObj();
        $messagesLogic->markAllRead($this->loginUserId);
        return ajaxSuccess();
    }
    public function markSelectedRead(){
        $messagesLogic = MessagesLogic::newObj();
        $messageIds = input('post.messageIds/a');
        if(empty($messageIds)){
            return ajaxSuccess();
        }
        $messagesLogic->markSelectedRead($messageIds);
        return ajaxSuccess();
    }
    public function content($messageId){
        $messagesLogic = MessagesLogic::newObj();
        $infos = $messagesLogic->getInfos($messageId);
        if(empty($infos)){
            return $this->fetch('common/error', ['msg'=>'无法找到该消息']);
        }
        $this->assign('content', $infos['content']);
        return $this->fetch();
    }
}