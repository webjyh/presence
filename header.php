<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, user-scalable=no">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<meta http-equiv="Cache-Control" content="no-siteapp">
<?php include_once( get_template_directory().'/include/seo.php' ); ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<?php
$presence_values = get_option('presence_framework_values');
if ( !is_array( $presence_values ) ) $presence_values = array();
if( array_key_exists( 'general_custom_favicon' , $presence_values) && !empty( $presence_values['general_custom_favicon'] ) ){ ?>
<link rel="shortcut icon" href="<?php echo $presence_values['general_custom_favicon'];  ?>" />
<?php } ?>
<?php wp_head(); ?>
<?php
if( array_key_exists( 'style_custom_css' , $presence_values) && !empty( $presence_values['style_custom_css'] ) ){
echo '<style type="text/css">'."\n";
echo stripslashes( $presence_values['style_custom_css'] )."\n";
echo '</style>'."\n";
}
if( array_key_exists( 'website_width' , $presence_values) && !empty( $presence_values['website_width'] ) ){
echo '<style type="text/css">'."\n";
echo ".w-size { width:".$presence_values['website_width']."; }\n";
echo '</style>'."\n";
}
?>
</head>
<body <?php body_class(); ?>>
	<div class="header w-size">
		<h1 class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php
				if( array_key_exists( 'general_text_logo' , $presence_values ) && $presence_values['general_text_logo'] == 'on' ){
					echo esc_attr( get_bloginfo( 'name', 'display' ) );
				} else {
					if ( !empty( $presence_values['general_custom_logo'] ) ){
						echo '<img src="'.$presence_values['general_custom_logo'].'" alt="'.esc_attr( get_bloginfo( 'name', 'display' ) ).'" />';
					} else {
			?>
				<img src="<?php echo get_bloginfo("template_url"); ?>/images/logo.png" width="143" height="60" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" />
			<?php
					}
				}
			?></a></h1>
		<?php
			if ( array_key_exists( 'Blog_description', $presence_values ) && $presence_values['Blog_description'] == 'on' ){
		?>
		<div class="descarea"><?php echo bloginfo( 'description' ); ?></div>
		<?php
			}
		?>
		<div class="nav clearfix"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'menu' ) ); ?></div>
	</div>
	<div class="mobile-nav">
		<h1><?php wp_title( '|', true, 'right' ); ?></h1>
		<a href="javascript:;" title="<?php _e( 'Click Menu', 'presence' ); ?>" id="menu-trigger" class="menu-trigger"></a>
	</div>
