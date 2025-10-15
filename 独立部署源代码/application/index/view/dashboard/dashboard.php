<div class="dashboard d-flex flex-row justify-content-around align-items-center" style="height: 100%;overflow: auto">

        <div class="info-box red-bg d-flex justify-content-center align-items-center">
            <div><i class="fa fa-users fa-3x"></i></div>
            <div>
                <div class="count"><?=$bindValues['statistic']['totalTodayUserCount']?></div>
                <div class="title">新增用户</div>
                <div class="sub-title">总用户</div>
                <div class="count"><?=$bindValues['statistic']['totalUserCount']?></div>
            </div>
        </div>

        <div class="info-box orange-bg d-flex justify-content-center align-items-center">
            <div><i class="fa fa-cubes fa-3x"></i></div>
            <div>
                <div class="count"><?=$bindValues['statistic']['totalTodayTestCount']?></div>
                <div class="title">今日测评订单</div>
                <div class="sub-title">金额</div>
                <div class="count"><?=$bindValues['statistic']['totalTodayTestMoney']?></div>
            </div>
        </div>

        <div class="info-box green-bg d-flex justify-content-center align-items-center">
            <div>
                <i class="fa fa-clock-o fa-3x"></i>
            </div>
            <div>
                <div class="count"><?=$bindValues['statistic']['totalTodayAppointExpertCount']?></div>
                <div class="title">今日预约订单</div>
                <div class="sub-title">金额</div>
                <div class="count"><?=$bindValues['statistic']['totalTodayAppointExpertMoney']?></div>
            </div>
        </div>

        <div class="info-box magenta-bg d-flex justify-content-center align-items-center">
            <div class="float-left">
                <i class="fa fa-navicon fa-3x"></i>
            </div>
            <div class="float-left">
                <div class="count"><?=$bindValues['statistic']['totalEventCount']?></div>
                <div class="title">事件总数</div>
            </div>
        </div>

</div>