	<div class="post-wrap" >
		<div class="m-post">
			<a class="typeicon audio" href="<?php the_permalink(); ?>"></a>
			<div class="content">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'presence' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="text">
					<?php 
						$file = get_post_meta( $post->ID, 'presence_File', true );
						if ( !empty( $file ) ){
					?>
					<div class="audio clearfix">
						<div class="audio-image">
							<?php
								$audio_img = get_post_meta( $post->ID, "presence_audio_upimg", true );
								if ( !empty( $audio_img ) ){
									$audioImage = get_bloginfo("template_url").'/timthumb.php?src='.$audio_img.'&q=100&w=150&h=150';
								}
							?>
							<a href="<?php echo $audio_img; ?>" title="<?php the_title(); ?>" class="phzoom"><img src="<?php echo $audioImage; ?>" /></a>
						</div>
						<div class="audio-content">
							<h3><?php the_title(); ?></h3>
							<p class="author">
								<strong><?php _e( 'Singer', 'presence' ); ?>&nbsp;:&nbsp;</strong>
								<?php
									$audio_author = get_post_meta( $post->ID, "presence_Author", true );
									if ( empty( $audio_author ) ){
										_e( 'Unknown' , 'presence' );
									} else {
										echo $audio_author;
									}
									echo '<span>&nbsp;&nbsp;&nbsp;&nbsp;'.get_post_meta( $post->ID, "presence_Level", true ).'</span>';
								?>
							</p>
							<p class="musicablum">
								<strong><?php _e( 'Music Album', 'presence' ); ?>&nbsp;:&nbsp;</strong>
								<?php
									$audio_album = get_post_meta( $post->ID, "presence_Album", true );
									if ( empty( $audio_album ) ){
										_e( 'Unknown' , 'presence' );
									} else {
										echo $audio_album;
									}
								?>
							</p>
							<p class="music-player">
								<embed src="<?php bloginfo('template_url'); ?>/images/player.swf?url=<?php echo $file; ?>&amp;autoplay=0" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" width="265" height="25">
							</p>
						</div>
					</div>
					<?php } ?>
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