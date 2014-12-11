jQuery(document).ready(function($){ 

    // Initial load Page Switcher
    var hash = window.location.hash;
    if(hash != ''){
		$('#page-' + hash.replace(/#/, '')).show();
        $('#presence-framework .nav li a[href="'+ hash +'"]').addClass('active');
	} else {
        $('#presence-framework .page:first').show();
        $('#presence-framework .nav li a:first').addClass('active');
    }
    
    // Page Switcher
    $('#presence-framework .nav li a').bind('click', function(){
        $('#presence-framework .page').hide();
        var loc = $(this).attr('href');
        $('#page-' + loc.replace(/#/, '')).show();
        $('#presence-framework .nav li a').removeClass('active');
        $(this).addClass('active');
    });
    
    // AJAX Save
    $('#presence-framework form').submit(function(){
        var form = $(this);
        form.trigger('presence-before-save');
        var button = $('#presence-framework #save-button');
        var buttonVal = button.val();
        button.val('Saving...');
		$.post(form.attr("action"), form.serialize(), function(data){
            button.val(buttonVal);
			//$('#presence-framework-messages').html(data.message);
			if(data.error){
				$.jGrowl(data.message, { header:'Error' });
			} else {
				$.jGrowl(data.message);
			}
            form.trigger('presence-saved');
		}, 'json');
		return false;
    });
    
    // Reset Button
    $('#presence-framework #reset-button').live('click', function(){
    	if(confirm('Click to reset. Any settings will be lost!')){
    		$(this).val('Reseting...');
	    	$.post(ajaxurl, { action:'presence_framework_reset', nonce:$('#presence_noncename').val() }, function(data){
				if(data.error){
					$.jGrowl(data.message, { header:'Error' });
				} else {
					window.location.reload(true);
				}
			}, 'json');
		}
		return false;
    });
    
    // Custom Layout Switcher
    $('#presence-framework .main-layout br').remove();
    $('#presence-framework .main-layout input[type="radio"]').each(function(){
    	var label = $(this).parent();
    	label.addClass($(this).val());
    	if($(this).is(':checked')) label.addClass('checked');
    });
    $('#presence-framework .main-layout label').live('click', function(){
    	$('#presence-framework .main-layout label').removeClass('checked');
    	$('#presence-framework .main-layout input[type="radio"]').attr('checked', false);
    	var id = $(this).attr('for');
    	$(this).addClass('checked');
    	$('#presence-framework .main-layout #'+ id).attr('checked', true);
    });
    
});