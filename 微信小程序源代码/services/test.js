function test(orderNo){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/test.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo
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
function answer(orderNo, item_id, item_type, item_option){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/answer.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo,
				item_id:item_id,
				item_type:item_type,
				item_option:item_option
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
function generateReport(orderNo){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/generateReport.html',
			data:{
				session_key: getApp().globalData.session_key,
				order_no:orderNo,
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
export const testService = {
	test,
	answer,
	generateReport
};