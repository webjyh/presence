	<div class="post-wrap" >
		<div class="m-post">
			<a class="typeicon image" href="<?php the_permalink(); ?>"></a>
			<div class="content">
				<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'presence' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
				<div class="text">
					<?php
						$presence_values = get_option('presence_framework_values');
						if ( !is_array( $presence_values ) ) $presence_values = array();
						if ( array_key_exists( 'general_summary' , $presence_values ) &&  $presence_values['general_summary'] == 'on' ) {
							$img = get_post_thumb( 'false' );
							if ( !empty( $img ) ) {
								echo '<p style="text-align:center;"><a href="'.$img.'" class="phzoom" title="';
								echo the_title();
								echo '"><img src="'.$img.'" alt="';
								echo the_title();
								echo '" /></a></p>';
							}
							if ( array_key_exists( 'text_length', $presence_values ) && !empty( $presence_values['text_length'] ) ){
								$strlen = intval( $presence_values['text_length'] );
							} else {
								$strlen = 350;
							}
							echo '<p>'.mb_strimwidth( strip_tags(apply_filters('the_content', $post->post_content)), 0, $strlen, "&nbsp;[...]").'</p>';
						} else {
							the_content();
						}
					?>
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