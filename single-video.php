	<div class="post-wrap" >
		<div class="m-post">
			<?php
				$format = get_post_format();
				if( false === $format ) { $format = 'standard'; }
			?>
			<a class="typeicon <?php echo $format; ?>" href="<?php the_permalink(); ?>"></a>
			<div class="content">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'presence' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="text">
					<?php
						$video = get_post_meta( $post->ID, "presence_code", true );
						if ( !empty( $video ) ){
							echo '<div class="video">'.html_entity_decode( $video ).'</div>';
						}
					?>
					<?php the_content(); ?>
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
			<div class="from"><span class="reblog"><?php the_category('&nbsp;'); ?></span></div>
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