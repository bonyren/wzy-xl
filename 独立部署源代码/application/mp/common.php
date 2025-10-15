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
use think\Cookie;
function generateShareDesc($desc, $len = 100){
    return sanitizeStringForJsVariable(mb_truncateString(trim(strip_tags(htmlspecialchars_decode($desc??''))), $len));
}
?>