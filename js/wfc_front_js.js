jQuery(document).ready(function(){
	get_height();
	jQuery( document.body ).trigger( 'wc_fragment_refresh' );

	jQuery(window).on('resize', function(){
      get_height();
	});

    jQuery('body').on( 'added_to_cart', function(){
       get_height();
       jQuery(".wfc_container").css("display","block");
	   jQuery(".wfc_container").animate({width: '300px',right: '0px'});
    });

    setInterval(function(){  
    	get_height(); 
    }, 200);

    jQuery(".wfc_close_cart").click(function(){
	  	var boxWidth = jQuery(".wfc_container").width();
	   	jQuery(".wfc_container").animate({
            right: '-300px'
        });
	});

	jQuery(".wfc_cart_basket").click(function(){
		jQuery(".wfc_container").css("display","block");
		jQuery(".wfc_container").animate({width: '300px',right: '0px'});
	});

	jQuery('body').on('change', 'input[name="update_qty"]', function(){ 
	    var pro_id = jQuery(this).closest('tr').attr('product_id');
	    var qty = jQuery(this).val();
	    var c_key = jQuery(this).closest('tr').attr('c_key');
		var pro_ida = jQuery(this);
		pro_ida.prop('disabled', true);
	    jQuery.ajax({
	        url:ajax_postajax.ajaxurl,
	        type:'POST',
	        data:'action=change_qty&c_key='+c_key+'&qty='+qty,
	        success : function(response) {
	        	pro_ida.prop('disabled', false);
	            jQuery( document.body ).trigger( 'wc_fragment_refresh' );
	        }
	    });
	}); 



    jQuery('.wfc_slider_inn').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        dots: false,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    }) 
    
})



function get_height(){
	var full_height = jQuery('.wfc_container' ).height();
	var head_height = jQuery('.wfc_header').outerHeight();
	var foot_height = jQuery('.wfc_footer').outerHeight();
	var wfc_total_tr = jQuery('.wfc_total_tr').outerHeight();
    var wfc_slider = jQuery('.wfc_slider').outerHeight();
	var body_height = full_height - head_height - foot_height - wfc_total_tr - wfc_slider;
	var tr_height = foot_height + wfc_slider;


	jQuery(".wfc_total_tr").css("bottom",tr_height+"px");
    jQuery(".wfc_slider").css("bottom",foot_height+"px");
	jQuery(".wfc_body").css("height",body_height+"px");
}


jQuery(document).on('click', '.wfc_body a.remove', function (e) {
    e.preventDefault();

    var product_id = jQuery(this).attr("data-product_id"),
        cart_item_key = jQuery(this).attr("data-cart_item_key"),
        product_container = jQuery(this).parents('.wfc_body');
	

    // Add loader
    product_container.block({
        message: null,
        overlayCSS: {
            cursor: 'none'
        }
    });

    jQuery.ajax({
        type: 'POST',
        dataType: 'json',
        url: wc_add_to_cart_params.ajax_url,
        data: {
            action: "product_remove",
            product_id: product_id,
            cart_item_key: cart_item_key
        },
        success: function(response) {

            if ( ! response || response.error )
                return;

            var fragments = response.fragments;

            // Replace fragments
            if ( fragments ) {
                jQuery.each( fragments, function( key, value ) {
                    jQuery( key ).replaceWith( value );
                });
            }
        }
    });
});


jQuery(document).on('click', '.add_to_cart_button', function () {
    var cart = jQuery('.wfc_cart_basket');
    var imgtodrag = jQuery(this).parent('.product').find("img").eq(0);
    if (imgtodrag) {
        var imgclone = imgtodrag.clone()
            .offset({
            top: imgtodrag.offset().top,
            left: imgtodrag.offset().left
        })
            .css({
            'opacity': '0.8',
                'position': 'absolute',
                'height': '150px',
                'width': '150px',
                'z-index': '100'
        })
            .appendTo(jQuery('body'))
            .animate({
            'top': cart.offset().top + 10,
                'left': cart.offset().left + 10,
                'width': 75,
                'height': 75
        }, 1000, 'easeInOutExpo');
        
        setTimeout(function () {
            cart.effect("shake", {
                times: 2
            }, 200);
        }, 1500);

        imgclone.animate({
            'width': 0,
                'height': 0
        }, function () {
            jQuery(this).detach()
        });
    }
});

