<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">普查介绍并请填写个人信息</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
    <ion-card>
        <ion-img alt="<?=$name?>" src="<?=generateThumbnailUrl($banner, 280, '/static/mp.ionic/img/empty-default.jpg')?>" 
            style="width: 100%;"></ion-img>
        <ion-card-header>
            <ion-card-title>
                <h3><?=$name?></h3>
            </ion-card-title>
            <ion-card-subtitle>
                <ion-chip>
                    <ion-icon name="alarm-outline"></ion-icon>
                    <ion-label><?=$costTime?>分钟</ion-label>
                </ion-chip>
                <ion-chip>
                    <ion-icon name="list-outline"></ion-icon>
                    <ion-label><?=$subjectCount?>个量表</ion-label>
                </ion-chip>
            </ion-card-subtitle>
        </ion-card-header>
        <ion-card-content>
            <?=$description?>
        </ion-card-content>
        <ion-card-content>
            <form id="personal-data-form" method="POST" enctype="application/x-www-form-urlencoded" action="<?=$submitUrl?>">
            <ion-list>
                <ion-list-header>
                    <ion-label>请录入测评者信息</ion-label>
                </ion-list-header>
                <!--姓名-->
                <?php if(in_array('name', $cfg_personal_data)){ ?>
                <ion-item>
                    <ion-input name="formData[name]" label="姓名" label-placement="fixed" type="text" placeholder="请输入真实姓名" maxlength="16" required="true" value="<?=$personal_data['name']?>"></ion-input>
                </ion-item>
                <?php } ?>
                <!--性别-->
                <?php if(in_array('sex', $cfg_personal_data)){ ?>
                    <ion-item>
                        <ion-select name="formData[sex]" label="性别" label-placement="fixed" placeholder="请选择" value="<?=$personal_data['sex']?>" cancel-text="取消" ok-text="确定">
                            <ion-select-option value="1">男</ion-select-option>
                            <ion-select-option value="0">女</ion-select-option>
                        </ion-select>
                    </ion-item>
                <?php } ?>
                <!--年龄-->
                <?php if(in_array('age', $cfg_personal_data)){ ?>
                    <ion-item>
                        <ion-input name="formData[age]" label="年龄" label-placement="fixed" type="number" placeholder="请输入您的年龄" min="1" max="120" required="true" value="<?=$personal_data['age']?>"></ion-input>
                    </ion-item>
                <?php } ?>
                <!--手机-->
                <?php if(in_array('mobile', $cfg_personal_data)){ ?>
                    <ion-item>
                        <ion-input name="formData[mobile]" label="手机号" label-placement="fixed" type="text" placeholder="请输入手机号码" maxlength="20" required="true" value="<?=$personal_data['mobile']?>"></ion-input>
                    </ion-item>
                <?php } ?>
                <!--地址-->
                <?php if(in_array('address', $cfg_personal_data)){ ?>
                    <ion-item>
                        <ion-textarea name="formData[address]" label="地址" label-placement="fixed" placeholder="请输入地址" maxlength="100" required="true" value="<?=$personal_data['address']?>"></ion-textarea>
                    </ion-item>
                <?php } ?>
                <!--组织-->
                <?php if(in_array('organization', $cfg_personal_data)){ ?>
                    <ion-item>
                        <ion-select name="formData[organization]" label="组织" label-placement="fixed" placeholder="请选择所在的组织部门" value="<?=$personal_data['organization']?>" cancel-text="取消" ok-text="确定">
                            <?php foreach($surveyOrganizationDatas as $key=>$text){ ?>
                                <ion-select-option value="<?=$key?>"><?=$text?></ion-select-option>
                            <?php } ?>
                        </ion-select>
                    </ion-item>
                <?php } ?>
                <!--身份证-->
                <?php if(in_array('id_card', $cfg_personal_data)){ ?>
                    <ion-item>
                        <ion-input name="formData[id_card]" label="身份证" label-placement="fixed" type="text" placeholder="请输入身份证号码" maxlength="20" pattern="^(\d{18}|\d{15}|\d{17}x)$" required="true" value="<?=$personal_data['id_card']?>"></ion-input>
                    </ion-item>
                <?php } ?>
            </ion-list>
            </form>
        </ion-card-content>
    </ion-card>
</ion-content>
<ion-footer>
  <ion-toolbar>
    <ion-buttons slot="end">
        <ion-button id="submitBtn" color="primary" fill="solid" size="large" strong="true" form="personal-data-form" type="submit">开始普查测评</ion-button>
    </ion-buttons>
  </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script src="/static/jquery.validation/1.14.0/jquery.validate.min.js" charset="UTF-8"></script>
<script src="/static/jquery.validation/1.14.0/validate-methods.js" charset="UTF-8"></script>
<script src="/static/jquery.validation/1.14.0/messages_zh.min.js" charset="UTF-8"></script>
<script type="text/javascript">
    /*
    $(function(){
        $('#submitBtn').on('click', function(e){
            $('#personal-data-form').submit();
            return false;
        });
    });*/
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>