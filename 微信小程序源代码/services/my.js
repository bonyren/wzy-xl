function my(){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/my.html',
			data:{
				session_key: getApp().globalData.session_key
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function _loginCode(){
	return new Promise((resolve, reject)=>{
		wx.login({
			success: data => {
				resolve(data.code);
			},
			fail: err => {
				reject('获取登录码失败');
			}
		});
	});
}
function _loginUni(channel, code=''){
	return new Promise((resolve, reject)=>{
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/login.html',
			data:{
				channel: channel,
				code: code
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function login(){
	return new Promise((resolve, reject) => {
		_loginCode().then(data=>{
			return _loginUni('WEIXIN', data);
		}).then(data=>{
			resolve(data);
		}).catch(err=>{
			reject(err);
		});
	});
}

function logout(){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/logout.html',
			data:{
				session_key: getApp().globalData.session_key
			},
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
function uploadAvatar(filePath){
	return new Promise((resolve, reject) => {
		wx.uploadFile({
			filePath: filePath,
			formData:{
				session_key: getApp().globalData.session_key
			},
			name: 'avatar',
			url: getApp().globalData.api_host + '/mp/uni_app/uploadAvatar.html',
			success:function(res){
				if(res.statusCode != 200){
					reject(res.statusCode);
				}
				res.data = JSON.parse(res.data);
				if(res.data.code == 1){
					resolve(res.data.data);
				}else{
					reject(res.data.msg);
				}
			},
			fail:function(res){
				reject(res.errMsg);
			}
		});
	});
}
export const myService = {
	my,
	login,
	logout,
	uploadAvatar
};