function getfeeds2(auth, perpage, page){
	 $("#morebtn .ui-btn-text").html("正在加载...");
	 $("#morebtn").addClass('ui-disabled');
	 $.ajax({
		dataType: "jsonp",
		url: "http://www.familyday.com.cn/dapi/space.php?do=home&m_auth=" + encodeURIComponent(auth) + "&page=" + page + "&perpage=" + perpage,
	   
		success: function( data ) {
		  /* Get the movies array from the data */
		  $("#morebtn .ui-btn-text").html("更多");
		   $("#morebtn").removeClass('ui-disabled');
		  if(data.error==0){
			data = data.data;
			
			if (data.length<=0)
				
				$('.more-btn').html('没有更多动态了，赶快发布新的动态吧！');
			else{
				for (var i = 0, len = data.length; i < len; ++i) {
					data[i].message = html_entity_decode(data[i].message);
					data[i].message = html_entity_decode(data[i].message);
					data[i].dateline = date('Y-m-d',data[i].dateline);
					
					if (length(data[i].message)>80)
					{
						data[i].mc = 1;
						console.log("mc");
					}
					if (data[i].image_1=="")
					{
						//data[i].image_1 = "http://www.familyday.com.cn/wx/image/nopic.gif";
						delete data[i].image_1;
					}
				}
				$("#feedTemplate").tmpl(data ).appendTo('#feedlist');
				$('#page').val(parseInt($('#page').val())+1);
			  }
		  }else{
			alert(data.msg);
		  }
		}
	  });

}

function getfeeds( wxkey, perpage, page ) {
	 $("#morebtn .ui-btn-text").html("正在加载...");
	 $("#morebtn").addClass('ui-disabled');
	  $.ajax({
		dataType: "jsonp",
		url: "http://www.familyday.com.cn/dapi/space.php?do=wxfeed&wxkey=" + wxkey + "&page=" + page + "&perpage=" + perpage,
	   
		success: function( data ) {
		  /* Get the movies array from the data */
		  $("#morebtn .ui-btn-text").html("更多");
		   $("#morebtn").removeClass('ui-disabled');
		  if(data.error==0){
			data = data.data;
			
			if (data.length<=0)
				
				$('.more-btn').html('没有更多动态了，赶快发布新的动态吧！');
			else{
				for (var i = 0, len = data.length; i < len; ++i) {
					data[i].message = html_entity_decode(data[i].message);
					data[i].message = html_entity_decode(data[i].message);

					if (length(data[i].message)>80)
					{
						data[i].mc = 1;
						console.log("mc");
					}
					if (data[i].image_1=="")
					{
						//data[i].image_1 = "http://www.familyday.com.cn/wx/image/nopic.gif";
						delete data[i].image_1;
					}
				}
				$("#feedTemplate").tmpl(data ).appendTo('#feedlist');
				$('#page').val(parseInt($('#page').val())+1);
			  }
		  }else{
			alert(data.msg);
		  }
		}
	  });
}

$(function(){
	 $('#auth').val(localStorage.getItem('auth'));
	// getfeeds($('#wxkey').val(), $('#perpage').val(), $('#page').val());
	getfeeds2($('#auth').val(), $('#perpage').val(), $('#page').val());

	$('.header-logo').click(function(){
		window.location.href = "http://www.familyday.com.cn/wx/wx.php?do=feed&wxkey=$_GET[wxkey]";
	});

})
