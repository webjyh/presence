<?php
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])){
		die ( __( 'Please do not load this page directly. Thanks!', 'presence' ) );
	}
	if ( post_password_required() ) {
		echo '<p class="nocomments">'._e( 'This post is password protected. Enter the password to view comments.', 'presence' ).'</p>';
		return;
	}
?>
<?php if ('open' == $post->comment_status || have_comments()) : ?>
	<div class="comments clearfix" id="comments">
		<a class="typeicon comment-icon" href="#comments"></a>
		<?php if ( have_comments() ) : ?>
		<h2 class="comments-title"><?php  _e( 'Comment Reply', 'presence' ); ?></h2>
		<ol id="commentlist" class="commentlist clearfix">
			<?php wp_list_comments(array('type'=>'comment','callback'=>'presence_comment','avatar_size'=>35, 'reply_text'=> __( 'Reply', 'presence' ) )); ?>
		</ol>
		<span id="cp_post_id" style="display:none;"><?php echo $post->ID; ?></span>
		<?php
		if (get_option('page_comments')) {
			$comment_pages = paginate_comments_links('prev_text='. __( 'pre', 'presence' ).'&next_text='. __( 'next', 'presence'). '&echo=0');
			if ($comment_pages) {
				echo '<div class="pagenavi" id="pagenavi">';
				echo $comment_pages; 
				echo '</div>';
			}
		}
		?>
		<?php endif; ?>

		<?php if ( comments_open() ) : ?>
		<div id="respond" class="respond">
			<div class="cancel-comment-reply">
				<small><?php cancel_comment_reply_link(); ?></small>
			</div>
			<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
				<p><?php _e( 'You must be', 'presence' )?> <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>"><?php _e( 'logged in', 'presence' ) ?></a> <?php _e( 'to post a comment.', 'presence' ) ?></p>
				
			<?php else : ?>
			<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
				<?php if ( $user_ID ) : ?>
				<p>
						<?php _e( 'Logged in as', 'presence' ) ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account"><?php _e( 'Log out &raquo;', 'presence' ) ?></a></p>
				<?php else: ?>
				<p>
					<input type="text" class="text" tabindex="1" size="22" value="<?php echo $comment_author; ?>" id="author" name="author">
					<label for="author"><small><?php _e( 'Name', 'presence' ); ?>	( <span>*</span> )</small> </label>
				</p>
				<p>
					<input type="text" class="text" tabindex="2" size="22" value="<?php echo $comment_author_email; ?>" id="email" name="email" />
					<label for="email"><small><?php _e( 'Email', 'presence' ); ?> ( <span>*</span> )</small></label>
				</p>
				<p>
					<input type="text" class="text" tabindex="3" size="22" value="<?php echo $comment_author_url; ?>" id="url" name="url">
					<label for="url"><small><?php _e( 'Website', 'presence' ); ?> ( <?php _e( 'http://', 'presence' ); ?> )</small></label>
				</p>
				<?php endif; ?>

				<?php require_once(TEMPLATEPATH . '/smilies.php'); ?>
				<p>
					<textarea tabindex="4" rows="5" id="comment" class="textarea" name="comment"></textarea>
				</p>
				<p>
					<?php $num1 = rand(1,9); $num2 = rand(1,9); ?>
					<?php echo $num1?> + <?php echo $num2?> = <input type="text" class="text" tabindex="1" style="width:25px; text-align:center;" value="" id="math" name="math" placeholder="?">
					<input type="hidden" value="<?php echo $num1; ?>" class="text" name="num1" />
					<input type="hidden" value="<?php echo $num2; ?>" class="text" name="num2" />
				</p>
				<p>
					<input type="submit" class="submit" value="<?php echo _e( 'Submit', 'presence' ); ?>" tabindex="5" id="submit" name="submit">
					<?php comment_id_fields();?>
				</p>
				<?php do_action('comment_form', $post->ID); ?>
			</form>
			<?php endif; ?>
		</div>
		<?php endif; ?>	
	</div>
<?php endif; ?>