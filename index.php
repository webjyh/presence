<?php get_header(); ?>
	<?php
		$presence_values = get_option('presence_framework_values');
		if ( !is_array( $presence_values ) ) $presence_values = array();
		if( array_key_exists( 'Infinite_scrolling' , $presence_values ) &&  $presence_values['Infinite_scrolling'] == 'on' ){
			$wrap = 'id="wrap"';
		}
	?>
	<div class="wrap w-size" <?php echo $wrap; ?>>
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
