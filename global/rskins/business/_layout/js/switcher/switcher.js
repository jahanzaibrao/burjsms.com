$(document).ready(function () {

    var styleswitcherstr = ' \
		<h4>Style Switcher <a href="#"><i class="fa fa-arrows"></i></a></h4> \
		<div class="content"> \
		<h5>Choose Layout Style</h5> \
		<div class="layout-switcher fixed"> \
		<a id="wide" class="layout">Wide</a> \
		<a id="boxed" class="layout">Boxed</a> \
		</div> \
		<h5>Choose Skin Color</h5> \
		<div class="switcher-box fixed"> \
		<a id="default" class="styleswitch color"></a> \
		<a id="green" class="styleswitch color"></a> \
		<a id="orange" class="styleswitch color"></a> \
		</div><!-- End switcher-box --> \
		<h5>Patterns for Boxed</h5>  \
		<div class="fixed"> \
		<a id="bg" class="pattern"></a> \
		<a id="bg2" class="pattern"></a> \
		<a id="bg3" class="pattern"></a> \
		<a id="bg4" class="pattern"></a> \
		<a id="bg5" class="pattern"></a> \
		<a id="bg6" class="pattern"></a> \
		<a id="bg7" class="pattern"></a> \
		<a id="bg8" class="pattern"></a> \
		<a id="bg9" class="pattern"></a> \
		<a id="bg10" class="pattern"></a> \
		<a id="bg11" class="pattern"></a> \
		<a id="bg12" class="pattern"></a> \
		<a id="bg13" class="pattern"></a> \
		<a id="bg14" class="pattern"></a> \
		<a id="bg15" class="pattern"></a> \
		<a id="bg16" class="pattern"></a> \
		<a id="bg17" class="pattern"></a> \
		<a id="bg18" class="pattern"></a> \
		<a id="bg19" class="pattern"></a> \
		<a id="bg20" class="pattern"></a> \
		</div> \
		<br> \
		</div><!-- End content --> \
		';

    $(".switcher").prepend(styleswitcherstr);

});

/* Template Layout  */

$(document).ready(function () {

    var cookieName = 'template-layout';

    function changeLayout(layout) {
        $.cookie(cookieName, layout);
        document.location.reload();
    }

    $("#wide").click(function () {
        changeLayout('wide');
    });

    $("#boxed").click(function () {
        changeLayout('boxed');
    });

});


/* Template Background */

$(document).ready(function () {


    $(".pattern").click(function () {
        var id = $(this).attr('id');
		
		// remove previous bg classes applied to body
		$('body').removeClass(function(i, c)
		{
			return c.match(/\S*bg\S*/g) ? c.match(/\S*bg\S*/g).join(' ') : false;
		});
		
		$("body").addClass(id);
        $.cookie('template-bg', id);
    });

});

/* Template Skins */

$(document).ready(function () {

    var cookieName = 'template-skin';

    function changeSkin(skin) {
        $.cookie(cookieName, skin);
        document.location.reload();
    }

    $("#default").click(function () {
        changeSkin('default');
    });
    $("#green").click(function () {
        changeSkin('green');
    });
	$("#orange").click(function () {
        changeSkin('orange');
    });

});

/* Switcher */

$(document).ready(function () {
	
    $('.switcher').animate({
        left: '-200px'
    });

    $('.switcher h4 a').click(function (e) {
        e.preventDefault();
        var div = $('.switcher');
        if (div.css('left') === '-200px') {
            $('.switcher').animate({
                left: '20px'
            }, 300);
        } else {
            $('.switcher').animate({
                left: '-200px'
            }, 300);
        }
    });

});