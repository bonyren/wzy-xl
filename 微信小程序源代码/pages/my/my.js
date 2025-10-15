/* globals Page */
const app = getApp()
import {common} from '../../services/common.js';
import {myService} from '../../services/my.js';
function requestData(that){
    wx.showLoading({
        title: '加载中'
    });
    myService.my().then(function(data){
        that.setData({
			headimgUrl: app.globalData.headimg_url,
			nickName: '为之易心理测量欢迎您！',
            orders:[[].concat(data.ordersInProgress), [].concat(data.ordersFinished)],
            currentOrders: [].concat(data.ordersInProgress),
            currentTab: 0,
            uiDataReady: true
        });
    }).catch(err=>{
        if(err == 401){
            common.goToLogin();
            return;
        }
        wx.showModal({
            title: '错误提示',
            content: JSON.stringify(err),
            showCancel: false,
            complete(){
            }
        });
    }).finally(()=>{
        wx.hideLoading();
    });
}
Page({
    data: {
		uiDataReady:false,
		headimgUrl: '',
		nickName: '',
        tabs:['未完成', '已完成'],
        currentTab: 0,
        currentOrders:[],
        orders:[[], []],
    },
    onLoad() {
    },
    onReady() {
        // 监听页面初次渲染完成的生命周期函数
    },
    onShow() {
        requestData(this);
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
	},
	bindchooseavatar(e){
		var that = this;
		wx.showLoading({
			title: '加载中'
		});
		myService.uploadAvatar(e.detail.avatarUrl).then(function(data){
			app.globalData.headimg_url = data;
			wx.setStorageSync('headimg_url', data);
			that.setData({
				headimgUrl: data
			});
		}).catch(err=>{
			if(err == 401){
				common.goToLogin();
				return;
			}
			wx.showModal({
				title: '错误提示',
				content: JSON.stringify(err),
				showCancel: false,
				complete(){
				}
			});
		}).finally(()=>{
			wx.hideLoading();
		});
	},
	onExit(e){
		wx.showLoading({
			title: '加载中'
		});
		myService.logout().then(data=>{
		}).finally(()=>{
			app.globalData.session_key = '';
			app.globalData.headimg_url = '';
			app.logout();
			wx.hideLoading();
			wx.switchTab({
				url: '/pages/home/home',
			});
		});
	},
    onClickTab(e){
      if (this.currentTab != e.detail.value){
          this.setData({
              currentTab:e.detail.value,
              currentOrders: this.data.orders[e.detail.value]
          });
      }
    },
    onTabChange(e){
      if (this.currentTab != e.detail.value){
          this.setData({
              currentTab:e.detail.value,
              currentOrders: this.data.orders[e.detail.value]
          });
      }
    },
    onContinueTest(e){
        let subjectId = e.currentTarget.dataset.subjectId;
        let orderNo = e.currentTarget.dataset.orderNo;
        wx.navigateTo({
            url: `/pages/test/test?subjectId=${subjectId}&orderNo=${orderNo}`
        });
    },
    onTestReport(e){
        let orderNo = e.currentTarget.dataset.orderNo;
        wx.navigateTo({
            url: `/pages/report/report?orderNo=${orderNo}`
        });
    }
});
