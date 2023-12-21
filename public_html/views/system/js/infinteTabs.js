jQuery(function () {

	function executeScroll(){
		if(jQuery('.infintetabs').scrollLeft()>0 && !jQuery('.infinteTabs-nav.prev').hasClass('active')){
			jQuery('.infinteTabs-nav.prev').addClass('active');
		}
		if( jQuery('.infintetabs').scrollLeft() <= 0 && jQuery('.infinteTabs-nav.prev').hasClass('active')){
			jQuery('.infinteTabs-nav.prev').removeClass('active');
		}
		if( jQuery('.infintetabs').scrollLeft() >= (jQuery('.infintetabs').prop('scrollWidth') - jQuery('.infintetabs').prop('clientWidth') ) && jQuery('.infinteTabs-nav.next').hasClass('active') ){
			jQuery('.infinteTabs-nav.next').removeClass('active');
		}
		if( jQuery('.infintetabs').scrollLeft() < (jQuery('.infintetabs').prop('scrollWidth') - jQuery('.infintetabs').prop('clientWidth') ) && !jQuery('.infinteTabs-nav.next').hasClass('active') ){
			jQuery('.infinteTabs-nav.next').addClass('active');
		}	
	}
	executeScroll();
	
	jQuery('.infinteTabs').scroll(function(){
		executeScroll();
	}); 
	
	jQuery(".next").click(function(){
		jQuery('.infinteTabs').animate( { scrollLeft: '+=460' }, 600);
	}); 
	jQuery(".prev").click(function(){
		jQuery('.infinteTabs').animate( { scrollLeft: '-=460' }, 600);	 	
	});
	
	jQuery('.infinteTabs').scrollLeft( jQuery('.infinteTabs li.active').first().position().left - 100);
	
});


