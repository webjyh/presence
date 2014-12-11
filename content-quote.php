	<div class="post-wrap">
		<div class="m-post">
			<a class="typeicon quote" href="<?php the_permalink(); ?>"></a>
			<div class="content">
				<h3 class="quote-text"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="text">
					<p class="quote-article">Author:&nbsp;<a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author(); ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:<a href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a></p>
				</div>
			</div>
		</div>
	</div>