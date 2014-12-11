<?php
/*
Template Name: Links
*/
?>
<?php get_header(); ?>

	<div class="wrap w-size">
	
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			
			<div class="post-wrap" >
				<div class="m-post">
					<a class="typeicon quote" href="<?php the_permalink(); ?>"></a>
					<div class="content">
						<h2 class="title">Links</h2>
						<div class="text">
							<div class="link-content">
							<?php
								$linkcats = $wpdb->get_results("SELECT T1.name AS name FROM $wpdb->terms T1, $wpdb->term_taxonomy T2 WHERE T1.term_id = T2.term_id AND T2.taxonomy = 'link_category'");
								if($linkcats) : foreach($linkcats as $linkcat) : ?>
									<h3><?php echo $linkcat->name; ?></h3>
									<ul class="clearfix">
										<?php
											$bookmarks = get_bookmarks('orderby=name&category_name=' . $linkcat->name);
											if ( !empty($bookmarks) ) {
												foreach ($bookmarks as $bookmark) {
													echo '<li><a href="' . $bookmark->link_url . '" title="' . $bookmark->link_description . '" target="_blank"><img style="float:left;" src="http://www.google.com/s2/favicons?domain='.str_replace( 'http://', '', $bookmark->link_url ).'">' . $bookmark->link_name . '</a></li>';
												}
											}
										?>
									</ul>
								<?php endforeach; endif; ?>
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
