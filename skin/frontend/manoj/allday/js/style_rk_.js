jQuery(document).ready(function($){
	

	// $().jSnow();
    //jQuery.fn.snow({minSize: 15, maxSize: 25, newOn: 250, flakeColor: '#bbb' });/*minSize: 5, maxSize: 25, newOn: 1000, */
    //jQuery.fn.snow1({minSize: 10, maxSize: 20, newOn: 1000, flakeColor: '#bbb' });/*minSize: 5, maxSize: 25, newOn: 1000, */
    // $.fn.snow2({minSize: 20, maxSize: 20, newOn: 2000, flakeColor: '#bbb' });/*minSize: 5, maxSize: 25, newOn: 1000, */

    jQuery(".more_info_btn").click(function(){
    	jQuery(".desc_area").slideToggle();
    });

	
	/* Category page more details accordion */	
	$(".example2 li").first().addClass("active");
	jQuery('.example2').accordion({
	    canToggle: true,
	    canOpenMultiple: false
	});	
	jQuery(".loading").removeClass("loading");

	var filter_cat_value =$(".catalogsearch-result-index .block-layered-nav dt").first().text();
	if ( filter_cat_value == "Category"){
		$(".catalogsearch-result-index .block-layered-nav dt").first().css("display","none");
	}


});
