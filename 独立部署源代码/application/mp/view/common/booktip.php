<ion-modal id="appoint-book-tip" is-open="false">
    <ion-header>
      <ion-toolbar color="bg">
        <ion-title color="action">预约说明</ion-title>
        <ion-buttons slot="end">
          <ion-button size="small" color="medium" fill="solid" onclick="document.getElementById('appoint-book-tip').dismiss()" strong="true">关闭</ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <ion-content color="bg" class="ion-padding">
        <?=systemSetting('appoint_tip')?>
    </ion-content>
</ion-modal>