/* globals Page */
import {myService} from '../../services/my.js';
function _getUserProfile(){
	return new Promise((resolve, reject)=>{
		wx.getUserProfile({
			desc: '用于完善会员资料',
			success: (res) => {
				let headimgUrl = res.userInfo.avatarUrl;
				let nickName = res.userInfo.nickName;
				resolve({
					headimg_url: headimgUrl, 
					nick_name: nickName
				});
			},
			fail:function(e){
				reject('获取用户信息失败');
			}
		});
	});
}
Page({
    data: {
        goBackPath: ''
    },
    onLogin(){
        var that = this;
        wx.showLoading({
            title: '加载中'
		});
		_getUserProfile().then(data=>{
			getApp().globalData.headimg_url = data.headimg_url;
			getApp().globalData.nick_name = data.nick_name;
			return myService.login();
		}).then(function(data){
            //session_key
			var sessionKey = data.session_key;
			var headimgUrl = data.headimg_url;
			getApp().globalData['session_key'] = sessionKey;
			getApp().globalData['headimg_url'] = headimgUrl;
            wx.setStorage({
                key: 'session_key',
                data: sessionKey,
                success(){
					wx.setStorageSync('headimg_url', headimgUrl);
                    if(that.data.goBackPath){
                        if(that.data.goBackPath == '/pages/home/home'
                        || that.data.goBackPath == '/pages/index/index'
                        || that.data.goBackPath == '/pages/my/my'){
                            wx.switchTab({
                              url: that.data.goBackPath,
                            })
                            return;
                        }
                        wx.redirectTo({
                            url: that.data.goBackPath
                        });
                        return;
                    }
                    wx.navigateBack();
                }
			});
        }).catch(err=>{
            wx.showModal({
                title: '错误提示',
                content: JSON.stringify(err),
                showCancel: false
            });
        }).finally(()=>{
            wx.hideLoading();
        });
    },
    onHome(){
        wx.switchTab({
          url: '/pages/home/home'
        });
    },
    onLoad(options) {
        this.data.goBackPath = decodeURIComponent(options.goBackPath || '');
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
