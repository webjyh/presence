<?php
/*
Template Name: Archives
*/
?>
<?php get_header(); ?>
	<div class="wrap w-size">
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post-wrap" >
				<div class="m-post">
					<a class="typeicon standard" href="<?php the_permalink(); ?>"></a>
					<div class="content">
						<h2 class="title"><?php the_title(); ?></h2>
						<div class="text">
							<div class="post-content">
								<div class="archive-lists">
									<h4><?php _e('Last 30 Posts', 'presence') ?></h4>
									<ul>
										<?php 
											$archive_30 = get_posts('numberposts=30');
											foreach($archive_30 as $post) : ?>
												<li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
										<?php endforeach; ?>
									</ul>
									<h4><?php _e('Archives by Month:', 'presence') ?></h4>
									<ul>
										<?php wp_get_archives('type=monthly'); ?>
									</ul>
									<h4><?php _e('Archives by Subject:', 'presence') ?></h4>
									<ul>
										<?php wp_list_categories( 'title_li=' ); ?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="m-infoarea clearfix">
					<a class="time" href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
					<?php if(function_exists('the_views')) { ?>
					<a href="<?php the_permalink(); ?>" class="like"><?php echo the_views('Views', true);?></a>
					<?php } ?>
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
		<?php endwhile; endif; ?>
	</div>
<?php get_footer(); ?>
