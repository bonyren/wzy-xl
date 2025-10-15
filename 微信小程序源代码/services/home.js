function homeConfig(){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/homeConfig.html',
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
				//网络连接错误
				reject(res.errMsg);
			}
		});
	});
}
function subjectsPopular(){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/subjectsPopular.html',
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
				//网络连接错误
				reject(res.errMsg);
			}
		});
	});
}
function subjectsFeatured(){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/subjectsFeatured.html',
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
				//网络连接错误
				reject(res.errMsg);
			}
		});
	});
}
export const homeService = {
	homeConfig,
	subjectsPopular,
	subjectsFeatured
};