jQuery(document).ready(function($){
	
	$('.zilla-metabox-table .button').click(function() {
		var button = $(this),
		    tbURL = $('#content-add_media').attr('href');
		    
		if( typeof tbURL === 'undefined' ) {
		    tbURL = $('#add_image').attr('href');
		}

		if( $(this).attr('id') != 'zilla_images_upload' ) {
		    window.send_to_editor = function(html) {
    			var imgurl = $('img', html).attr('src');
    			var id = button.attr('id').replace('_button', '');
    			$('#'+ id).val(imgurl);
    			tb_remove();
    		}
    		
    		tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');
    		
	    } else {
	        tb_show('', tbURL);
	    }
		
		return false;
	});

});
