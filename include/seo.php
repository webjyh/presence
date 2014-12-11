<?php
$presence_values = get_option('presence_framework_values');
if ( !is_array( $presence_values ) ) $presence_values = array();
if ( is_home() ) {
	if ( array_key_exists( 'website_keyword' , $presence_values ) && !empty( $presence_values['website_keyword'] ) ) {
		$keywords = $presence_values['website_keyword'];
	}
	if ( array_key_exists( 'website_description' , $presence_values ) && !empty( $presence_values['website_description'] ) ) {
		$description = $presence_values['website_description'];
	}
} elseif ( is_single() ) {
    $title = get_post($id)->post_title;
	$keywords = get_post_meta($post->ID, "keywords", true);
	if($keywords == ""){
		$tags = wp_get_post_tags($post->ID);
		foreach ($tags as $tag){
			$keywords = $keywords.$tag->name.",";
		}
		$keywords = $title.','.rtrim($keywords, ', ');
	}
	$description = get_post_meta($post->ID, "description", true);
	if($description == ""){
		if($post->post_excerpt){
			$description = $post->post_excerpt;
		}else{
			$description = mb_strimwidth(strip_tags(apply_filters('the_content',$post->post_content)),0,200);
		}
	}
} elseif ( is_page() ) {
	$keywords = get_post_meta($post->ID, "keywords", true);
	$description = get_post_meta($post->ID, "description", true);
} elseif ( is_category() ) {
	$keywords = single_cat_title('', false);
	$description = category_description();
} elseif ( is_tag() ){
	$keywords = single_tag_title('', false);
	$description = tag_description();
}
echo '<meta name="Keywords" content="'.trim(strip_tags($keywords)).'" />'."\n";
echo '<meta name="description" content="'.trim(strip_tags($description)).'" />'."\n";
?>