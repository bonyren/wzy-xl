<?php
use app\Defs;
?>
<?php
include APP_PATH . "mp" . DS . "view" . DS . "common" . DS . "header.php";
?>
<ion-app>
<ion-header>
    <ion-toolbar color="bg">
        <ion-buttons slot="start" id="nav-top-buttons">
            <ion-button size="small" href="<?=$_home_url?>"><ion-icon slot="start" name="chevron-back-outline"></ion-icon>后退</ion-button>
        </ion-buttons>
        <ion-title color="action">量表测评答题</ion-title>
    </ion-toolbar>
</ion-header>
<ion-content color="bg">
<ion-card class="test_box" color="light">
    <!--
    <ion-card-header class="test_box_title ion-no-padding">
        <?=$subject['name']?>
    </ion-card-header>
    -->
    <ion-card-content class="test_box_head ion-no-padding">
        <div class="test_box_head_label"><?=$subject['name']?></div>
        <div class="test_box_head_progress">
            <div class="test_box_head_progress_label">
                <ion-text id="item_no" class="current"><?=$order['test_items']+1?></ion-text>
                <span class="total"><span>/&nbsp;</span><?=$order['total_items']?></span>
            </div>
            <div class="test_box_head_progress_bar">
                <ion-progress-bar id="progress-cur-bar" type="determinate" color="success" value="<?=($order['test_items']+1)/$order['total_items']?>">
                </ion-progress-bar>
            </div>
        </div>
    </ion-card-content>
    <ion-card-content class="test_box_content">
        <h1 id="item_title" class="title"></h1>
        <div id="item_type" class="type"></div>
        <ion-grid id="item_options"></ion-grid>
    </ion-card-content>
</ion-card>
</ion-content>
<ion-footer>
    <ion-toolbar>
    <?php if($subject['test_allow_back']){ ?>
        <ion-buttons slot="start" id="prevBtns">
            <ion-button id="prevBtn" color="medium" fill="solid">上一题</ion-button>
        </ion-buttons>
    <?php } ?>
        <ion-buttons slot="end" id="nextBtns" style="display: none;">
            <ion-button id="nextBtn" color="action" fill="solid" size="large" strong="true">下一题</ion-button>
        </ion-buttons>
    </ion-toolbar>
</ion-footer>
</ion-app>
<?php
include APP_PATH . "mp/view/common/script_no_weixin.php";
?>
<script type="text/javascript">
    var subjectItems = <?=$subjectItems?>;
    var curItemIndex = <?=$order['test_items']+1?>;
    var totalItems = <?=$order['total_items']?>;
    var lastItemId = <?=$order['curr_item']?>;
    var nowItem = null;

    function initQuestions() {
        var itemId;
        if (lastItemId) {
            //继续测试
            itemId = subjectItems['id_' + lastItemId].next_id;
            itemId = 'id_' + itemId;
        } else {
            //第一次测试
            $.each(subjectItems,function(i, v) {
                itemId = i;
                return false;
            });
        }
        nowItem = subjectItems[itemId];
    }
    function setQuestion(itemId) {
        if (itemId) {
            nowItem = subjectItems['id_' + itemId];
        }
        //序号
        $('#item_no').text(curItemIndex);
        //进度
        document.getElementById('progress-cur-bar').value =  curItemIndex/totalItems;
        //题目
        let question = nowItem.item;
        if(nowItem.image){
            question += `<br /><img src="${nowItem.image}" style="max-width:80%;" />`;
        }
        if(!nowItem.item_2){
            $('#item_title').html(question);
        }else{
            $('#item_title').html(`<ion-row><ion-col>${question}</ion-col><ion-col>${nowItem.item_2}</ion-col></ion-row>`);
        }
        //类型
        $('#item_type').text(<?=json_encode(Defs::QUESTION_TYPES)?>[nowItem.type]);
        let _html = '';
        if (nowItem.type == <?=Defs::QUESTION_TEXT?>){
            //填写
            if(nowItem.tag == '<?=Defs::SUBJECT_ITEM_TAG_AGE?>'){
                //年龄
                _html += `<div class="input_box"><label><input name="test_option_input" type="number" value="${nowItem.value}" placeholder="请输入数字, 例如：10" min="1" max="100" maxlength="6" required="true" style="width:100%;"/></label></div>`;
            }else{
                _html += '<div class="input_box"><label>';
                if(nowItem.value){
                    _html += '<textarea name="test_option_input" cols="30" rows="5">' + nowItem.value + '</textarea></label></div>';
                }else {
                    _html += '<textarea name="test_option_input" cols="30" rows="5"></textarea></label></div>';
                }
            }
        }else {
            //单选或者多选
            nowItem.options = [];
            let cols = <?=$subject['test_option_col_layout']?>;
            for (var i = 1; i <= 12; i++) {
                let field = 'option_' + i;
                let fieldImage = 'image_' + i;
                if (nowItem[field] || nowItem[fieldImage]) {
                    nowItem.options.push({id: i, opt: nowItem[field]});
                    var lastSelected = false;
                    if(nowItem.value){
                        //上次的选择
                        if (nowItem.type == <?=Defs::QUESTION_RADIO?>){
                            //单选
                            lastSelected = (i==nowItem.value);
                        }else if(nowItem.type == <?=Defs::QUESTION_CHECKBOX?> && $.isArray(nowItem.value)){
                            //多选, i转换为字符串
                            if($.inArray(String(i), nowItem.value) != -1){
                                lastSelected = true;
                            }
                        }
                    }
                    if((i-1)%cols == 0){
                        if(_html == ''){
                            _html += '<ion-row>';
                        }else{
                            _html += '</ion-row><ion-row>';
                        }
                    }
                    if(lastSelected){
                        _html += '<ion-col><div class="input_box active"><label><span>' + nowItem[field];
                        if(nowItem[fieldImage]){
                            _html += `<img src="${nowItem[fieldImage]}" style="max-width:80%;" />`;
                        }
                        _html += '</span><input type="checkbox" name="test_option_input" value="' + i + '" checked>';
                        if(cols < 5){
                            _html += '<ion-icon name="radio-button-off-outline" color="primary"></ion-icon><ion-icon name="checkmark-circle-outline" color="success"></ion-icon>';
                        }
                        _html += '</label></div></ion-col>';
                    }else{
                        _html += '<ion-col><div class="input_box"><label><span>' + nowItem[field];
                        if(nowItem[fieldImage]){
                            _html += `<img src="${nowItem[fieldImage]}" style="max-width:80%;" />`;
                        }
                        _html += '</span><input type="checkbox" name="test_option_input" value="' + i + '">';
                        if(cols < 5){
                            _html += '<ion-icon name="radio-button-off-outline" color="primary"></ion-icon><ion-icon name="checkmark-circle-outline" color="success"></ion-icon>';
                        }
                        _html += '</label></div></ion-col>';
                    }
                }else{
                    break;
                }
            }
            if(_html){
                _html += '</ion-row>';
            }
        }
        //答案
        $('#item_options').html(_html);
        if (nowItem.type == <?=Defs::QUESTION_RADIO?>) {
            //单选
            $('#nextBtns').hide();
        } else {
            $('#nextBtns').show();
        }
        <?php if($subject['test_allow_back']){ ?>
            if (nowItem.prev_id){
                $('#prevBtns').show();
            }else{
                $('#prevBtns').hide();
            }
        <?php } ?>
    }
    /**
     *
     * @param value 单选:option id, 多选:option id组成的数组, 填写: 文本
     */
    function submitItem(value, cb) {
        $.post('<?=$answer_url?>',{
            order_no: '<?=$order['order_no']?>',
            item_id: nowItem.id,
            item_type: nowItem.type,//单选，多选，填写
            item_option: value
        },function(res){
            if(!res.code) {
                if(res.data == -1){
                    cb && cb();
                    //测评项目版本变更, 刷新订单，从头开始测评
                    ALERT.tip("该测评包含的项目发生了更新，需要重新开始.", function(){
                        LOADING.show('处理中').then(()=>{
                            $.post('<?=$regen_order_url?>',{order_no:'<?=$order['order_no']?>'}, function(res){
                                LOADING.hide();
                                if (!res.code) {
                                    TOAST.error(res.msg);
                                } else {
                                    var test_url = '<?=$test_url?>';
                                    var href = GLOBAL.func.addUrlParam(test_url, 'order_no', '<?=$order['order_no']?>');
                                    window.location.replace(href);
                                }
                            },'json');
                        });
                    });
                    return;
                }else{
                    TOAST.error(res.msg).then(()=>{
                        cb && cb();
                    });
                }
                return;
            }
            //保存上次选中的值, 为后退保存状态
            nowItem.value = value;
            if(nowItem.next_id == '0') {
                //测试完毕, 查看报告, 不保存到window.history
                TOAST.success("本量表测评完成，正在跳转，请稍候...").then(()=>{
                    window.location.replace(res.data);
                });
            } else {
                curItemIndex += 1;
                setQuestion(nowItem.next_id);
                cb && cb();
            }
        },'json');
    }
    /******************************************************************************************************************/
    var submitting = false;
    $(function(){
        initQuestions();
        setQuestion();
        //下一题, 单选时隐藏
        $(document).on('click', '#nextBtn', function() {
            if(LOADING.isLoading()){
                return;
            }
            //单选不会触发
            if (nowItem.type == <?=Defs::QUESTION_CHECKBOX?>) {
                //多选
                var checked = $("*[name='test_option_input']:checked");
                <?php if(!$subject['test_allow_answer_empty']){ ?>
                    if (!checked.length) {
                        TOAST.warning('请选择至少一个选项');
                        return;
                    }
                <?php } ?>
                var values = [];
                $.each(checked, function (i, v) {
                    values.push(v.value);
                });
                LOADING.show('').then(()=>{
                    submitItem(values, function(){
                        LOADING.hide();
                    });
                });
            }else if (nowItem.type == <?=Defs::QUESTION_TEXT?>){
                //填写
                var val = $("*[name='test_option_input']").val();
                if(val.length > 100){
                    TOAST.warning('不允许超过最大长度100字符');
                    return;
                }
                if(nowItem.tag == 'age'){
                    if(!$.isNumeric(val)){
                        TOAST.warning('年龄格式非法');
                        return;
                    }
                    if(val <= 0 || val > 200){
                        TOAST.warning('年龄输入值非法');
                        return;
                    }
                }
                <?php if(!$subject['test_allow_answer_empty']){ ?>
                    if($.trim(val).length == 0){
                        TOAST.warning('填写不允许为空');
                        return;
                    }
                <?php } ?>
                LOADING.show('').then(()=>{
                    submitItem(val, function(){
                        LOADING.hide();
                    });
                });
            }
            return false;
        });
        //上一题
        <?php if($subject['test_allow_back']){ ?>
            $('#prevBtn').click(function() {
                if (nowItem.prev_id) {
                    curItemIndex -= 1;
                    setQuestion(nowItem.prev_id);
                }
                return false;
            });
        <?php } ?>
        //checkbox选择，取消选择都会触发change事件，所以单选在“上一题”后，选择同样的答案，依然可以自动提交到"下一题"
        $(document).on('change', '.input_box input', function() {
            var that = this;
            if (nowItem.type == <?=Defs::QUESTION_RADIO?>) {
                if(submitting) return;
                submitting = true;
                //单选, 自动下一题
                $(this).closest('.input_box').addClass('active').siblings().removeClass('active');
                //停留一会，便于测试者注意所选的答案
                //LOADING.show('').then(()=>{
                    //setTimeout(function(){
                        submitItem(that.value, function(){
                //            LOADING.hide().then(()=>{
                                submitting = false;
                //            })
                        });
                    //}, 500);
                //});
            } else {
                if($(this).is(':checked')){
                    $(this).closest('.input_box').addClass('active');
                }else{
                    $(this).closest('.input_box').removeClass('active');
                }
            }
        });
    });
</script>
<?php
include APP_PATH . "mp/view/common/footer.php";
?>