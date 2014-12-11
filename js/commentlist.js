function ajacpload() {
	$('#pagenavi a').click(function () {
		var wpurl = $(this).attr("href").split(/(\?|&)action=AjaxCommentsPage.*$/)[0];
		var commentPage = 1;
		if (/comment-page-/i.test(wpurl)) {
			commentPage = wpurl.split(/comment-page-/i)[1].split(/(\/|#|&).*$/)[0];
		} else if (/cpage=/i.test(wpurl)) {
			commentPage = wpurl.split(/cpage=/)[1].split(/(\/|#|&).*$/)[0];
		};
		var postId = $('#cp_post_id').text();
		var url = wpurl.split(/#.*$/)[0];
		url += /\?/i.test(wpurl) ? '&' : '?';
		url += 'action=AjaxCommentsPage&post=' + postId + '&page=' + commentPage;
		$.ajax({
			url : url,
			type : 'GET',
			beforeSend : function () {
				document.body.style.cursor = 'wait';
				var C = 0.5; //修改下面的选择器，评论列表div的id，分页部分的id
				$('#commentlist,#pagenavi').css({
					opacity : C,
					MozOpacity : C,
					KhtmlOpacity : C,
					filter : 'alpha(opacity=' + C * 100 + ')'
				});
				var loading = 'Comments Loading......';
				$('#pagenavi').html(loading);
			},
			error : function (request) {
				alert(request.responseText);
			},
			success : function (data) {
				var responses = data.split('@||@');
				$('#commentlist').html(responses[0]);
				$('#pagenavi').html(responses[1]);
				var C = 1; //修改下面的选择器，评论列表div的id，分页部分的id
				$('#commentlist,#pagenavi').css({
					opacity : C,
					MozOpacity : C,
					KhtmlOpacity : C,
					filter : 'alpha(opacity=' + C * 100 + ')'
				});
				$('#cmploading').remove();
				document.body.style.cursor = 'auto';
				ajacpload(); //自身重载一次
				//single_js();//需要重载的js，注意
				$body.animate({
					scrollTop : $('#comments').offset().top
				}, 1000);
			} //返回评论列表顶部
		});
		return false;
	});
}