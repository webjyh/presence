<?php
/***********wordpress文章编辑页面创建自定义面板类文件*************/
class ashu_meta_box{
	var $options;
	var $boxinfo;
	
	//构造函数
	function ashu_meta_box($options,$boxinfo){
		$this->options = $options;
		$this->boxinfo = $boxinfo;
		
		add_action('admin_menu', array(&$this, 'init_boxes'));
		add_action('save_post', array(&$this, 'save_postdata'));
	}
	
	//初始化
	function init_boxes(){
		$this->add_script_and_styles();
		$this->create_meta_box();
	}
	
	//加载css和js脚本
	function add_script_and_styles(){
		if(basename( $_SERVER['PHP_SELF']) == "page.php" 
		|| basename( $_SERVER['PHP_SELF']) == "page-new.php" 
		|| basename( $_SERVER['PHP_SELF']) == "post-new.php" 
		|| basename( $_SERVER['PHP_SELF']) == "post.php"
		|| basename( $_SERVER['PHP_SELF']) == "media-upload.php")
		{	
			//注意加载的脚本的url
			wp_enqueue_style('metabox_fields_css', get_bloginfo('template_url') . '/include/metabox_fields.css');
			wp_enqueue_script('metabox_fields_js', get_bloginfo('template_url') . '/js/metabox_fields.js');

			if(isset($_GET['hijack_target']))
			{	
				add_action('admin_head', array(&$this,'add_hijack_var'));
			}
		}
	}
	
	/*************************/
	function add_hijack_var()
	{
		echo "<meta name='hijack_target' content='".$_GET['hijack_target']."' />\n";
	}
	
	//创建自定义面板
	function create_meta_box(){
		if ( function_exists('add_meta_box') && is_array($this->boxinfo['page']) ) 
		{
			foreach ($this->boxinfo['page'] as $area)
			{	
				if ($this->boxinfo['callback'] == '') $this->boxinfo['callback'] = 'new_meta_boxes';
				
				add_meta_box( 	
					$this->boxinfo['id'], 
					$this->boxinfo['title'],
					array(&$this, $this->boxinfo['callback']),
					$area, $this->boxinfo['context'], 
					$this->boxinfo['priority']
				);  
			}
		}  
	}
	
	//创建自定义面板的显示函数
	function new_meta_boxes(){
		global $post;
		//根据类型调用显示函数
		foreach ($this->options as $option)
		{				
			if (method_exists($this, $option['type']))
			{	
				$meta_box_value = get_post_meta($post->ID, $option['id'], true); 
				if($meta_box_value != "") $option['std'] = $meta_box_value;  
				
				echo '<div class="alt kriesi_meta_box_alt meta_box_'.$option['type'].' meta_box_'.$this->boxinfo['context'].'">';
				$this->$option['type']($option);
				echo '</div>';
			}
		}
		
		//隐藏域
		echo'<input type="hidden" name="'.$this->boxinfo['id'].'_noncename" id="'.$this->boxinfo['id'].'_noncename" value="'.wp_create_nonce(plugin_basename(__FILE__) ).'" />';  
	}
	
	//保存字段数据
	function save_postdata() {
		$post_id = $_POST['post_ID'];
		
		foreach ($this->options as $option) {
			if (!wp_verify_nonce($_POST[$this->boxinfo['id'].'_noncename'], plugin_basename(__FILE__))) {	
				return $post_id ;
			}
			//判断权限
			if ( 'page' == $_POST['post_type'] ) {
				if ( !current_user_can( 'edit_page', $post_id  ))
				return $post_id ;
			} else {
				if ( !current_user_can( 'edit_post', $post_id  ))
				return $post_id ;
			}
			//将预定义字符转换为html实体
			$data = htmlspecialchars($_POST[$option['id']], ENT_QUOTES,"UTF-8");
			
			if(get_post_meta($post_id , $option['id']) == "")
			add_post_meta($post_id , $option['id'], $data, true);
			
			elseif($data != get_post_meta($post_id , $option['id'], true))
			update_post_meta($post_id , $option['id'], $data);
			
			elseif($data == "")
			delete_post_meta($post_id , $option['id'], get_post_meta($post_id , $option['id'], true));
			
		}
	}
	//显示标题
	function title($values){
		echo '<p class="metaBox-title">'.$values['name'].'</p><hr class="metaBox-hr" />';
	}
	//文本框
	function text($values){	
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];
		
		echo '<label for="'.$values['id'].'" class="metaBox-label"><strong>'.$values['name'].'</strong><span>'.$values['desc'].'</span></label>';
		echo '<p class="metaBox-p"><input type="text" size="'.$values['size'].'" value="'.$values['std'].'" id="'.$values['id'].'" name="'.$values['id'].'"/></p>';
	    echo '<div class="clear"></div>';
	}
	//文本域
	function textarea($values){
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];
		
		echo '<label for="'.$values['id'].'" class="metaBox-label"><strong>'.$values['name'].'</strong><span>'.$values['desc'].'</span></label>';
		echo '<p class="metaBox-p"><textarea class="kriesi_textarea" cols="40" rows="5" id="'.$values['id'].'" name="'.$values['id'].'">'.$values['std'].'</textarea></p>';
	    echo '<div class="clear"></div>';
	}
	//媒体上传
	function media($values){
		if(isset($this->database_options[$values['id']])) $values['std'] = $this->database_options[$values['id']];
		
		//图片上传按钮
		global $post_ID, $temp_ID;
		$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
		$media_upload_iframe_src = "media-upload.php?post_id=$uploading_iframe_ID";
		$image_upload_iframe_src = apply_filters('image_upload_iframe_src', "$media_upload_iframe_src&amp;type=image");
		
		$button = '<a href="'.$image_upload_iframe_src.'&amp;hijack_target='.$values['id'].'&amp;TB_iframe=true" id="'.$values['id'].'" class="k_hijack button thickbox" title="'.$image_title.'" onclick="return false;" >'.$values['button_label'].'</a>';
		
		//判断图片格式,图片预览
		$image = '';
		if($values['std'] != '') {
			$fileextension = substr($values['std'], strrpos($values['std'], '.') + 1);
			$extensions = array('png','gif','jpeg','jpg','pdf','tif');
			
			if(in_array($fileextension, $extensions))
			{
				$image = '<img width="210" src="'.$values['std'].'" />';
			}
		}
		
		echo '<div id="'.$values['id'].'_div" class="kriesi_preview_pic">'.$image .'</div>';
		echo '<label for="'.$values['id'].'" class="metaBox-label"><strong>'.$values['name'].'</strong><span>'.$values['desc'].'</span></label>';
		echo '<p class="metaBox-p"><input class="kriesi_preview_pic_input" type="text" size="'.$values['size'].'" value="'.$values['std'].'" name="'.$values['id'].'"/>&nbsp;&nbsp;'.$button;
		echo '</p><div class="clear"></div>';
	}
	//单选框
	function radio( $values ){
		echo '<p>'.$values['name'].'</p>';
		$counter = 1;
		foreach( $values['buttons'] as $radiobutton ) {
			$checked ="";
			if(isset($values['std']) && $values['std'] == $counter) {
				$checked = 'checked = "checked"';
			}
			echo '<input '.$checked.' type="radio" class="kcheck" value="'.$counter.'" name="'.$values['id'].'"/>'.$radiobutton;
			$counter++;
		}
	}
	//复选框
	function checkbox($values){
		if(isset($values['std']) && $values['std'] == 'true') $checked = 'checked = "checked"';
		echo '<p>'.$values['name'].'</p>';
		echo '<p><input class="kcheck" type="checkbox" name="'.$values['id'].'" id="'.$values['id'].'" value="true"  '.$checked.' />';
		echo '<label for="'.$values['id'].'">'.$values['desc'].'</label><br/></p>';
	}
	//下拉框
	function dropdown($values){
	
		echo '<p>'.$values['name'].'</p>';
			//选择内容可以使页面、分类、菜单、侧边栏和自定义内容
			if($values['subtype'] == 'page'){
				$select = 'Select page';
				$entries = get_pages('title_li=&orderby=name');
			}else if($values['subtype'] == 'cat'){
				$select = 'Select category';
				$entries = get_categories('title_li=&orderby=name&hide_empty=0');
			}else if($values['subtype'] == 'menu'){
				$select = 'Select Menu in page left';
				$entries = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			}else if($values['subtype'] == 'sidebar'){
				global $wp_registered_sidebars;
				$select = 'Select a special sidebar';
				$entries = $wp_registered_sidebars;
			}else{	
				$select = 'Select...';
				$entries = $values['subtype'];
			}
		
			echo '<p><select class="postform" id="'. $values['id'] .'" name="'. $values['id'] .'"> ';
			echo '<option value="">'.$select .'</option>  ';
			
			foreach ($entries as $key => $entry){
				if($values['subtype'] == 'page'){
					$id = $entry->ID;
					$title = $entry->post_title;
				}else if($values['subtype'] == 'cat'){
					$id = $entry->term_id;
					$title = $entry->name;
				}else if($values['subtype'] == 'menu'){
					$id = $entry->term_id;
					$title = $entry->name;
				}else if($values['subtype'] == 'sidebar'){
					$id = $entry['id'];
					$title = $entry['name'];
				}else{
					$id = $entry;
					$title = $key;				
				}

				if ($values['std'] == $id ){
					$selected = "selected='selected'";	
				}else{
					$selected = "";		
				}
				
				echo"<option $selected value='". $id."'>". $title."</option>";
			}
		
		echo '</select>';
		echo $values['desc'].'<br/></p>'; 
	    echo '<br/>';
	}
}
?>