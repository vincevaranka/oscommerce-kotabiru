
$(document).ready(function(){


function onMenu()
{
	$("#column-menu").animate({left:'150px',display:'none'});
		$("#content").animate({'margin-left':'220px'});
		$("#adminAppMenu div").removeClass('cssmenu2');
		$("#adminAppMenu div").addClass('cssmenu1');
		$(".text-menu").show();
		$(".has-sub i").hide();
		$(".menu-toogle").show();
		$(".menu-toogle-on").hide();
		
}
function offMenu(){
	$("#column-menu").animate({left:'0px',display:'block'});
		$("#content").animate({'margin-left':'50px'});
		$("#adminAppMenu div").removeClass('cssmenu1');
		$("#adminAppMenu div").addClass('cssmenu2');
		$(".text-menu").hide();
		$(".has-sub i").show();
		$(".menu-toogle").hide();
		$(".menu-toogle-on").show();
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}
var tog = getCookie("tog");
if(tog == '1') { offMenu(); } 
else if(tog=='0'){ onMenu();} 
else {
	if (screen.width <= 800) {
		offMenu();
		} else {
		onMenu();
		}
}

$(".menu-toogle").click(
	function(){
		offMenu();
		if(screen.width >= 800){
		document.cookie="tog=1";}
		
	}
);
$(".menu-toogle-on").click(
function(){
		onMenu();
		if(screen.width >= 800){
		document.cookie="tog=0";}
		location.reload();
	}
);

$(".btn-menu-side").click(function(){
	 $('.open').removeClass('open');

});

$('.cssmenu1 > ul > li > a').click(function() {
  $('.cssmenu1 li').removeClass('active');
  $(this).closest('li').addClass('active');	
  var checkElement = $(this).next();
  if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
    $(this).closest('li').removeClass('active');
    checkElement.slideUp('normal');
  }
  if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
    $('.cssmenu1 ul ul:visible').slideUp('normal');
    checkElement.slideDown('normal');
  }
  if($(this).closest('li').find('ul').children().length == 0) {
    return true;
  } else {
    return false;	
  }		
});

$(".menu-shop").each(function(){
	$(this).hover(
		function(){
			$(this).children('div').stop().fadeIn();
		},
	    function(){
			$(this).children('div').stop().fadeOut();
		}
	);
});


});



