<?php
 namespace app\index\service; use app\index\model\Setting as settingModel; class Setting extends Base { protected $_model; protected function __construct() { parent::__construct(); $this->_model = new settingModel(); } public function setting($field) { return $this->_model->getSetting($field); } }
