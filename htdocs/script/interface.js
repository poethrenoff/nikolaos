function question_init() {
	$(function(){
		$('[comment]').each(function(){
			var $input = $(this);
			var comment = $input.attr('comment');
			$input.each(function(){
				var val = $(this).val();
				var vallength = $.trim(val).length;
				if(vallength == 0){ 
					$input.val(comment).addClass('comment');
				}
			}).focus(function(){
				var val = $(this).val();
				var vallength = $.trim(val).length;
				if(vallength == 0 || val == comment){ 
					$input.val('').removeClass('comment');
				}
			}).blur(function(){
				var val = $input.val();
				var vallength = $.trim(val).length;
				if(vallength == 0){
					$input.addClass('comment').val(comment);
				}
			}).parents('form').submit(function(){
				var val = $input.val();
				var vallength = $.trim(val).length;
				return !(vallength == 0 || val == comment);
			});
		})
	});
}

function photo_init() {
	$(function(){
		if ( typeof( SexyLightbox ) != 'undefined' ) {
			SexyLightbox.initialize({ color:'white', dir: '/script/sexy-lightbox/sexyimages' });
		}
	});
}
