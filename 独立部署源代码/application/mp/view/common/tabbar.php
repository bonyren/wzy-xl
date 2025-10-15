<?php
use app\index\logic\Defs as IndexDefs;
?>
<ion-tab-bar slot="bottom">
<?php
if(in_array(IndexDefs::STORE_BOTTOM_TAB_INDEX, $_studio['store_bottom_tabs'])){ 
?>
    <ion-tab-button id="_tab_index" href="<?=$_home_url?>" selected="<?=$_home_url==$_current_url?'true':'false'?>">
      <ion-icon name="home" aria-hidden="true"></ion-icon>
      <ion-label>首页</ion-label>
    </ion-tab-button>
<?php } ?>
<?php
if(in_array(IndexDefs::STORE_BOTTOM_TAB_PSYCHOLOGY, $_studio['store_bottom_tabs'])){ 
?>
    <ion-tab-button id="_tab_subject" href="<?=url('mp/Subject/category')?>" selected="<?=$_current_tab=='subject'?'true':'false'?>">
      <ion-icon name="albums" aria-hidden="true"></ion-icon>
      <ion-label>测评</ion-label>
    </ion-tab-button>
<?php } ?>
<?php
if(in_array(IndexDefs::STORE_BOTTOM_TAB_HEALTH, $_studio['store_bottom_tabs'])){ 
?>
    <ion-tab-button id="_tab_health" href="<?=url('mp/Health/category')?>" selected="<?=$_current_tab=='health'?'true':'false'?>">
      <ion-icon name="accessibility" aria-hidden="true"></ion-icon>
      <ion-label>健康</ion-label>
    </ion-tab-button>
<?php } ?>
<?php
if(in_array(IndexDefs::STORE_BOTTOM_TAB_EXPERT, $_studio['store_bottom_tabs'])){ 
?>
    <ion-tab-button id="_tab_expert" href="<?=url('mp/Expert/index')?>" selected="<?=$_current_tab=='expert'?'true':'false'?>">
      <ion-icon name="people" aria-hidden="true"></ion-icon>
      <ion-label>预约</ion-label>
    </ion-tab-button>
<?php } ?>
<?php
if(in_array(IndexDefs::STORE_BOTTOM_TAB_MY, $_studio['store_bottom_tabs'])){ 
?>
    <ion-tab-button id="_tab_ucenter" href="<?=url('mp/Ucenter/index')?>" selected="<?=$_current_tab=='ucenter'?'true':'false'?>">
      <ion-icon name="person-circle" aria-hidden="true"></ion-icon>
      <ion-label>我的</ion-label>
    </ion-tab-button>
<?php } ?>
</ion-tab-bar>
<style>
  ion-tab-button{
    --color-selected: #54a58c;
  }
</style>