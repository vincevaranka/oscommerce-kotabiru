$(document).ready(function(){
/*START HERE */
/**CART**/
$('.col-boxes-right i').click(function() {
	var $col = $('.col-boxes-right'), $icon = $('.col-boxes-right i');
		if ($col.hasClass('col-pos')) {
			$icon.removeClass('fa-chevron-left');
			$icon.addClass('fa-chevron-right');
			$col.removeClass('col-pos');
			$col.addClass('col-left');
		} else {
			$icon.removeClass('fa-chevron-right');
			$icon.addClass('fa-chevron-left');
			$col.removeClass('col-left');
			$col.addClass('col-pos');
		}
});


$(".cart-btn").hover(
	function(){$(".cart-btn").stop().animate({width:'80%'},100);$(".cart-content").stop().fadeIn();},
	function(){$(".cart-btn").stop().animate({width:'90px'},100);$(".cart-content").stop().fadeOut();}
);

$(".social").hover(
	function(){$(".social-content").stop().fadeIn();},
	function(){$(".social-content").stop().fadeOut();}
);



$('.btn-act').each(
	function(){
	$(this).hover(
		
		function(){
			$(this).find('div.animate-me').stop().animate({'height': '100%'},200);
			$(this).find('div.animate-me img').stop().fadeIn();
			$(this).find('div.btn-buy').stop().delay(150).fadeIn(400);
			$(this).find('div.btn-view').stop().delay(350).fadeIn(400);

		},
	    function(){
		$(this).find('div.animate-me img').stop().fadeOut();
			$(this).find('div.animate-me').stop().animate({'height':'0%'});
			$(this).find('div.btn-buy').stop().fadeOut(10);
			$(this).find('div.btn-view').stop().fadeOut(10);
		}
	);
}); 
 


/**currencies**/
$(".box-currencies").hover(
	function(){$(".cur-content").stop().fadeIn();},
	function(){$(".cur-content").stop().fadeOut();}
);
/**currencies**/
$(".box-languages").hover(
	function(){$(".lang-content").stop().fadeIn();},
	function(){$(".lang-content").stop().fadeOut();}
);


/******** Navigation Menu ********/
$('.menu > span').click(function () {
	  $(this).toggleClass("active");  
	  $(this).parent().find("> ul").slideToggle('medium');
	});

$('.menu.m-menu > ul > li.categories > div > .megamenu-column > div').before('<span class="more"></span>');
		$('span.more').click(function () {
			$(this).next().slideToggle('fast');
			$(this).toggleClass('plus');
			
});
							
/********Category Accordion **********/
	$('#cat_accordion').cutomAccordion({
		classExpand : 'custom_id20',
		menuClose: false,
		autoClose: true,
		saveState: false,
		disableLink: false,		
		autoExpand: true
	});


	
$(window).scroll(function () {
if ($(this).scrollTop() > 100) {
	$('.backtop').fadeIn();
	$('.head-one').stop().animate({top:'-30px'},300);
	$('.cont-head').stop().animate({'padding-top':'0px'},100);
} 
else 
{	
	$('.backtop').fadeOut();
	$('.head-one').stop().animate({top:'0px'},300);
	$('.cont-head').stop().animate({'padding-top':'20px'},100);

}
});$('.backtop').click(function () {$("html, body").animate({scrollTop: 0}, 1000);return false;});



//$('.head-one').hover(
	//function(){$(this).stop().animate({top:'0px'},500)},
	//function(){$(this).stop().animate({top:'-28px'},500);}
//);

	
	
	
$(".shop-cart").hover(
	function(){$(".shop-cart-content").stop().fadeIn();},
	function(){$(".shop-cart-content").stop().fadeOut();}
);	
$(".shop-user").hover(
	function(){$(".shop-user-content").stop().fadeIn();},
	function(){$(".shop-user-content").stop().fadeOut();}
);		
$(".shop-search").hover(
	function(){$(".shop-search-content").stop().fadeIn();},
	function(){$(".shop-search-content").stop().fadeOut();}
);	
$(".btn-langu").hover(
	function(){$(".langu-content").stop().fadeIn();},
	function(){$(".langu-content").stop().fadeOut();}
);		
	
	
});

$("#tabs").show();
!function( $ ){

  "use strict"

 /* TAB CLASS DEFINITION
  * ==================== */

  var Tab = function ( element ) {
    this.element = $(element)
  }

  Tab.prototype = {

    constructor: Tab

  , show: function () {
      var $this = this.element
        , $ul = $this.closest('ul:not(.dropdown-menu)')
        , selector = $this.attr('data-target')
        , previous
        , $target

      if (!selector) {
        selector = $this.attr('href')
        selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
      }

      if ( $this.parent('li').hasClass('active') ) return

      previous = $ul.find('.active a').last()[0]

      $this.trigger({
        type: 'show'
      , relatedTarget: previous
      })

      $target = $(selector)

      this.activate($this.parent('li'), $ul)
      this.activate($target, $target.parent(), function () {
        $this.trigger({
          type: 'shown'
        , relatedTarget: previous
        })
      })
    }

  , activate: function ( element, container, callback) {
      var $active = container.find('> .active')
        , transition = callback
            && $.support.transition
            && $active.is('.fade, .slide')

      function next() {
        $active
          .removeClass('active in out')
          .find('> .dropdown-menu > .active')
          .removeClass('active')

        element.addClass('active')

        if (transition) {
            element[0].offsetWidth // reflow for transition
            element.removeClass('out').addClass('in')
        } else {
          element.removeClass('fade slide')
        }

        if ( element.parent('.dropdown-menu') ) {
          element.closest('li.dropdown').addClass('active')
        }

        callback && callback()
      }

      transition ?
        $active.one($.support.transition.end, next) :
        next();

      $active.removeClass('in').addClass('out');
    }
  }


 /* TAB PLUGIN DEFINITION
  * ===================== */

  $.fn.tab = function ( option ) {
    return this.each(function () {
      var $this = $(this)
        , data = $this.data('tab')
      if (!data) $this.data('tab', (data = new Tab(this)))
      if (typeof option == 'string') data[option]()
    })
  }

  $.fn.tab.Constructor = Tab


 /* TAB DATA-API
  * ============ */

  $(function () {
    $('body').on('click.tab.data-api', '[data-toggle="tab"], [data-toggle="pill"]', function (e) {
      e.preventDefault()
      $(this).tab('show')
    })
  })

}( window.jQuery );
 
/*end here */

