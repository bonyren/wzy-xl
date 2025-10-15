<?php
use app\Defs;
?>
<table class="table table-bordered table-sm">
    <tr>
        <td colspan="4" class="table-tr-caption">基本信息</td>
    </tr>
    <tr>
		<!--
        <td class="table-active" style="width: 15%;">客户编号</td>
        <td><?=$infos['id']?></td>
		-->
        <td class="table-active"  style="width: 15%;">openid</td>
        <td colspan="3"><?=$infos['openid']?></td>
    </tr>
    <tr>
        <td class="table-active">昵称</td>
        <td><?=$infos['nickname']?></td>
        <td class="table-active" style="width: 15%;">头像</td>
        <td><img src="<?=$infos['headimg_url']?>" class="img-thumbnail" style="width: 80px;"/></td>
    </tr>
    <tr>
        <td class="table-active">性别</td>
        <td>
            <?php
            if($infos['sex']){
                echo $infos['sex']==1?'男':'女'; 
            }
            ?>
        </td>
        <td class="table-active">区域</td>
        <td><?=$infos['country']?><?=$infos['province']?><?=$infos['city']?></td>
    </tr>
    <tr>
        <td class="table-active">来源</td>
        <td colspan="3">
            <?=Defs::CHANNELS_HTML[$infos['channel_id']]??''?>
        </td>
    </tr>
    <!--------------------------------------------------------------------------------------------------------------->
    <tr>
        <td colspan="4" class="table-tr-caption">身份信息</td>
    </tr>
    <tr>
        <td class="table-active">真实姓名</td>
        <td><?=$infos['real_name']?></td>
        <td class="table-active">身份证</td>
        <td><?=$infos['idcard']?></td>
    </tr>
    <tr>
        <td class="table-active">手机号码</td>
        <td><?=$infos['cellphone']?></td>
        <td class="table-active">住址</td>
        <td><?=$infos['address']?></td>
    </tr>
    <tr>
        <td class="table-active">年龄</td>
        <td><?=$infos['age']?></td>
        <td class="table-active">职业</td>
        <td><?=$infos['profession']?></td>
    </tr>
    <tr>
        <td class="table-active">组织</td>
        <td colspan="3"><?=$infos['organization']?></td>
    </tr>
    <tr>
        <td class="table-active">工作单位</td>
        <td><?=$infos['company']?></td>
        <td class="table-active">工作岗位</td>
        <td><?=$infos['job']?></td>
    </tr>
    <tr>
        <td class="table-active">工作年限</td>
        <td><?=$infos['work_age']?></td>
        <td class="table-active">疾病史</td>
        <td><?=$infos['disease']?></td>
    </tr>
    <tr>
        <td class="table-active">注册时间</td>
        <td><?=$infos['register_time']?></td>
        <td class="table-active">最近登录时间</td>
        <td><?=$infos['latest_login_time']?></td>
    </tr>
    <tr>
        <td class="table-active">备注</td>
        <td colspan="3"><?=nl2br($infos['remark'])?></td>
    </tr>
    <!--------------------------------------------------------------------------------------------------------------->
    <!--
    <tr>
        <td colspan="4" class="table-tr-caption">业务统计</td>
    </tr>
    <tr>
        <td class="table-active">最近测验时间</td>
        <td colspan="3"><?=$infos['latest_test_time']?></td>
    </tr>
    <tr>
        <td class="table-active">测评总次数</td>
        <td><?=$infos['total_test_quantity']?></td>
        <td class="table-active">测评总金额</td>
        <td><?=$infos['total_test_amount']?></td>
    </tr>
    <tr>
        <td class="table-active">最近预约医生时间</td>
        <td colspan="3"><?=$infos['latest_appoint_time']?></td>
    </tr>
    <tr>
        <td class="table-active">预约医生总次数</td>
        <td><?=$infos['total_appoint_quantity']?></td>
        <td class="table-active">预约医生总金额</td>
        <td><?=$infos['total_appoint_amount']?></td>
    </tr>
    -->
</table>