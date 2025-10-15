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
namespace app\cli\command;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\cli\controller\Dispatcher;
use think\Log;

class Scheduler extends Command
{
    protected function configure(){
        $this->setName('RunScheduler')->setDescription('run scheduler in command line');
    }

    protected function execute(Input $input, Output $output){
		Log::notice("Scheduler-execute");
        //通过自定义command执行，会导致很多index.php中定义的常量无法引用
        $dispatcher = new Dispatcher();
        $dispatcher->run();
    }
}