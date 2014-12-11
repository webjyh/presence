<?php get_header(); ?>
	<div class="wrap w-size">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php
				$format = get_post_format();
				if( false === $format ) { $format = 'standard'; }
			?>
			<?php get_template_part( 'single', $format ); ?>
			<div class="single-navigation clearfix">
				<div class="prev-single"><?php previous_post_link( '%link', __( 'PRE', 'presence' ) ); ?></div>
				<div class="next-single"><?php next_post_link( '%link', __( 'NEXT', 'presence' ) ); ?></div>
			</div>
			<?php comments_template( '', true ); ?>
		<?php endwhile; ?>
	</div>
<?php get_footer(); ?>