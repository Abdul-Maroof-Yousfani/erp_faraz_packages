$(document).ready(function(){
    
$(".Navclose").click(function(){
    $(".sidenavnr").toggleClass("Navactive");
    $("body").toggleClass("full_with");
   
  });



$(".tmenu-list").hover(function () {
      $(this).toggleClass("active");
    });
$(".settingListSb").click(function () {
      $(this).toggleClass("active");
    });

$(".settingListSb-subItem").click(function () {
      $(this).toggleClass("active");
     
    });

$(".dd").click(function () {
      $(this).find('.collapsee').slideToggle();
  });

$(".sm-bx").click(function () {
       $(".sidenavnr").removeClass("Navactive");
    $("body").removeClass("full_with");

    });


 var $myGroup = $('#myGroup');
$myGroup.on('show.bs.collapse','.collapse', function() {
    $myGroup.find('.collapse.in').collapse('hide');
});



  // sticky footer

     $(window).scroll(function() {
    if ($(this).scrollTop() > 100){  
        $('.footer-section').addClass("active");
    }
    else{
        $('.footer-section').removeClass("active");
    }
});

// end
});


var btn = $('#button');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});      
