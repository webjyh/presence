	<div class="mobile-menu-wrap clearfix"><?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'mobile-menu' ) ); ?></div>	
	<div class="footer w-size">
		<p>Copyright &copy; <?php echo date("Y"); ?> <a href="<?php echo home_url( '/' ); ?>"><?php echo esc_attr( get_bloginfo( 'name') ); ?></a> All Rights Reserved </p>
		<p>Powered by <a target="_blank" href="http://wordpress.org/">WordPress <?php bloginfo('version');?></a> | Author by  <a href="http://webjyh.com" target="_blank">M.J</a></p>
		<?php
		$presence_values = get_option('presence_framework_values');
		if ( !is_array( $presence_values ) ) $presence_values = array();
		if( array_key_exists( 'general_tracking_code' , $presence_values ) && !empty( $presence_values['general_tracking_code'] ) ){
		echo '<p>'.stripslashes( $presence_values['general_tracking_code'] ).'</p>'."\n";
		}
		?>
	</div>
	<div id="gtotop" class="m-goTopArea"><a  href="javascript:;" class="goTop" title="回到顶部">回到顶部</a></div>
	<?php 
		$bg = get_background_image();
		if ( !empty( $bg ) ){
	?>
	<div class="bg-fixed"></div>
	<?php 
		} 
	?>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.min.js"></script>
	<script type = "text/javascript" > 
	jQuery(function () {
			jQuery(window).scroll(function () {
				if ($(window).scrollTop() >= $(window).height()) {
					jQuery("#gtotop").fadeIn(600)
				} else {
					jQuery("#gtotop").fadeOut(600)
				}
			});
			jQuery("#gtotop").click(function () {
				jQuery('body,html').animate({
					scrollTop : 0
				}, 600);
				return false
			});
			$('.menu li').hover(function () {
				var li_w = $(this).css('width');
				$('.menu li > .sub-menu a').css({
					'width' : li_w,
					'padding' : '0'
				});
				$(this).children('a').addClass('current');
				$(this).children('ul').stop(true, true).slideDown('fast')
			}, function () {
				$(this).children('ul').stop(true, true).slideUp('fast');
				$(this).children('a').removeClass('current')
			});
			$('#menu-trigger').click(function () {
				if (!$('.mobile-menu-wrap').hasClass('mobile-menu-left')) {
					$('.header, .mobile-nav, .wrap, .footer').addClass('mobile-left');
					$('.mobile-menu-wrap').addClass('mobile-menu-left');
				} else {
					$('.mobile-menu-wrap').removeClass('mobile-menu-left');
					$('.header, .mobile-nav, .wrap, .footer').removeClass('mobile-left');
				}
			});
			$('.title a, .quote-text a').click(function (e) {
				e.preventDefault();
				var htm = 'Loading',
				i = 4,
				t = $(this).html(htm).unbind('click');
				(function ct() {
					i < 0 ? (i = 4, t.html(htm), ct()) : (t[0].innerHTML += '.', i--, setTimeout(ct, 150))
				})();
				window.location = this.href
			});
		});
	</script>
	<?php
		if( array_key_exists( 'Infinite_scrolling' , $presence_values ) &&  $presence_values['Infinite_scrolling'] == 'on' ){
	?>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.infinitescroll.min.js"></script>
	<script type="text/javascript">
		jQuery(function () {
			$('#wrap').infinitescroll({
				navSelector : "#navigation",
				nextSelector : "#navigation a",
				itemSelector : ".post-wrap",
				loading : {
					img: '<?php bloginfo('template_url'); ?>/images/loading_scroll.gif',
					msgText  : "",
					finishedMsg :　""
				}
			}
			<?php
				if( array_key_exists( 'phZoom' , $presence_values ) &&  $presence_values['phZoom'] == 'on' ){
			?>
			,function(arrayOfNewElems){
				$('.phzoom').phzoom();
			}
			<?php
				}
			?>
			);
		});
	</script>
	<?php
		}
	?>
	<?php
		if( array_key_exists( 'phZoom' , $presence_values ) &&  $presence_values['phZoom'] == 'on' ){
	?>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/phzoom.js"></script>
	<script type="text/javascript">jQuery(function () { $('.phzoom').phzoom(); });</script>
	<?php
		}
	?>
	<?php if (is_singular()) { ?>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/commentlist.js"></script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/comments-ajax.js"></script>
	<script type="text/javascript">ajacpload();</script>
	<?php } ?>
	<?php wp_footer(); ?>
	
	<!--[if lt IE 9]>
	<script src="<?php bloginfo('template_directory'); ?>/js/ie.js"></script>
	<![endif]-->
</body>
</html>