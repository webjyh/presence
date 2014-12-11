<?php get_header(); ?>
	<div class="wrap w-size">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post-wrap" >
				<div class="m-post">
					<a class="typeicon standard" href="<?php the_permalink(); ?>"></a>
					<div class="content">
						<h2 class="title"><?php the_title(); ?></h2>
						<div class="text">
							<?php the_content(); ?>
							<?php wp_link_pages( array( 'before' => '<div class="page-links">', 'after' => '</div>' ) ); ?>
						</div>
						
						<?php edit_post_link( __( 'Edit', 'presence' ), '<span class="edit-link">', '</span>' ); ?>
					</div>
				</div>
				<div class="m-infoarea clearfix">
					<a class="time" href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
					<?php if(function_exists('the_views')) { ?>
					<a href="<?php the_permalink(); ?>" class="like"><?php echo the_views('Views', true);?></a>
					<?php } ?>
					<?php comments_popup_link( '0', '1', '%', 'comment' ); ?>
					<span class="sep">/</span>
					<div class="tagarea clearfix">
						<span class="taglb"><?php _e( 'Tag:&nbsp;', 'presence' ); ?></span>
						<?php 
							$taglist = get_the_tag_list( '', '' );
							if ( $taglist ) {
								echo $taglist;
							} else {
								echo __( 'No Tags', 'presence' );
							}
						?>
					</div>
				</div>
			</div>
			<?php comments_template( '', true ); ?>
		<?php endwhile; endif; ?>
	</div>
<?php get_footer(); ?>
