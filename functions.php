<?php
/**
 * presence functions and definitions.
 *
 * @package M.J
 * @subpackage presence
 * @since Photo Broad 1.0
 */

	//add default function
	function presence_setup(){

		//Makes presence available for translation.
		load_theme_textdomain( 'presence', get_template_directory() . '/languages' );
		
		// Adds RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		// This theme uses a custom image size for featured images, displayed on "standard" posts.
		add_theme_support( 'post-thumbnails' );

		// This theme supports a variety of post formats.
		add_theme_support( 'post-formats', array( 'image', 'video', 'audio', 'quote' ) ); 

		// This theme uses wp_nav_menu() in one location.
		register_nav_menu( 'primary', 'Primary Menu' );

		/*
		 * This theme supports custom background color and image, and here
		 * we also set up the default background color.
		 */
		add_theme_support( 'custom-background', array(
			'default-color' => '0B3B41',
			'default-image' => get_template_directory_uri().'/images/bg.jpg',
		) );

	}
	add_action( 'after_setup_theme', 'presence_setup' );

	//remove shortcode from archive
	function remove_shortcode_from_archive($content) {
		if ( !is_singular() ) {
			$content = strip_shortcodes( $content );
		}
		return $content;
	}
	add_filter('the_content', 'remove_shortcode_from_archive');

	//Registers Link Manager
	add_filter( 'pre_option_link_manager_enabled', '__return_true' );
	
	function presence_wp_title( $title, $sep ) {

		global $paged, $page;

		if ( is_feed() )
			return $title;

		// Add the site name.
		$title .= get_bloginfo( 'name' );

		// Add the site description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			$title = "$title $sep $site_description";

		// Add a page number if necessary.
		if ( $paged >= 2 || $page >= 2 )
			$title = "$title $sep " . sprintf( __( 'Page %s', 'presence' ), max( $paged, $page ) );

		return $title;
	}
	add_filter( 'wp_title', 'presence_wp_title', 10, 2 );
	
	
	/**
	 * Get Post Thumb img
	 * @author M.J
	 * @return string 
	 */
	function get_post_thumb( $return_src = 'true' ){
		global $post, $posts;
		$content = $post->post_content;
		$pattern = '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i';
		$result = preg_match_all( $pattern, $content, $matches );
		if ( $return_src == 'true' ){
			if ( !empty( $result ) ){
				$imgResult = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$matches[1][0].'&amp;q=100&amp;w=210" alt="" />';
			}
		} else {
			$imgResult = $matches[1][0];
		}
		return $imgResult;
	}
	
	
	//New window opens Comment Link
	function hu_popuplinks($text) {
		$text = preg_replace('/<a (.+?)>/i', "<a $1 target='_blank'>", $text);
		return $text;
	}
	add_filter('get_comment_author_link', 'hu_popuplinks', 6);

	//To anti in English comment spam
	function scp_comment_post( $incoming_comment ) {
		$pattern = '/[一-龥]/u';
		if(!preg_match($pattern, $incoming_comment['comment_content'])) {
			err( __('You should type some Chinese word (like "Hello") in your comment to pass the spam-check, thanks for your patience! Your comment must contain Chinese characters!', 'presence') );
		}
		return( $incoming_comment );
	}
	add_filter('preprocess_comment', 'scp_comment_post');
	
	//Add Math For Comment
	function mathCode_comment_post(){
		$num1 = ( isset( $_POST['num1'] ) ) ? trim( intval( strip_tags( $_POST['num1'] ) ) ) : null;
		$num2 = ( isset( $_POST['num1'] ) ) ? trim( intval( strip_tags( $_POST['num2'] ) ) ) : null;
		$math = ( isset( $_POST['num1'] ) ) ? trim( intval( strip_tags( $_POST['math'] ) ) ) : null;
		if ( !num1 || !num2 || !math ){
			err( __( 'Warning: Wrong Answer!', 'presence' ) );
		} else {
			if ( ( $num1+$num2 ) != $math ){
				err( __( 'Warning: Wrong Answer!', 'presence' ) );
			}
		}
	}
	add_action( 'pre_comment_on_post', 'mathCode_comment_post');

	/* comment_mail_notify v1.0 by willin kan. */
	function comment_mail_notify($comment_id) {
		$comment = get_comment($comment_id);
		$parent_id = $comment->comment_parent ? $comment->comment_parent : '';
		$spam_confirmed = $comment->comment_approved;
		if (($parent_id != '') && ($spam_confirmed != 'spam')) {
			$wp_email = 'no-reply@' . preg_replace('#^www.#', '', strtolower($_SERVER['SERVER_NAME'])); //e-mail 发出点, no-reply 可改为可用的 e-mail.
			$to = trim(get_comment($parent_id)->comment_author_email);
			$subject = '您在 [' . get_option("blogname") . '] 的留言有了回复';
			$message = '
				<div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px;">
				<p><strong>' . trim(get_comment($parent_id)->comment_author) . '</strong>, 您好!</p>
				<p>您曾在 <a href="'.site_url( '/' ).'" target="_blank">'.get_option('blogname').'</a> 《' . get_the_title($comment->comment_post_ID) . '》的留言:</p>
				<p style="text-indent:2em;">'. trim(get_comment($parent_id)->comment_content) . '</p>
				<hr />
				<p> <a href="'.site_url( '/' ).'" target="_blank">'. trim($comment->comment_author) . '</a> 给您的回复:</p>
				<p style="text-indent:2em;">'. trim($comment->comment_content) . '</p>
				<p>您可以点击 <a href="'.get_permalink( $comment->comment_post_ID ).'#comment-'.$parent_id.'" target="_blank">查看回复完整內容</a></p>
				<hr />
				<p>欢迎再度光临 <a href="'.site_url( '/' ).'" target="_blank">'.get_option('blogname').'</a> </p>
				<p>(此邮件由系统自动发送，请勿回复.)</p>
			</div>';
			$from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
			$headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
			wp_mail( $to, $subject, $message, $headers );
		}
	}
	add_action('comment_post', 'comment_mail_notify');
	

	/**
	 * when comment check the comment_author comment_author_email
	 * @param unknown_type $comment_author
	 * @param unknown_type $comment_author_email
	 * @return unknown_type
	 * Prevent visitors posing bloggers comment 
	 */
	function CheckEmailAndName(){
		global $wpdb;
		$comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
		$comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
		if(!$comment_author || !$comment_author_email){
			return;
		}
		$result_set = $wpdb->get_results("SELECT display_name, user_email FROM $wpdb->users WHERE display_name = '" . $comment_author . "' OR user_email = '" . $comment_author_email . "'");
		if ($result_set) {
			if ($result_set[0]->display_name == $comment_author){
				err( __( 'Warning: You can not use this nickname, because this is the nickname of the bloggers!', 'presence' ) );
			}else{
				err( __( 'Warning: You can not use the mailbox, because this is the mailbox of the bloggers!', 'presence') );
			}
			fail($errorMessage);
		}
	}
	add_action('pre_comment_on_post', 'CheckEmailAndName');
	
	//Page Function
	function par_pagenavi($range = 9){
	
		global $paged, $wp_query;
		
		if ( !$max_page ) {
			$max_page = $wp_query->max_num_pages;
		}
		
		if($max_page > 1){
		
			if(!$paged){ $paged = 1; }
			
			echo '<div class="curoftotal">'.$paged.'/'.$max_page.'</div>';
			
			if($paged != 1){
				echo '<div class="page prv"><a href="' . get_pagenum_link(1) . '">'.__( 'Home', 'presence' ).'</a><div class="pgbg">&nbsp;</div></div>';
				echo '<div class="page prv">';
				previous_posts_link( __( ' Pre', 'presence' ) );
				echo '<div class="pgbg">&nbsp;</div></div>';
			}
			
			if($max_page > $range){
			
				if($paged < $range){
					for($i = 1; $i <= ($range + 1); $i++){
						if($i==$paged){
							echo '<div class="page current"><a href="javascript:;">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
						} else { 
							echo '<div class="page"><a href="'. get_pagenum_link($i) .'">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
						}
					}
				} elseif ( $paged >= ($max_page - ceil(($range/2))) ) {
					for($i = $max_page - $range; $i <= $max_page; $i++){
						if($i==$paged) {
							echo '<div class="page current"><a href="javascript:;">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
						} else {
							echo '<div class="page"><a href="'. get_pagenum_link($i) .'">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
						}
					}
				} elseif ( $paged >= $range && $paged < ($max_page - ceil(($range/2))) ){
					for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
						if($i==$paged) {
							echo '<div class="page current"><a href="javascript:;">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
						} else { 
							echo '<div class="page"><a href="'. get_pagenum_link($i) .'">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
						}
					}
				}
			} else {
				for($i = 1; $i <= $max_page; $i++){
					if($i==$paged) {
						echo '<div class="page current"><a href="javascript:;">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
					} else {
						echo '<div class="page"><a href="'. get_pagenum_link($i) .'">'.$i.'</a><div class="pgbg">&nbsp;</div></div>';
					}
				}
			}

			if($paged != $max_page){
				echo '<div class="page nxt">';
				next_posts_link( __( 'Next', 'presence' ) );
				echo '<div class="pgbg">&nbsp;</div></div>';
				echo '<div class="page nxt"><a href="' . get_pagenum_link($max_page) . '">'.__( 'Last', 'presence' ).'</a><div class="pgbg">&nbsp;</div></div>';
			}

		}
	}
	
	//Replace the default expression
	function custom_smilies_src($img_src,$img,$siteurl) {
		return get_bloginfo('template_directory').'/images/smilies/'.$img;
	}
	add_filter('smilies_src','custom_smilies_src',1,10);
	
	
	//phzoom
	function phzoom( $content ){
		return preg_replace( '/<a(.*?)href=(.*?).(bmp|gif|jpeg|jpg|png)"(.*?)>/i', '<a$1href=$2.$3" $4 class="phzoom">', $content );
	}
	add_filter( 'the_content', 'phzoom', 2 );


	/**ajax comment page
	 * @return unknown_type
	 */
	function AjaxCommentsPage(){
	
		if( isset($_GET['action'])&& $_GET['action'] == 'AjaxCommentsPage'  ){
		
			global $post,$wp_query, $wp_rewrite;
			$postid = isset($_GET['post']) ? $_GET['post'] : null;
			$pageid = isset($_GET['page']) ? $_GET['page'] : null;
			
			if(!$postid || !$pageid){
				fail(__('Error post id or comment page id.'));
			}
		
			$comments = get_comments('post_id='.$postid);

			$post = get_post($postid);

			if(!$comments){
				fail(__('Error! can\'t find the comments'));
			}

			if( 'desc' != get_option('comment_order') ){
				$comments = array_reverse($comments);
			}

			// set as singular (is_single || is_page || is_attachment)
			$wp_query->is_singular = true;

			// base url of page links
			$baseLink = '';
			if ($wp_rewrite->using_permalinks()) {
				$baseLink = '&base=' . user_trailingslashit(get_permalink($postid) . 'comment-page-%#%', 'commentpaged');
			}

			// response 注意修改callback为你自己的，没有就去掉callback
			wp_list_comments('callback=presence_comment&type=comment&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);
			echo '@||@';
			paginate_comments_links('current=' . $pageid . $baseLink);
			die;
		}
	}
	add_action('init', 'AjaxCommentsPage');
?>
<?php
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own presence_comment(), and that function will be used instead.
	 */
	function presence_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment; 
?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div class="comment-body clearfix" id="comment-<?php comment_ID(); ?>">
		<?php echo get_avatar( $comment, $size='35' ); ?>
		<div class="comment-wrap">
			<div class="comment-author">
				<span class="reply-container">
					<?php comment_reply_link(array_merge( $args, array('reply_text' => __('Reply ','presence' ), 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
					<?php edit_comment_link(__(' Edit ', 'presence'),'  ','') ?>
					<span class="comment-meta commentmetadata"><?php echo(get_comment_date()) ?></span>
				</span>
				<span id="reviewer-<?php echo comment_ID(); ?>"><?php printf('%s', get_comment_author_link()) ?></span>
			</div>
			<div class="comment-content"><?php comment_text() ?></div>
		</div>
	</div>
<?php
	}
	require_once( TEMPLATEPATH . '/include/metaboxclass.php' );
	//add post.php MetaBox
	set_metaBox();

	//Default Set MetaBox
	function set_metaBox(){
		$options = array();
		$metaBox = array();
		$options['audio'] = array(
			'title' => array( 
				"name" => __("These settings enable you to embed audio into your posts. You must provide both .mp3 and .agg/.oga file formats in order for self hosted audio to function accross all browsers." , 'presence'),
				"type" => "title"
				),
			'file' => array(
				"name" => __( "MP3 File URL" , 'presence' ),
				"desc" => __( "The URL to the .mp3 audio file" , 'presence' ),
				"id" => "presence_File",
				"size"=>"40",
				"std" => "",
				"type" => "text"
				),
			'level' => array(
				"name" => __( "MP3 Level" , 'presence' ),
				"desc" => __( "Used here &hearts; fill" , 'presence' ),
				"id" => "presence_Level",
				"size"=>"40",
				"std" => "",
				"type" => "text"
				),
			'author' => array(
				"name" => __( "MP3 Author" , 'presence' ),
				"desc" => __( "Here to fill in the name of the artist" , 'presence' ),
				"id" => "presence_Author",
				"size"=>"40",
				"std" => "",
				"type" => "text"
				),
			'musicablum' => array(
				"name" => __( "MP3 Album" , 'presence' ),
				"desc" => __( "Here to fill out the album name" , 'presence' ),
				"id" => "presence_Album",
				"size"=>"40",
				"std" => "",
				"type" => "text"
				),
			'image' => array(
				"name" => __( "Audio Poster Image", 'presence' ),
				"desc" => __( "The preview image for this audio track. Image width should be 210px.", 'presence' ),
				"id" => "presence_audio_upimg",
				"std" => "",
				"button_label"=> __( 'Upload Image' , 'presence' ),
				"type" => "media"
			)
		);

		$options['video'] = array(
			'title' => array( 
				"name" => __("These settings enable you to embed videos into your posts." , 'presence'),
				"type" => "title"
				),
			'image' => array(
				"name" => __( "Poster Image", 'presence' ),
				"desc" => __( "The preview image for this audio track. Image width should be 210px.", 'presence' ),
				"id" => "presence_video_upimg",
				"std" => "",
				"button_label"=> __( 'Upload Image' , 'presence' ),
				"type" => "media"
			),
			'code' => array(
				"name" => __( "Embedded Code", 'presence' ),
				"desc" => __( "If you are using something other than self hosted video such as Youtube or Vimeo, paste the embed code here. Width is best at 500px with any height.", 'presence' ),
				"id" => "presence_code",
				"std" => "",
				"type" => "textarea"
			)
		);

		$audio = array( 'title' => __( 'Audio Settings' , 'presence' ), 'id'=>'metaBox-post-format-audio', 'page'=>array('post'), 'context'=>'normal', 'priority'=>'low', 'callback'=>'' );
		$video = array( 'title' => __( 'Video Settings' , 'presence' ), 'id'=>'metaBox-post-format-video', 'page'=>array('post'), 'context'=>'normal', 'priority'=>'low', 'callback'=>'' );
		$new_box = new ashu_meta_box( $options['audio'], $audio );
		$new_box = new ashu_meta_box( $options['video'], $video );

	}
	
	
	/**
	 * Load admin CSS
	 */
	function presence_admin_styles() {
		wp_enqueue_style('presence_admin_css', get_bloginfo('template_directory') .'/include/presence-admin.css');
		wp_enqueue_style('presence_jgrowl', get_bloginfo('template_directory') .'/include/jgrowl/jquery.jgrowl.css');
		wp_enqueue_style('farbtastic');
	}
	add_action('admin_print_styles', 'presence_admin_styles');
	
	
	/**
	 * Load admin JS
	 */
	function presence_admin_scripts() {
		wp_register_script('presence-ajaxupload', get_bloginfo('template_directory') .'/include/ajaxupload.js', array('jquery'));
		wp_enqueue_script('presence-ajaxupload');  
		wp_register_script('presence-jgrowl', get_bloginfo('template_directory') .'/include/jgrowl/jquery.jgrowl_min.js', array('jquery'));
		wp_enqueue_script('presence-jgrowl'); 
		wp_register_script('presence-framework-admin', get_bloginfo('template_directory') .'/include/admin.js', array('jquery','farbtastic'));
		wp_enqueue_script('presence-framework-admin'); 
		wp_enqueue_script('jquery');
		wp_enqueue_style('farbtastic');
	}
	add_action('admin_enqueue_scripts', 'presence_admin_scripts');
	
	//theme setting
	function presence_setting(  ){
		$presence_values = get_option('presence_framework_values');
		if ( !is_array( $presence_values ) ){
			$presence_values = array();
		}
?>
		<div id="presence-framework" class="clearfix">
			<form action="<?php echo site_url() .'/wp-admin/admin-ajax.php'; ?>" method="post">
				<div class="header clearfix">
					<h1 class="theme-name">Presence</h1>
					<span class="theme-version">v.2.0</span>
					<ul class="theme-links">
						<li><a href="http://mail.163.com/share/mail2me.htm#email=106105097110103121097104097105064049054051046099111109" target="_blank" class="forums"><?php _e( 'Write to the author', 'presence' ); ?></a></li>
						<li><a href="http://webjyh.com" target="_blank" class="themes"><?php _e( 'Author Home', 'presence' ); ?></a></li>
					</ul>
				</div>
				<div class="main clearfix">
					<div class="nav">
						<ul>
							<li><a href="#general-settings"><?php _e( 'General Settings', 'presence' ); ?></a></li>
							<li><a href="#styling-options"><?php _e( 'Styling Options', 'presence' ); ?></a></li>
						</ul>
					</div>
					<div class="content">
						<div id="page-general-settings" class="page">
							<h2><?php _e( 'General Settings', 'presence' ); ?></h2>
							<p class="page-desc"><?php _e( 'Control and configure the general setup of your theme. Upload your preferred logo, setup your text length and insert your analytics tracking code.', 'presence' ) ?></p>
							<div class="section ">
								<h3><?php _e( 'Plain Text Logo', 'presence' ); ?></h3>
								<div class="desc"><?php _e( 'Check this box to enable a plain text logo rather than upload an image. Will use your site name.', 'presence' ); ?> </div>
								<div class="input checkbox">
								<?php
									if(array_key_exists( 'general_text_logo' , $presence_values ) && $presence_values['general_text_logo'] == 'on') $val = ' checked="yes"';
									if(array_key_exists( 'general_text_logo' , $presence_values ) && $presence_values['general_text_logo'] != 'on') $val = '';
									echo '<input type="hidden" name="settings[general_text_logo]" value="off" />
									<input type="checkbox" id="general_text_logo" name="settings[general_text_logo]" value="on"'. $class . $val .' /> ';
								?>
								</div>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'Blog description', 'presence' ); ?></h3>
								<div class="desc"><?php _e( 'Display the blog description', 'presence' ); ?> </div>
								<div class="input checkbox">
								<?php
									if(array_key_exists( 'Blog_description' , $presence_values ) && $presence_values['Blog_description'] == 'on') $val = ' checked="yes"';
									if(array_key_exists( 'Blog_description' , $presence_values ) && $presence_values['Blog_description'] != 'on') $val = '';
									echo '<input type="hidden" name="settings[Blog_description]" value="off" />
									<input type="checkbox" id="Blog_description" name="settings[Blog_description]" value="on"'. $class . $val .' /> ';
								?>
								</div>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'Custom Logo Upload', 'presence' ); ?></h3>
								<div class="desc"><?php _e( 'Upload a logo for your theme.', 'presence' ); ?> </div>
								<div class="input file">
									<?php 
										$wp_uploads = wp_upload_dir();
									?>
									<div class="ajax-uploaded" id="uploaded_general_custom_logo">
										<?php 
											if(array_key_exists( 'general_custom_logo' , $presence_values)){
												$ext = substr( $presence_values['general_custom_logo'], strrpos($presence_values['general_custom_logo'], '.') + 1 );
												if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif'){
													echo '<img src="'. $presence_values['general_custom_logo'] .'" alt="" />'; 
												} else {
													echo $presence_values['general_custom_logo']; 
												}
											}
										?>
									</div>
									<a class="button-secondary " id="ajax_upload_general_custom_logo" href="#"><?php _e( 'Upload Image', 'presence' ); ?></a>
									<a class="button-secondary" id="ajax_remove_general_custom_logo" href="#" <?php if( !array_key_exists( 'general_custom_logo' , $presence_values )){ echo ' style="display:none"'; } ?>><?php _e( 'Remove', 'presence' ); ?></a>
								</div>
								<script type="text/javascript">
								jQuery(document).ready(function($){ 
									var button = $('#ajax_upload_general_custom_logo');
									var buttonVal = button.text();
									var interval = '';
									// AJAX upload
									new AjaxUpload(button, {
										action: '<?php echo site_url(); ?>/wp-admin/admin-ajax.php',
										data: { action:'presence_ajax_upload', data:'general_custom_logo' },
										onSubmit : function(file, ext){
											button.text('Uploading');
											this.disable();
											
											 // Uploding -> Uploading. -> Uploading...
											interval = window.setInterval(function(){
												var text = button.text();
												if (text.length < 13){
													button.text(text + '.');
												} else {
													button.text('Uploading');
												}
											}, 200);
										},
										onComplete: function(file, response){
											button.text(buttonVal);
											this.enable();
											window.clearInterval(interval);
											
											// Show image
											$('#uploaded_general_custom_logo').html('');
											var ext = file.substr(file.lastIndexOf(".")+1,file.length);
											if(ext && /^(jpg|png|jpeg|gif)$/.test(ext)){
												$('#uploaded_general_custom_logo').html('<img src="<?php echo $wp_uploads['url']; ?>/' + file + '" alt="" />');
											} else {
												$('#uploaded_general_custom_logo').text('<?php echo $wp_uploads['url']; ?>/' + file);
											}
											$('#ajax_remove_general_custom_logo').show();
										}
									});
									
									var remove = $('#ajax_remove_general_custom_logo');
									remove.bind('click', function(){
										remove.text('Removing...');
										$.post('<?php echo site_url(); ?>/wp-admin/admin-ajax.php', 
											{ action:'presence_ajax_remove', data:'general_custom_logo' }, 
											function(data){
												remove.fadeOut(500, function(){
													remove.text('Remove');
												});
												$('#uploaded_general_custom_logo').html('');
											}
										);
										return false;
									});
								});
								</script>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'Custom Favicon Upload' , 'presence' ); ?></h3>
								<div class="desc"><?php _e( "Upload a 16px x 16px Png/Gif image that will represent your website's favicon." , "presence" ); ?></div>
								<div class="input file">
									<?php 
										$wp_uploads = wp_upload_dir();
									?>
									<div class="ajax-uploaded" id="uploaded_general_custom_favicon">
										<?php 
											if(array_key_exists( 'general_custom_favicon' , $presence_values)){
												$ext = substr( $presence_values['general_custom_favicon'], strrpos($presence_values['general_custom_favicon'], '.') + 1 );
												if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif'){
													echo '<img src="'. $presence_values['general_custom_favicon'] .'" alt="" />'; 
												} else {
													echo $presence_values['general_custom_favicon']; 
												}
											}
										?>
									</div>
									<a class="button-secondary" id="ajax_upload_general_custom_favicon" href="#"><?php _e( 'Upload Image', 'presence' ); ?></a>
									<a class="button-secondary" id="ajax_remove_general_custom_favicon" href="#" <?php if( !array_key_exists( 'general_custom_favicon' , $presence_values )){ echo ' style="display:none"'; } ?>><?php _e( 'Remove', 'presence' ); ?></a>
								</div>
								<script type="text/javascript">
								jQuery(document).ready(function($){ 
									var button = $('#ajax_upload_general_custom_favicon');
									var buttonVal = button.text();
									var interval = '';
									// AJAX upload
									new AjaxUpload(button, {
										action: '<?php echo site_url(); ?>/wp-admin/admin-ajax.php',
										data: { action:'presence_ajax_upload', data:'general_custom_favicon' },
										onSubmit : function(file, ext){
											button.text('Uploading');
											this.disable();
											
											 // Uploding -> Uploading. -> Uploading...
											interval = window.setInterval(function(){
												var text = button.text();
												if (text.length < 13){
													button.text(text + '.');
												} else {
													button.text('Uploading');
												}
											}, 200);
										},
										onComplete: function(file, response){
											button.text(buttonVal);
											this.enable();
											window.clearInterval(interval);
											
											// Show image
											$('#uploaded_general_custom_favicon').html('');
											var ext = file.substr(file.lastIndexOf(".")+1,file.length);
											if(ext && /^(jpg|png|jpeg|gif)$/.test(ext)){
												$('#uploaded_general_custom_favicon').html('<img src="<?php echo $wp_uploads['url']; ?>/' + file + '" alt="" />');
											} else {
												$('#uploaded_general_custom_favicon').text('<?php echo $wp_uploads['url']; ?>/' + file);
											}
											$('#ajax_remove_general_custom_favicon').show();
										}
									});
									
									var remove = $('#ajax_remove_general_custom_favicon');
									remove.bind('click', function(){
										remove.text('Removing...');
										$.post('<?php echo site_url(); ?>/wp-admin/admin-ajax.php', 
											{ action:'presence_ajax_remove', data:'general_custom_favicon' }, 
											function(data){
												remove.fadeOut(500, function(){
													remove.text('Remove');
												});
												$('#uploaded_general_custom_favicon').html('');
											}
										);
										return false;
									});
								});
								</script>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'webSite width' , 'presence' ) ?></h3>
								<div class="desc"><?php _e( 'Setting the total width of the web site can be pixel (866PX) units can also be a percentage (90%) as a unit, but be sure must be written (px) or (%)' , 'presence' ) ?></div>
								<div class="input text"><input type="text" value="<?php  if(array_key_exists('website_width', $presence_values)) echo $presence_values['website_width']; ?>" name="settings[website_width]" id="website_width"></div>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'webSite Keyword' , 'presence' ) ?></h3>
								<div class="desc"><?php _e( 'Set the keywords of the website' , 'presence' ) ?></div>
								<div class="input text"><input type="text" value="<?php  if(array_key_exists('website_keyword', $presence_values)) echo $presence_values['website_keyword']; ?>" name="settings[website_keyword]" id="website_keyword"></div>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'webSite description' , 'presence' ) ?></h3>
								<div class="desc"><?php _e( 'Set the description of the website' , 'presence' ) ?></div>
								<div class="input textarea"><textarea name="settings[website_description]" id="website_description"><?php
										if(array_key_exists('website_description', $presence_values)) {
											echo stripslashes( $presence_values['website_description'] );
										};
									?></textarea>
								</div>
								<div class="presence-clear"></div>
							</div>
							<div class="section tracking-code">
								<h3><?php _e( 'Tracking Code' , 'presence' ) ?></h3>
								<div class="desc"><?php _e( 'Paste your Google Analytics (or other) tracking code here. It will be inserted before the closing body tag of your theme.' , 'presence' ) ?></div>
								<div class="input textarea"><textarea name="settings[general_tracking_code]" id="general_tracking_code"><?php
										if(array_key_exists('general_tracking_code', $presence_values)) {
											echo stripslashes( $presence_values['general_tracking_code'] );
										};
									?></textarea>
								</div>
								<div class="presence-clear"></div>
							</div>
						</div>
						<div id="page-styling-options" class="page">
							<h3><?php _e( 'Styling Options' , 'presence' ) ?></h3>
							<div class="desc"><?php _e( 'Configure the visual appearance of you theme by selecting a stylesheet if applicable, choosing your overall layout and inserting any custom CSS necessary.' , 'presence' ) ?></div>
							<div class="section ">
								<h3><?php _e( 'Show summary', 'presence' ); ?></h3>
								<div class="desc"><?php _e( 'Select this check box is enabled display Abstract default display articles All', 'presence' ); ?> </div>
								<div class="input checkbox">
								<?php
									if(array_key_exists( 'general_summary' , $presence_values ) && $presence_values['general_summary'] == 'on') $val = ' checked="yes"';
									if(array_key_exists( 'general_summary' , $presence_values ) && $presence_values['general_summary'] != 'on') $val = '';
									echo '<input type="hidden" name="settings[general_summary]" value="off" />
									<input type="checkbox" id="general_summary" name="settings[general_summary]" value="on"'. $class . $val .' /> ';
								?>
								</div>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'Infinite scrolling', 'presence' ); ?></h3>
								<div class="desc"><?php _e( 'Open up infinite scroll', 'presence' ); ?> </div>
								<div class="input checkbox">
								<?php
									if(array_key_exists( 'Infinite_scrolling' , $presence_values ) && $presence_values['Infinite_scrolling'] == 'on') $val = ' checked="yes"';
									if(array_key_exists( 'Infinite_scrolling' , $presence_values ) && $presence_values['Infinite_scrolling'] != 'on') $val = '';
									echo '<input type="hidden" name="settings[Infinite_scrolling]" value="off" />
									<input type="checkbox" id="Infinite_scrolling" name="settings[Infinite_scrolling]" value="on"'. $class . $val .' /> ';
								?>
								</div>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'phZoom', 'presence' ); ?></h3>
								<div class="desc"><?php _e( 'Open phZoom', 'presence' ); ?> </div>
								<div class="input checkbox">
								<?php
									if(array_key_exists( 'phZoom' , $presence_values ) && $presence_values['phZoom'] == 'on') $val = ' checked="yes"';
									if(array_key_exists( 'phZoom' , $presence_values ) && $presence_values['phZoom'] != 'on') $val = '';
									echo '<input type="hidden" name="settings[phZoom]" value="off" />
									<input type="checkbox" id="phZoom" name="settings[phZoom]" value="on"'. $class . $val .' /> ';
								?>
								</div>
								<div class="presence-clear"></div>
							</div>
							<div class="section ">
								<h3><?php _e( 'Text length' , 'presence' ) ?></h3>
								<div class="desc"><?php _e( 'Set the plain text of the article text length.' , 'presence' ) ?></div>
								<div class="input text"><input type="text" value="<?php  if(array_key_exists('text_length', $presence_values)) echo $presence_values['text_length']; ?>" name="settings[text_length]" id="text_length"></div>
								<div class="presence-clear"></div>
							</div>
								
							<div class="section custom-css">
								<h3><?php _e( 'Custom CSS' , 'presence' ) ?></h3>
								<div class="desc"><?php _e( 'Quickly add some CSS to your theme by adding it to this block.' , 'presence' ) ?></div>
								<div class="input textarea">
									<textarea name="settings[style_custom_css]" id="style_custom_css"><?php
										if(array_key_exists('style_custom_css', $presence_values)) {
											echo stripslashes( $presence_values['style_custom_css'] );
										};
									?></textarea>
								</div>
								<div class="presence-clear"></div>
							</div>
						</div>
					</div>
					<div class="presence-clear"></div>
				</div>
				<div class="footer clearfix">
					<input type="hidden" value="presence_framework_save" name="action">
					<input type="hidden" value="<?php echo wp_create_nonce('presence_framework_options'); ?>" id="presence_noncename" name="presence_noncename">
					<input type="button" id="reset-button" class="button" value="<?php _e( 'Reset Options' , 'presence' ) ?>">
					<input type="submit" id="save-button" class="button-primary" value="<?php _e( 'Save All Changes' , 'presence' ) ?>">
				</div>
			</form>
		</div>
<?php
	}
	
	/**
	 * AJAX Save Options
	 */
	function presence_framework_save(){
		$response['error'] = false;
		$response['message'] = '';
		$response['type'] = '';
		
		// Verify this came from the our screen and with proper authorization
		if(!isset($_POST['presence_noncename']) || !wp_verify_nonce($_POST['presence_noncename'], plugin_basename('presence_framework_options'))){
			$response['error'] = true;
			$response['message'] = __('You do not have sufficient permissions to save these options.', 'presence' );
			echo json_encode($response);
			die;
		}
				
		$presence_values = get_option('presence_framework_values');
		foreach( $_POST['settings'] as $key => $val ){
			$presence_values[$key] = $val;
		}
		
		$presence_values = apply_filters( 'presence_framework_save', $presence_values ); // Pre save filter
		
		update_option('presence_framework_values', $presence_values);
		
		$response['message'] = __( 'Settings saved', 'presence' );    
		echo json_encode($response);
		die;
	}
	add_action('wp_ajax_presence_framework_save', 'presence_framework_save');

	/**
	 * AJAX Reset Options
	 */
	function presence_framework_reset(){
		$response['error'] = false;
		$response['message'] = '';
		
		// Verify this came from the our screen and with proper authorization
		if(!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], plugin_basename('presence_framework_options'))){
			$response['error'] = true;
			$response['message'] = __('You do not have sufficient permissions to reset these options.', 'presence' );
			echo json_encode($response);
			die;
		}
				
		update_option('presence_framework_values', array());
		  
		echo json_encode($response);
		die;
	}
	add_action('wp_ajax_presence_framework_reset', 'presence_framework_reset');

	/**
	 * Framework AJAX upload
	 */
	function presence_ajax_upload(){
		$response['error'] = false;
		$response['message'] = '';
		
		$wp_uploads = wp_upload_dir();
		$uploadfile = $wp_uploads['path'] .'/'. basename($_FILES['userfile']['name']);

		if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
			$presence_values = get_option('presence_framework_values');
			$presence_values[$_POST['data']] = $wp_uploads['url'] .'/'. basename($_FILES['userfile']['name']);
			update_option('presence_framework_values', $presence_values);
			$response['message'] =  'success';
		} else {
			$response['error'] = true;
			$response['message'] =  'error'; 
		}
		
		echo json_encode($response);
		die;
	}
	add_action('wp_ajax_presence_ajax_upload', 'presence_ajax_upload');

	/**
	 * Framework AJAX remove upload
	 */
	function presence_ajax_remove(){
		$response['error'] = false;
		$response['message'] = '';
		
		$data = $_POST['data'];

		$presence_values = get_option('presence_framework_values');
		unset($presence_values[$_POST['data']]);
		update_option('presence_framework_values', $presence_values);
		$response['message'] =  'success';
		
		echo json_encode($response);
		die;
	}
	add_action('wp_ajax_presence_ajax_remove', 'presence_ajax_remove');
    
	function theme_page(){
		add_theme_page( 
			__('Theme Options'),
			__('Theme Options'), 
			__('edit_themes'), 
			basename(__FILE__), 'presence_setting' 
		);
	}
	add_action('admin_menu','theme_page');
	
	//Login Page
	function custom_login() {
		echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('template_directory') . '/css/login.css" />'."\n";
		echo '<script type="text/javascript" src="'.get_bloginfo('template_directory').'/js/jquery.min.js"></script>'."\n";
	}
	add_action('login_head', 'custom_login');

	//Login Page Title
	function custom_headertitle ( $title ) {
		return get_bloginfo('name');
	}
	add_filter('login_headertitle','custom_headertitle');

	//Login Page Link
	function custom_loginlogo_url($url) {
		return esc_url( home_url('/') ); //修改URL地址
	}
	add_filter( 'login_headerurl', 'custom_loginlogo_url' );

	//Login Page Footer
	function custom_html() {
		echo '<div class="footer">'."\n";
		echo '<p>Copyright &copy; '.date('Y').' M.J All Rights | Author by <a href="'.esc_url( home_url('/') ).'" target="_blank">'.get_bloginfo('name').'</a></p>'."\n";
		echo '</div>'."\n";
		echo '<script type="text/javascript" src="'.get_bloginfo('template_directory').'/js/resizeBg.js"></script>'."\n";
		echo '<script type="text/javascript">'."\n";
		echo 'jQuery("body").prepend("<div class=\"loading\"><img src=\"'.get_bloginfo('template_directory').'/images/login_loading.gif\" width=\"58\" height=\"10\"></div><div id=\"bg\"><img /></div>");'."\n";
		echo 'jQuery(\'#bg\').children(\'img\').attr(\'src\', \''.get_bloginfo('template_directory').'/images/login_bg.jpg\').load(function(){'."\n";
		echo '	resizeImage(\'bg\');'."\n";
		echo '	jQuery(window).bind("resize", function() { resizeImage(\'bg\'); });'."\n";
		echo '	jQuery(\'.loading\').fadeOut();'."\n";
		echo '});';
		echo '</script>'."\n";
	}
	add_action('login_footer', 'custom_html');
?>