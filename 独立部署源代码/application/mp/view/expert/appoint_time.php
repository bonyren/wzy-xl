<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="javascript:window.history.back();"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">选择预约时间</ion-title>
        <ion-buttons slot="end">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="home-outline"></ion-icon>首页</ion-button>
        </ion-buttons>
    </ion-toolbar>
    <ion-toolbar color="bg" class="book-steps">
        <ion-backdrop visible="true"></ion-backdrop>
        <ion-segment value="time" disabled="false" class="center-block">
            <ion-segment-button value="time">
                <ion-label>选择时间</ion-label>
            </ion-segment-button>
            <ion-segment-button value="info">
                <ion-label>填写资料</ion-label>
            </ion-segment-button>
            <ion-segment-button value="pay">
                <ion-label>预约确认</ion-label>
            </ion-segment-button>
            <ion-segment-button value="success">
                <ion-label>预约成功</ion-label>
            </ion-segment-button>
        </ion-segment>
    </ion-toolbar>
</ion-header>
<ion-content color="bg" class="book_wrapper">
    <form id="bookForm" action="">
        <!----------------------------------------------------------------------------------------------------->
        <ion-list class="select_box" id="selectStepDate">
            <ion-item color="section" lines="none">
                <ion-title>选择预约时间</ion-title>
                <ion-button id="bookTips" slot="end" color="light" fill="solid" size="small">预约说明</ion-button>
            </ion-item>
            <div id="selectDateSection" class="date" lines="none">
                <section>
                    <input type="radio" name="date_input" disabled="disabled" id="date1" value="" />
                    <label for="date1" id="data1_label"></label>
                </section>
                <section>
                    <input type="radio" name="date_input" disabled="disabled" id="date2" value="" />
                    <label for="date2" id="data2_label"></label>
                </section>
            </div>
        </ion-list>
        <!----------------------------------------------------------------------------------------------------->
        <ion-list class="select_box" id="selectStepDuration" style="display: none;">
            <ion-item color="light" lines="none">
                <ion-title>选择服务时长</ion-title>
            </ion-item>
            <ion-item class="time" lines="inset">
                <section>
                    <input type="radio" name="duration_input" id="duration15" value="15" />
                    <label for="duration15">15分钟(复询)</label>
                </section>
                <section>
                    <input type="radio" name="duration_input" id="duration45" value="45" />
                    <label for="duration45">45分钟</label>
                </section>
            </ion-item>
        </ion-list>
        <!----------------------------------------------------------------------------------------------------->
        <div class="time_section" id="selectStepTime" style="display: none;">
            <div class="time_section_box" id="time_section">
            </div>
        </div>
        <!----------------------------------------------------------------------------------------------------->
    </form>
</ion-content>
<ion-footer>
  <ion-toolbar>
    <ion-buttons slot="start">
        <ion-button id="prevBtn" color="medium" fill="solid">上一步</ion-button>
    </ion-buttons>
    <ion-buttons slot="end">
        <ion-button id="nextBtn" color="action" fill="solid">下一步</ion-button>
    </ion-buttons>
  </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script.php";
?>
<script type="text/javascript">
var appointTime = (function($){
    var expertId = <?=$expertId?>;
    var expertAppointTimes = [];
    //预约日期
    function createAppointDay() {
        if(expertAppointTimes.length == 0){
            $('#selectDateSection').html('<ion-note>无可供预约日期</ion-note>');
            return;
        }
        $('#selectDateSection').html('');
        //html
        var html = '';
        for(var i = 1; i <= expertAppointTimes.length; i++) {
            html += '<section>' +
                        '<input type="radio" name="date_input" disabled="disabled" id="date' + i + '" value="" />' +
                        '<label for="date' + i + '" id="data' + i + '_label"></label>' +
                    '</section>';
        }
        $('#selectDateSection').html(html);
        for(var i = 1; i <= expertAppointTimes.length; i++) {
            var labelText = expertAppointTimes[i-1].label;
            $('#data' + i + '_label').text(labelText);
            $("#date" + i).val(expertAppointTimes[i-1].weekDay);
            $("#date" + i).data('date', expertAppointTimes[i-1].date);
            if(expertAppointTimes[i-1].available){
                document.getElementById("date" + i).disabled = false;
            }
        }
    }
    //预约时间
    function createAppointTime(duration, weekDay) {
        $('#time_section').empty();
        for(var i = 0; i < expertAppointTimes.length; i++) {
            if(expertAppointTimes[i].weekDay == weekDay) {
                if(duration == 15){
                    if(expertAppointTimes[i].appointTimes15.length == 0){
                        $('#time_section').text('预约已满');
                    }
                    for(var j = 1; j <= expertAppointTimes[i].appointTimes15.length; j++) {
                        var $input = $('<input/>', {
                                type:'radio',
                                name:'time_input',
                                id:'time'+j,
                                value:expertAppointTimes[i].appointTimes15[j-1]});
                        var $label = $('<label/>', {for:'time'+j});
                        $label.text(expertAppointTimes[i].appointTimes15[j-1]);
                        var $section = $('<section/>');
                        $section.append($input);
                        $section.append($label);
                        //var sectionIndex = Math.ceil(j/4);
                        $('#time_section').append($section);
                    }
                }else if(duration == 45){
                    if(expertAppointTimes[i].appointTimes45.length == 0){
                        $('#time_section').text('预约已满');
                    }
                    for(var j = 1; j <= expertAppointTimes[i].appointTimes45.length; j++) {
                        var items = expertAppointTimes[i].appointTimes45[j-1].split('-');
                        if(items.length != 4){
                            continue;
                        }
                        var labelText = items[0] + '-' + items[items.length-1];
                        var $input = $('<input/>', {
                            type:'radio',
                            name:'time_input',
                            id:'time'+j,
                            value:expertAppointTimes[i].appointTimes45[j-1]});
                        var $label = $('<label/>', {for:'time'+j});
                        $label.text(labelText);
                        var $section = $('<section/>');
                        $section.append($input);
                        $section.append($label);
                        //var sectionIndex = Math.ceil(j/4);
                        $('#time_section').append($section);
                    }
                }
                break;
            }
        }
    }
    function getItemList(callback) {
        $.ajax({
            type: 'POST',
            url: '<?=url('mp/Expert/appointTime')?>',
            data: {
                expertId: expertId
            },
            dataType: 'json',
            success: function(res){
                if(res.code == 0) {
                    TOAST.error(res.msg);
                    callback && callback();
                    return;
                }
                expertAppointTimes = [];
                for(var i = 0; i < res.data.weekDayList.length; i++) {
                    var expertTime = {
                        weekDay: "",
                        available: false,
                        date: "",
                        label: "",
                        appointTimes45: [],
                        appointTimes15: []

                    };
                    expertTime.weekDay = res.data.weekDayList[i].weekDay;
                    expertTime.available = res.data.weekDayList[i].available;
                    expertTime.date = res.data.weekDayList[i].date;
                    expertTime.label = res.data.weekDayList[i].label;

                    expertTime.appointTimes45 = [].concat(res.data.weekDayList[i].available45AppointTimes);
                    expertTime.appointTimes15 = [].concat(res.data.weekDayList[i].available15AppointTimes);
                    expertAppointTimes.push(expertTime);
                }
                createAppointDay();
                callback && callback();
            },
            error:function(){
                TOAST.error('当前网络不可用，请检查网络！');
                callback && callback();
            }
        });
    }
    return {
        load:function(){
            LOADING.show('正在加载').then(()=>{
                getItemList(()=>{
                    LOADING.hide();
                });
            });
        },
        createAppointTime:function(duration, weekDay){
            createAppointTime(duration, weekDay);
        }
    }
})(jQuery);

/*
function scroll_book_form_bottom() {
    var contentHeight = $("#bookForm").get(0).scrollHeight;
    $("#bookForm").get(0).scrollTop = contentHeight;
}*/
$(function() {
    var date_val, week_day, duration_val, time_val;
    $('input[name=date_input]').removeAttr('checked');

    $(document).on('click', '#bookTips', function() {
        document.getElementById('appoint-book-tip').present();
        return false;
    }).on('change', 'input[name=date_input]', function() {
        $('input[name=duration_input]').removeAttr('checked');
        $('input[name=time_input]').removeAttr('checked');
        $('#selectStepDuration').fadeIn();
        $('#selectStepTime').hide();
        duration_val = '';
        time_val = '';
        date_val = $(this).data('date');
        week_day = $(this).val();
    }).on('change', 'input[name=duration_input]', function() {
        $('input[name=time_input]').removeAttr('checked');
        $('#selectStepTime').fadeIn()
        time_val = '';
        duration_val = $(this).val();
        appointTime.createAppointTime(duration_val, week_day);
    }).on('change', 'input[name=time_input]', function() {
        time_val = $(this).val();
        //scroll_book_form_bottom();
    }).on('click', '#nextBtn', function() {
        if (!date_val) {
            TOAST.warning('请选择预约时间');
            return;
        } else if (!duration_val) {
            TOAST.warning('请选择服务时长');
            return;
        } else if (!time_val) {
            TOAST.warning('请选择服务时段');
            return;
        }
        var params = {
            expertId: <?=$expertId?>,
            date: date_val,
            duration: duration_val,
            time: time_val,
            mode: 1//面对面咨询
        };
        window.location.href = '<?=url('mp/Expert/appointInfo')?>?' + $.param(params);
    }).on('click', '#prevBtn', function(){
        window.history.back();
    });
    appointTime.load();
});
</script>
<?php
include APP_PATH . "mp/view/common/booktip.php";
include APP_PATH . "mp/view/common/footer.php";
?>