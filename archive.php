<?php get_header(); ?>
	<?php
		$presence_values = get_option('presence_framework_values');
		if ( !is_array( $presence_values ) ) $presence_values = array();
		if( array_key_exists( 'Infinite_scrolling' , $presence_values ) &&  $presence_values['Infinite_scrolling'] == 'on' ){
			$wrap = 'id="wrap"';
		}
	?>
	<div class="wrap w-size" <?php echo $wrap; ?>>
		
		<?php /* If this is a category archive */ if (is_category()) { ?>
		<h1 class="page-title"><?php printf(__('All posts in &ldquo;%s&rdquo;', 'presence'), single_cat_title('',false)); ?></h1>
		<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h1 class="page-title"><?php printf(__('All posts tagged &ldquo;%s&rdquo;', 'presence'), single_tag_title('',false)); ?></h1>
		<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h1 class="page-title"><?php _e('Archive for ', 'presence') ?> <?php the_time('F jS, Y'); ?></h1>
		 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h1 class="page-title"><?php _e('Archive for ', 'presence') ?> <?php the_time('F, Y'); ?></h1>
		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h1 class="page-title"><?php _e('Archive for', 'presence') ?> <?php the_time('Y'); ?></h1>
		<?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h1 class="page-title"><?php _e('All posts by ', 'presence') ?> <?php echo $curauth->display_name; ?></h1>
		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h1 class="page-title"><?php _e('Blog Archives', 'presence') ?></h1>
		<?php } ?>
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<?php
				$format = get_post_format();
				if( false === $format ) { $format = 'standard'; }
			?>
			
			<?php get_template_part( 'content', $format ); ?>
			
			<?php endwhile; ?>
			
			<?php
				if( array_key_exists( 'Infinite_scrolling' , $presence_values ) &&  $presence_values['Infinite_scrolling'] == 'on' ){
			?>	
				<div class="navigation" id="navigation"><?php next_posts_link( '' ) ?></div>
			<?php
				} else {
			?>
				<div class="m-pager clearfix">
					<?php par_pagenavi(9); ?>
				</div>
			<?php
				}
			?>

		<?php else : ?>

			<div class="no-result">
				<h1> <?php _e( 'No Results Found', 'presence' ); ?></h1>
				<p><?php _e( 'The page you requested could not be found. Try refining your search, or use the navigation above to locate the post.', 'presence' ); ?></p>
			</div>

		<?php endif; ?>

	</div>
	
<?php get_footer(); ?>
