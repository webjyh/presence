<script type="text/javascript">
/* <![CDATA[ */
function grin(tag) {
	var myField;
	tag = ' ' + tag + ' ';
	if (document.getElementById('comment') && document.getElementById('comment').type == 'textarea') {
		myField = document.getElementById('comment');
	} else {
		return false;
	}
	if (document.selection) {
		myField.focus();
		sel = document.selection.createRange();
		sel.text = tag;
		myField.focus();
	}
	else if (myField.selectionStart || myField.selectionStart == '0') {
		var startPos = myField.selectionStart;
		var endPos = myField.selectionEnd;
		var cursorPos = endPos;
		myField.value = myField.value.substring(0, startPos)
					  + tag
					  + myField.value.substring(endPos, myField.value.length);
		cursorPos += tag.length;
		myField.focus();
		myField.selectionStart = cursorPos;
		myField.selectionEnd = cursorPos;
	}
	else {
		myField.value += tag;
		myField.focus();
	}
}
</script>
<p class="smilies">
	<a href="javascript:grin(':?:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_question.gif" width="18" height="16" /></a>
	<a href="javascript:grin(':razz:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_razz.gif" width="17" height="16" /></a>
	<a href="javascript:grin(':sad:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_sad.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':evil:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_evil.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':!:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_exclaim.gif" width="18" height="16" /></a>
	<a href="javascript:grin(':smile:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_smile.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':oops:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_redface.gif" width="17" height="16" /></a>
	<a href="javascript:grin(':grin:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_biggrin.gif" width="20" height="16" /></a>
	<a href="javascript:grin(':eek:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_surprised.gif" width="18" height="16" /></a>
	<a href="javascript:grin(':shock:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_eek.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':???:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_confused.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':cool:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_cool.gif" width="18" height="16" /></a>
	<a href="javascript:grin(':lol:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_lol.gif" width="18" height="16" /></a>
	<a href="javascript:grin(':mad:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_mad.gif" width="19" height="16" /></a>
	<a href="javascript:grin(':twisted:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_twisted.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':roll:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_rolleyes.gif" width="19" height="16" /></a>
	<a href="javascript:grin(':wink:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_wink.gif" width="19" height="16" /></a>
	<a href="javascript:grin(':idea:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_idea.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':arrow:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_arrow.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':neutral:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_neutral.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':cry:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_cry.gif" width="16" height="16" /></a>
	<a href="javascript:grin(':mrgreen:');"><img src="<?php bloginfo('template_directory'); ?>/images/smilies/icon_mrgreen.gif" width="17" height="16" /></a>
</p>