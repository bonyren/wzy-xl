// app.js
App({
	onLaunch() {
		let that = this;
		wx.getStorage({
			key: 'session_key',
			success: function (res) {
				that.globalData['session_key'] = res.data || '';
				let headimgUrl = wx.getStorageSync('headimg_url');
				if(headimgUrl){
					that.globalData['headimg_url'] = headimgUrl;
				}
			}
		});
	},
	globalData: {
		session_key: '',
		api_host: '',
		headimg_url: '',
		nick_name: '',
		default_category_id: 0,
    home_search_kw: '',
    copyright_text: 'Copyright © 2022-2024 为之易心理测量.'
	},
	logout(){
		wx.removeStorage({
			key: 'session_key',
		});
		wx.removeStorageSync('headimg_url');
	}
});
