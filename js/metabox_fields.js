jQuery.noConflict();
jQuery(document).ready(function(){	
	hijack_media_uploader();
	hijack_preview_pic();
	formats_select();
	set_formats();
});

function hijack_preview_pic(){
	jQuery('.kriesi_preview_pic_input').each(function(){
		jQuery(this).bind('focus blur change ktrigger', function(){	
			$select = '#' + jQuery(this).attr('name') + '_div';
			$value = jQuery(this).val();
			if ( $value != '' ){
				$image = '<img width="210" src ="'+$value+'" />';
				var $image = jQuery($select).html('').append($image).find('img');
				//set timeout because of safari
				window.setTimeout(function(){
				 	if(parseInt($image.attr('width')) < 20){	
						jQuery($select).html('');
					}
				},500);
			}
		});
	});
}

function hijack_media_uploader(){		
		$buttons = jQuery('.k_hijack');
		$realmediabuttons = jQuery('.media-buttons a');
		window.custom_editor = false;
		$buttons.click(function(){	
			window.custom_editor = jQuery(this).attr('id');			
		});
		$realmediabuttons.click(function(){
			window.custom_editor = false;
		});
		window.original_send_to_editor = window.send_to_editor;
		window.send_to_editor = function(html){	
			if (custom_editor) {	
				$img = jQuery(html).attr('src') || jQuery(html).find('img').attr('src') || jQuery(html).attr('href');
				
				jQuery('input[name='+custom_editor+']').val($img).trigger('ktrigger');
				custom_editor = false;
				window.tb_remove();
			}else{
				window.original_send_to_editor(html);
			}
		};
}

function formats_select(){
	jQuery('#post-formats-select input').click(function(){
		set_formats();
	});
}

function set_formats(){
	var val=jQuery('input:radio[name="post_format"]:checked').val(); 
	if( val != null ){
		jQuery('#metaBox-post-format-audio, #metaBox-post-format-video').css( 'display', 'none' );
		switch ( val ){
			case 'video':
				jQuery('#metaBox-post-format-video').css( 'display', 'block' )
				break;
			case 'audio':
				jQuery('#metaBox-post-format-audio').css( 'display', 'block' )
				break;
			default:
				jQuery('#metaBox-post-format-audio, #metaBox-post-format-video').css( 'display', 'none' );
		}
	}
}
