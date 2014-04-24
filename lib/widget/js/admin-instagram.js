jQuery(document).ready(function() {
	
	jQuery('input[name="instagram_type_select"]').change( function() {
		var selected = jQuery(this).val();
		console.log(selected);

		if ( selected == 'username' ) {
			jQuery(this).parents('.item-wrapper').siblings('#username').show();
			jQuery(this).parents('.item-wrapper').siblings('#hashtag').hide();
			jQuery(this).parents('.item-wrapper').siblings('#hashtag').find('input').val('');
		} else if ( selected == 'hashtag' ) {
			jQuery(this).parents('.item-wrapper').siblings('#hashtag').show();
			jQuery(this).parents('.item-wrapper').siblings('#username').hide();
			jQuery(this).parents('.item-wrapper').siblings('#username').find('input').val('');
		}

	});

});