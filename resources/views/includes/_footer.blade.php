<section class="footer-section">
	<div class="container">
    	<div class="row">
        	<div class="text-center">
            	&copy; <?php echo date('Y')?> Innovative-net.com |<a href="http://www.innovative-net.com/" target="_blank"  > Designed by : innovative-net.com</a>
			</div>
		</div>
	</div>
</section>

<script>
$(document).ready(function() {
    var scrollTop = 0;
    $(window).scroll(function() {
        scrollTop = $(window).scrollTop();
        $('.counter').html(scrollTop);
        if (scrollTop >= 70) {
            $('.erp-menus').addClass('scrolled-nav');
        } else if (scrollTop < 70) {
            $('.erp-menus').removeClass('scrolled-nav');
        }
    });
    $(".mega-dropdown .dropdown-toggle").hover(function() {
        $(".mega-dropdown .dropdown-toggle").removeClass("active-erp");
        $(this).addClass("active-erp");
    });
    $("#nav li a").click(function() {
        $("#changeStyle").attr("href", $(this).attr('rel'));
        return !1;
    });

$("#changeStyle.changeme").attr("href", $(this).attr('rel'));
$(document).ready(function() {
    if ($.cookie("css")) {
        $("#changeStyle").attr("href", $.cookie("css"));
    }
    $("#nav li a").click(function() {
        $("#changeStyle").attr("href", $(this).attr('rel'));
        $.cookie("css", $(this).attr('rel'), {
            expires: 365,
            path: '/'
        });
        return !1;
    });
});
if ($.cookie("css")) {
    $("#changeStyle").attr("href", $.cookie("css"));
}
$(document).ready(function() {
    $("#nav li a").click(function() {
        $("#changeStyle").attr("href", $(this).attr('rel'));
        $.cookie("css", $(this).attr('rel'), {
            expires: 365,
            path: '/'
        });
        return !1;
    });
});
$('ul.nav li.dropdown').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(100);
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(100);
});

});
</script>