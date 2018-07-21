/* Add Custom Code Jquery
 ========================================================*/
$(document).ready(function(){

	// Messenger posmotion
	$( "#so_popup_countdown .customer a" ).click(function() {
		$('body').toggleClass('hidden-popup-countdown');
		$(this).toggleClass('active');
	});

	// Fix hover on IOS
	$('body').bind('touchstart', function() {}); 
	
	jQuery(function(){
		var windowswidth = $(window).width();
		var containerwidth = $('.container').width();
		var widthcss = (windowswidth-containerwidth)/2-70;
		
		var rtl = jQuery( 'body' ).hasClass( 'rtl' );
		if( !rtl ) {
			jQuery(".custom-scoll").css("left",widthcss);
		}else{
			jQuery(".custom-scoll").css("right",widthcss);
		}
		var x = $("#box-link1").position();
	});
	
	jQuery(function(){
		$('#nav-scroll').onePageNav({
			currentClass: 'active',
			changeHash: false,
			scrollSpeed: 750,
			scrollThreshold: 0.5,
			filter: '',
			easing: 'swing',
			
		});

		
	});

	// Messenger posmotion
	$( "#close-posmotion-header" ).click(function() {
		$('.promotion-top').toggleClass('hidden-promotion');
		$('body').toggleClass('hidden-promotion-body');

		if($(".promotion-top").hasClass("hidden-promotion")){
			$.cookie("open", 0);
			
		} else{
			$.cookie("open", 1);
		}

	});
	
	if($.cookie("open") == 0){
		$('.promotion-top').addClass('hidden-promotion');
		$('body').addClass('hidden-promotion-body');
	}


	// Messenger Top Link
	$('.list-msg').owlCarousel2({
		pagination: false,
		center: false,
		nav: false,
		dots: false,
		loop: true,
		slideBy: 1,
		autoplay: true,
		margin: 30,
		autoplayTimeout: 4500,
		autoplayHoverPause: true,
		autoplaySpeed: 1200,
		startPosition: 0, 
		responsive:{
			0:{
				items:1
			},
			480:{
				items:1
			},
			768:{
				items:1
			},
			1200:{
				items:1
			}
		}
	});

	// Slider Brands Home 1 - etrostores
	jQuery(document).ready(function($) {
	    var slider1 = $(".slider-brand-layout1 .slider-brand");
	    slider1.owlCarousel2({    
	    margin:30,
	    nav:true,
	    loop:true,
	    dots: false,
	    navText: ['',''],
	    responsive:{
	            0:{
	                items:1
	            },
	            480:{
	                items:2
	            },
	            768:{
	                items:4
	            },
	            992:{
	                items:5
	            },
	            1200:{
	                items:5
	            },
	        },
	    })
	});

	// Slider Cate Home 1 - etrostores
	jQuery(document).ready(function($) {
	    var slider3 = $(".box-cates ul");
	    slider3.owlCarousel2({    
	    margin:0,
	    nav:false,
	    loop:false,
	    dots: false,
	    navText: ['',''],
	    responsive:{
	            0:{
	                items:2
	            },
	            480:{
	                items:2
	            },
	            768:{
	                items:4
	            },
	            992:{
	                items:5
	            },
	            1200:{
	                items:6
	            },
	        },
	    })
	});

	// Slider Brands Home 2 - etrostores
	jQuery(document).ready(function($) {
	    var slider2 = $(".slider-brand-layout3 .slider-brand");
	    slider2.owlCarousel2({    
	    margin:0,
	    nav:false,
	    loop:true,
	    dots: false,
	    navText: ['',''],
	    responsive:{
	            0:{
	                items:2
	            },
	            480:{
	                items:2
	            },
	            768:{
	                items:3
	            },
	            992:{
	                items:5
	            },
	            1200:{
	                items:6
	            },
	        },
	    })
	});

	// Close pop up countdown
	 // $( "#so_popup_countdown .customer a.login" ).click(function() {
	 //  $('body').toggleClass('hidden-popup-countdown');
	 // });
	// =========================================


	// click header search header 
	jQuery(document).ready(function($){
		$( ".search-header-w .icon-search" ).click(function() {
		$('#sosearchpro .search').slideToggle(200);
		$(this).toggleClass('active');
		});
	});
	$( ".header_search .btn-search" ).click(function() {
		$('.sosearchpro-wrapper').toggleClass('active');
		$(this).toggleClass('active');
	});
	$( ".header_search .bt-search" ).click(function() {
		$('.sosearchpro-wrapper').toggleClass('active');
	});
	
	// add class Box categories
	jQuery(document).ready(function($){

		if($("#accordion-category .panel .panel-collapse").hasClass("in")){
			$('#accordion-category .panel .accordion-toggle').addClass("show");			
		} 
		else{
			$('#accordion-category .panel .accordion-toggle').removeClass("show");
		}

	});

	// click btn category slider on device
	jQuery(document).ready(function($){
		if ($(window).innerWidth() <= 991) {
			$( ".cate-slider1 .item-sub-cat" ).click(function() {
			$('.cate-slider1 .item-sub-cat > ul').slideToggle(200);
			$(this).toggleClass('active');
			});

			$( ".cate-slider2 .item-sub-cat" ).click(function() {
			$('.cate-slider2 .item-sub-cat > ul').slideToggle(200);
			$(this).toggleClass('active');
			});

			$( ".cate-slider3 .item-sub-cat" ).click(function() {
			$('.cate-slider3 .item-sub-cat > ul').slideToggle(200);
			$(this).toggleClass('active');
			});
		}
	});

	// sroll so categories on iphone - layout 2
	jQuery(document).ready(function($) {
	    var slider4 = $(".layout-2 .so-categories .cat-wrap");
	    slider4.owlCarousel2({    
	    margin:0,
	    nav:false,
	    loop:false,
	    dots: false,
	    navText: ['',''],
	    responsive:{
	            0:{
	                items:1
	            },
	            480:{
	                items:2
	            },
	            768:{
	                items:4
	            },
	            992:{
	                items:4
	            },
	            1200:{
	                items:4
	            },
	        },
	    })
	});

	// custom to show footer center
	$(".description-toggle").click(function () {
		if($('.showmore').hasClass('active'))
			$('.showmore').removeClass('active');
		else
			$('.showmore').addClass('active');
	}); 
	
	// Slider Banner So Supper
	$('.slider-img-cate').owlCarousel2({
		pagination: false,
		center: false,
		nav: false,
		loop: true,
		dots: false,
		slideBy: 1,
		autoplay: true,
		margin: 0,
		autoplayTimeout: 4500,
		autoplayHoverPause: true,
		autoplaySpeed: 1200,
		startPosition: 0, 
		responsive:{
			0:{
				items:1
			},
			480:{
				items:1
			},
			768:{
				items:1
			},
			1200:{
				items:1
			}
		}
	});
	
	// Slider Clients Say
	$('.slider-clients-say').owlCarousel2({
		pagination: false,
		center: false,
		nav: false,
		loop: true,
		slideBy: 1,
		autoplay: true,
		margin: 0,
		autoplayTimeout: 4500,
		autoplayHoverPause: true,
		autoplaySpeed: 1200,
		startPosition: 0, 
		responsive:{
			0:{
				items:1
			},
			480:{
				items:1
			},
			768:{
				items:1
			},
			1200:{
				items:1
			}
		}
	});


});
