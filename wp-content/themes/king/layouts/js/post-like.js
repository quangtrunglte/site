html2element:(function( $ ) {
	'use strict';
	$(document).on('click', '.king-like-button', function() {
		var button = $(this);
		var post_id = button.attr('data-post-id');
		var security = button.attr('data-nonce');

		var allbuttons;
			allbuttons = $('.king-like-button-'+post_id);
		var loader = allbuttons.next('#sl-loader');
		if (post_id !== '') {
			$.ajax({
				type: 'POST',
				url: simpleLikes.ajaxurl,
				data : {
					action : 'king_process_simple_like',
					post_id : post_id,
					nonce : security,
				},
				beforeSend:function(){
					loader.html('&nbsp;<div class="loader"></div>');
					allbuttons.addClass('effect');
				},				
				success: function(response){
					var icon = response.icon;
					var count = response.count;
					allbuttons.html(icon+count);
					if(response.status === 'unliked') {
						var like_text = simpleLikes.like;
						allbuttons.prop('title', like_text);
						allbuttons.removeClass('liked');
					} else {
						var unlike_text = simpleLikes.unlike;
						allbuttons.prop('title', unlike_text);
						allbuttons.addClass('liked');
					}
					loader.empty();		
					allbuttons.removeClass('effect');			
				}
			});
			
		}
		return false;
	});
})( jQuery );