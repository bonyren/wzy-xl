/* globals Page */
import {
  common
} from '../../services/common.js';
import {
  subjectService
} from '../../services/subject.js';
import {
  testService
} from '../../services/test.js';
var testModule = (function () {
  function setQuestion(that, curIndex, curItem) {
    var curItemOptions = [];
    if (curItem.type == 1) {
      //单选
      var i = 1;
      while (i <= 12) {
        if (curItem['option_' + i] || curItem['image_' + i]) {
          curItemOptions.push({
            text: curItem['option_' + i],
            image: curItem['image_' + i],
            value: String(i),
            checked: curItem['value'] == i ? true : false
          });
        } else {
          break;
        }
        i++;
      }
    } else if (curItem.type == 2) {
      //多选
      var value = curItem['value'] || []; //数组
      if (!Array.isArray(value)) {
        value = [];
      }
      var i = 1;
      while (i <= 12) {
        if (curItem['option_' + i] || curItem['image_' + i]) {
          curItemOptions.push({
            text: curItem['option_' + i],
            image: curItem['image_' + i],
            value: String(i),
            checked: (value.indexOf(String(i)) != -1) ? true : false
          });
        } else {
          break;
        }
        i++;
			}
			curItem['value'] = curItem['value'] || [];
    } else if (curItem.type == 3) {
      //填写
      curItem['value'] = curItem['value'] || '';
    }
    if (curItem.type == 1) {
      //单选，重置value为空，“上一题”回退后，点击相同的选项，则onTestItemOptionChange可以被触发
      curItem.value = '';
    }
    that.setData({
      test: {
        curIndex: curIndex,
        curItem: curItem,
        curItemOptions: curItemOptions,
        curPercent: Math.floor(curIndex / that.data.order.total_items * 100)
      },
      optionColSpan: (curItemOptions.length == 1 ? 100 : Math.floor(100 / that.data.subject.test_option_col_layout)) - 10
    });
  }

  function nextQuestion(that) {
    if (!that.data.test.curItem['value'] && that.data.subject.test_allow_answer_empty == 0) {
      wx.showToast({
        title: '请答题'
      });
      return;
    }
    if (that.data.test.curItem.tag == 'age') {
      //检查年龄值是否非法
      /*
			if(!/^\d+$/.test(that.data.test.curItem['value'])){
				wx.showToast({
					title: '年龄格式非法'
				});
				return;
			}*/
      if (isNaN(that.data.test.curItem['value'])) {
        wx.showToast({
          title: '年龄输入值非法'
        });
        return;
      }
      if (that.data.test.curItem['value'] <= 0 || that.data.test.curItem['value'] > 200) {
        wx.showToast({
          title: '年龄输入值非法'
        });
        return;
      }
    }
    //多选
    if (Array.isArray(that.data.test.curItem['value']) && that.data.test.curItem['value'].length == 0 && that.data.subject.test_allow_answer_empty == 0) {
      wx.showToast({
        title: '请答题'
      });
      return;
    }
    wx.showLoading({
      title: '加载中'
    });
    testService.answer(that.data.order.order_no,
      that.data.test.curItem.id,
      that.data.test.curItem.type,
      that.data.test.curItem['value']).then(data => {
      if (data == -1) {
        //测评项目版本发生了变更
        wx.showModal({
          title: '提示',
          content: '该测评包含的项目发生了变更，需要重新开始.',
          showCancel: false,
          complete() {
            wx.showLoading({
              title: '加载中'
            });
            subjectService.regenOrder(that.data.orderNo).then(data => {
              wx.redirectTo({
                url: `/pages/test/test?subjectId=${that.data.subjectId}&orderNo=${that.data.orderNo}`
              });
            }).catch(err => {
              if (err == 401) {
                common.goToLogin(that.data.currentPageUrl);
                return;
              }
              wx.showModal({
                title: '错误提示',
                content: JSON.stringify(err),
                showCancel: false
              });
            }).finally(() => {
              wx.hideLoading();
            });
          }
        });
        return;
      }
      if (data.finished ||
        that.data.test.curItem.next_id == 0) {
        //finished, show report
        wx.redirectTo({
          url: `/pages/report/report?orderNo=${that.data.order.order_no}`
        });
        return;
      }
      var curItem = that.data.subjectItems[that.data.test.curItem.next_id];
      setQuestion(that, ++that.data.test.curIndex, curItem);
    }).catch(err => {
      wx.showToast({
        title: JSON.stringify(err),
        icon: 'error'
      });
    }).finally(() => {
      wx.hideLoading();
    });
  }

  function prevQuestion(that) {
    if (that.data.test.curItem.prev_id == 0) {
      return;
    }
    var curItem = that.data.subjectItems[that.data.test.curItem.prev_id];
    setQuestion(that, --that.data.test.curIndex, curItem);
  }
  return {
    setQuestion: setQuestion,
    nextQuestion: nextQuestion,
    prevQuestion: prevQuestion
  };
})();
Page({
  data: {
    footerText: getApp().globalData.copyright_text,
    currentPageUrl: '',
    uiDataReady: false,
    subjectId: 0,
    orderNo: '',
    order: {},
    subject: {},
    subjectItems: [],
    test: {
      curIndex: 1,
      curItem: {},
      curItemOptions: [],
      curPercent: 0
    },
    optionColSpan: 90
  },
  onLoad(options) {
    var that = this;
    this.data.currentPageUrl = common.genCurrentPageUrl('/pages/test/test', options);
    that.setData({
      subjectId: options.subjectId,
      orderNo: options.orderNo || '' //继续测评
    });
    wx.showLoading({
      title: '加载中'
    });
    subjectService.createOrder(this.data.subjectId, this.data.orderNo).then(order_no => {
      that.setData({
        orderNo: order_no
      });
      return testService.test(that.data.orderNo);
    }).then(data => {
      that.setData({
        order: data.order,
        subject: data.subject,
        subjectItems: data.subjectItems,
        optionColSpan: (Math.floor(100 / data.subject.test_option_col_layout)) - 10
      });
      let curItem = null;
      let testItems = that.data.order.test_items;
      //以item id为key
      let index = 0;
      for (let key in that.data.subjectItems) {
        if (index == testItems) {
          curItem = that.data.subjectItems[key];
          break;
        }
        index++;
      }
      if (!curItem) {
        //impossible

      }
      testModule.setQuestion(that, ++testItems, curItem);
      that.setData({
        uiDataReady: true
      });
    }).catch(err => {
      if (err == 401) {
        common.goToLogin(that.data.currentPageUrl);
        return;
      }
      wx.showModal({
        title: '错误提示',
        content: JSON.stringify(err),
        showCancel: false,
        complete() {
          wx.navigateBack();
        }
      });
    }).finally(() => {
      wx.hideLoading();
    });
  },
  //单选
  onTestItemOptionChange({
    detail
  }) {
    this.data.test.curItemOptions[detail.value - 1].checked = true;
    this.data.test.curItem['value'] = detail.value;
    this.setData({
      test: this.data.test
    });
    testModule.nextQuestion(this);
  },
  //多选
  onTestItemOptionsChange({
    detail
  }) {
    //detail = {value:[选中的 checkbox 的 value 的数组]}
    let that = this;
    that.data.test.curItemOptions.forEach(function (ele) {
      ele.checked = false;
    });
    detail.value.forEach(function (ele) {
      that.data.test.curItemOptions[ele - 1].checked = true;
    });
    this.data.test.curItem['value'] = [].concat(detail.value);
    this.setData({
      test: this.data.test
    });
  },
  onTextAreaChange({
    detail
  }) {
    this.data.test.curItem['value'] = detail.value;
  },
  nextQuestion() {
    testModule.nextQuestion(this);
  },
  prevQuestion() {
    testModule.prevQuestion(this);
  },
  onReady() {
    // 监听页面初次渲染完成的生命周期函数
  },
  onShow() {
    // 监听页面显示的生命周期函数
  },
  onHide() {
    // 监听页面隐藏的生命周期函数
  },
  onUnload() {
    // 监听页面卸载的生命周期函数
  },
  onPullDownRefresh() {
    // 监听用户下拉动作
  },
  onReachBottom() {
    // 页面上拉触底事件的处理函数
  }
});