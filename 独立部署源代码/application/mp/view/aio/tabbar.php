<ion-tab-bar slot="bottom">
    <ion-tab-button href="<?=$_home_url?>" selected="<?=$_current_tab=='index'?'true':'false'?>">
      <ion-icon name="home" aria-hidden="true"></ion-icon>
      <ion-label>首页</ion-label>
    </ion-tab-button>
    <ion-tab-button href="<?=url('mp/Aio/category')?>" selected="<?=$_current_tab=='category'?'true':'false'?>">
      <ion-icon name="create" aria-hidden="true"></ion-icon>
      <ion-label>测评</ion-label>
    </ion-tab-button>
    <ion-tab-button href="<?=url('mp/Aio/ucenter')?>" selected="<?=$_current_tab=='ucenter'?'true':'false'?>">
      <ion-icon name="person" aria-hidden="true"></ion-icon>
      <ion-label>我的</ion-label>
    </ion-tab-button>
</ion-tab-bar>
<style>
  ion-tab-button{
    --color-selected: #54a58c;
  }
</style>