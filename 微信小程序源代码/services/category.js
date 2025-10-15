function fetchCategory(categoryId=0){
	return new Promise((resolve, reject) => {
		wx.request({
			url: getApp().globalData.api_host + '/mp/uni_app/categoryDetail.html',
			data:{
				session_key: getApp().globalData.session_key,
				id:categoryId
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
export const categoryService = {
	fetchCategory
};