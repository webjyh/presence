<?php get_header(); ?>
	<div class="page-404 clearfix">
		<style type="text/css">
		.page-404 pre{ width:600px;background:#fff;margin:30px auto;font:18px/1.1 '\5fae\8f6f\96c5\9ed1','\9ed1\4f53';-webkit-box-shadow:5px 5px 5px #ddd;-moz-box-shadow:5px 5px 5px #ddd;box-shadow:0 0 5px #ddd;-moz-border-radius-topleft:90px;-moz-border-radius-bottomright:90px;-webkit-border-top-left-radius:90px;-webkit-border-bottom-right-radius:90px;border-top-left-radius:90px;border-bottom-right-radius:90px;border:1px solid #eee;padding:30px; line-height:100%;}
		.page-404 pre:hover{background:#fafafa}
		.page-404 pre p { margin:0px; }
		#font-404-title {color:#09e;font-size:23px;}
		#font-404-1{font-size:90px;color:#900}
		#font-404-2{font-size:30px;color:#900}
		#time-404{color:#900}
		</style>
		<pre>
			<p>&lt;!DOCTYPE HTML&gt;</p>
			<p>&lt;html&gt;</p>
			<p>	&lt;head&gt;</p>
			<p>		&lt;title&gt;<span id="font-404-title"><?php wp_title( '|', true, 'right' ); ?></span>&lt;/title&gt;</p>
			<p>	&lt;/head&gt;</p>
			<p>	&lt;body&gt;</p>
			<p>		&lt;h1&gt;<span id="font-404-1">404</span><span id="font-404-2">,  <?php _e( 'You Know', 'presence'); ?></span>&lt;/h1&gt;</p>
			<p>	&lt;/body&gt;<br />
			<p>&lt;/html&gt;
		</pre>
	</div>
<?php get_footer(); ?>