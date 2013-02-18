function getUser( fuid, auth){
	var r;
	$.ajax({
		dataType: "jsonp",
		url: "http://www.familyday.com.cn/dapi/space.php?do=friend&fuid=" + fuid + "&m_auth=" + encodeURIComponent(auth),
	   async: false,
		success: function( data ) {
		  /* Get the movies array from the data */

		  if(data.error==0){
				r =  data.data;

		  }else{
			alert(data.msg);
		  }
		}
	  });
	return r;
}

function getDetail(type, id, uid, auth){
	if (type=="blogid"){
		$.ajax({
			dataType: "jsonp",
			url: "http://www.familyday.com.cn/dapi/space.php?do=blog&id=" + id + "&uid=" + uid + "&m_auth=" + encodeURIComponent(auth),
		   
			success: function( data ) {
			  /* Get the movies array from the data */

			  if(data.error==0){
					data=data.data;
					data.message = html_entity_decode(data.message);
					data.dateline = date('Y-m-d H:i',data.dateline);
					data.user = getUser(data.uid, auth);
					$("#detailTemplate").tmpl(data).appendTo('#detail-panel');

			  }else{
				alert(data.msg);
			  }
			}
		  });
	}else if(type=="photoid"){
		$.ajax({
			dataType: "jsonp",
			url: "http://www.familyday.com.cn/dapi/space.php?do=photo&id=" + id + "&uid=" + uid + "&m_auth=" + encodeURIComponent(auth),
		   
			success: function( data ) {
			  /* Get the movies array from the data */

			  if(data.error==0){
					data=data.data;
					data.message = html_entity_decode(data.message);
					data.dateline = date('Y-m-d H:i',data.dateline);
					data.user = getUser(data.uid, auth);
					$("#detailTemplate").tmpl(data ).appendTo('#detail-panel');

			  }else{
				alert(data.msg);
			  }
			}
		  });

	}else if(type=="videoid"){
		$.ajax({
			dataType: "jsonp",
			url: "http://www.familyday.com.cn/dapi/space.php?do=video&id=" + id + "&uid=" + uid + "&m_auth=" + encodeURIComponent(auth),
		   
			success: function( data ) {
			  /* Get the movies array from the data */

			  if(data.error==0){
					data=data.data;
					data.message = html_entity_decode(data.message);
					data.dateline = date('Y-m-d H:i',data.dateline);
					data.user = getUser(data.uid, auth);
					$("#detailTemplate").tmpl(data ).appendTo('#detail-panel');

			  }else{
				alert(data.msg);
			  }
			}
		  });

	}
}

function getComment(idtype, id, page, perpage, auth){
	$.ajax({
			dataType: "jsonp",
			url: "http://www.familyday.com.cn/dapi/space.php?do=comment&id=" + id + "&idtype=" + idtype + "&m_auth=" + encodeURIComponent(auth) + "&page=" + page + "&perpage=" + perpage,
		   
			success: function( data ) {
			  /* Get the movies array from the data */

			  if(data.error==0){
					data=data.data;
					if (data.length<=0)
						
						$('.more-btn').html('没有更多评论了，赶快发布新的评论吧！');
					else{
						for (var i = 0, len = data.length; i < len; ++i) {
							data[i].message = html_entity_decode(data[i].message);
							data[i].message = html_entity_decode(data[i].message);
							data[i].dateline = date('Y-m-d H:i',data[i].dateline);
						}
						$("#commentTemplate").tmpl(data).appendTo('#comment-panel');
						$('#page').val(parseInt($('#page').val())+1);
					}
			  }else{
				alert(data.msg);
			  }
			}
		  });
}

function cpComment(idtype, id, message, auth){
	var pattern = /^[\s]{0,}$/g;
	if (!pattern.test(message)){
		$.ajax({
				dataType: "jsonp",
				url: "http://www.familyday.com.cn/dapi/do.php?ac=comment&id=" + id + "&idtype=" + idtype + "&m_auth=" + encodeURIComponent(auth) + "&message=" + message + "&come=wx",
			   
				success: function( data ) {
				  /* Get the movies array from the data */

				  if(data.error==0){
					  $('#comment-panel').html("");
					  $('#page').val(1);
					  getComment($('#idtype').val(), $('#id').val(), $('#page').val(), $('#perpage').val(), $('#auth').val());		
				  }else{
					alert(data.msg);
				  }
				}
		});
	}else{
		alert("至少写一点东西！");
	}
}

$(function(){

	$('#auth').val(localStorage.getItem('auth'));

	if ($('#idtype').val()=="reblogid")
	{
		$('#idtype').val("blogid");
	}else if($('#idtype').val()=="rephotoid")
	{
		$('#idtype').val("photoid");
	}else if($('#idtype').val()=="revideoid")
	{
		$('#idtype').val("videoid");
	}
	getDetail($('#idtype').val(), $('#id').val(), $('#uid').val(), $('#auth').val());
	getComment($('#idtype').val(), $('#id').val(), $('#page').val(), $('#perpage').val(), $('#auth').val());
	
})