<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>

    <style>
        .carousel-inner {
            height: 150px;
        }

        .carousel-control {
            top: 20%;
        }

        .carousel-control.left, .carousel-control.right {
            background: none;
            color: @red;
            border: none;
        }

        .carousel-control.left {
            margin-left: -45px; color: black;
        }

        .carousel-control.right {
            margin-right: -45px; color: black;
        }
    </style>


    <div id="homepage-content" style="margin-top: 25px; margin-left: 15%; margin-right:15%; margin-bottom: 25px;">
        <div id="homepage-summary" style="margin-bottom: 75px">
            <h1>Mapping the Fourth uses the power of crowdsourcing to rediscover how Independence Day was celebrated during     the Civil War era.</h1>
        </div>  <!-- homepage-summary -->
        <div id="homepage-carousel" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="item active">
                    <a href=""><div style="display:inline-block; width: 33%;" data-target="#homepage-carousel" data-slide-to    ="1"><img style="max-height: 120px; margin: auto; display: block;" src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/transcribe.png"></div></a>
                    <a href=""><div style="display:inline-block; width: 33%;" data-target="#homepage-carousel" data-slide-to    ="2"><img style="max-height: 120px; margin: auto; display: block;" src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/tag.png"></div></a>
                    <a href=""><div style="display:inline-block; width: 33%;" data-target="#homepage-carousel" data-slide-to    ="3"><img style="max-height: 120px; margin: auto; display: block;" src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/connect.png"></div></a>
                </div>
                <div class="item">
                    <div style="display:inline-block; width: 33%; float: left;"><img style="max-height: 120px; margin: auto; display: block;" src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/transcribe.png"></div>
                    <div style="display:inline-block; width: 60%; position: relative;">
                        <div style="">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis e    gestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas     semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra.
                        </div>
                        <div style="position: absolute; top: 100px; width: 100%; margin-left: 40%;">
                            <a href=""><button style="margin: 0 auto;" class="btn btn-danger">TRY IT NOW</button></a>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div style="display:inline-block; width: 33%; float: left;"><img style="max-height: 120px; margin: auto; display: block;" src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/tag.png"></div>
                    <div style="display:inline-block; width: 60%; position: relative;">
                        <div style="">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis e    gestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas     semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra.
                        </div>
                        <div style="position: absolute; top: 100px; width: 100%; margin-left: 40%;">
                            <a href=""><button style="margin: 0 auto;" class="btn btn-danger">TRY IT NOW</button></a>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div style="display:inline-block; width: 33%; float: left;"><img style="max-height: 120px; margin: auto; display: block;" src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/connect.png"></div>
                    <div style="display:inline-block; width: 60%; position: relative;">
                        <div style="">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis e    gestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas     semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra.
                        </div>
                        <div style="position: absolute; top: 100px; width: 100%; margin-left: 40%;">
                            <a href=""><button style="margin: 0 auto;" class="btn btn-danger">TRY IT NOW</button></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Carousel controls -->
            <a class="carousel-control left" href="#homepage-carousel" data-slide="prev" style="font-size: 450%">
                &lsaquo;
            </a>
            <a class="carousel-control right" href="#homepage-carousel" data-slide="next" style="font-size: 450%">
                &rsaquo;
            </a>
        </div> <!-- homepage-carousel -->
        <br>
        <div id="homepage-details" style="margin-top: 50px;">
            <h3 class="m4j-main-text-color">WE ARE UNFOLDING AMERICAN HISTORY ONE DOCUMENT AT A TIME</h3>
            <img src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/doc-icon.jpg" style="float: left; margin-right: 20px; max-width: 120px;">
            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tort    or quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricie    s mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, con    dimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orc    i, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id curs    us faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis,     accumsan porttitor, facilisis luctus, metus</p>
            <div style="clear:left"></div>
            <br>
            <h3 class="m4j-main-text-color" style="clear: left;">POWERED BY INCITE</h3>
            <img src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/m4j-brand.png" style="float: left; margin-right: 20px; max-width: 120px;">
            <p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tort    or quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricie    s mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, con    dimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orc    i, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id curs    us faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis,     accumsan porttitor, facilisis luctus, metus</p>
        </div>   <!-- homepage-details -->
    </div> <!-- homepage-content -->


    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
    var d = new Date();
    var mon = (d.getMonth()+1) < 10 ? ("0"+(d.getMonth()+1)) : (d.getMonth()+1);
    var dat = (d.getDate() < 10) ? ("0"+d.getDate()) : d.getDate();
    //var today = ''+(1830+(Math.floor(Math.random()*(1870-1830))+1))+'-'+mon+'-'+dat;
    var today = '1860-'+mon+'-'+dat;
    var d = new Date();
    d.setMonth(d.getMonth()-1);
    var a_month_ago = (d.getMonth()+1) < 10 ? ("0"+(d.getMonth()+1)) : (d.getMonth()+1);
    var start_year = d.getFullYear();
    d = new Date();
    var a_month_after = (d.getMonth()+2) < 10 ? ("0"+(d.getMonth()+2)) : (d.getMonth()+2);
    var end_year = d.getFullYear();
    var random_year = 1860;
    $('#time_picker2').daterangepicker({
        locale     : { format: 'YYYY-MM-DD'},
        "startDate": '1855-12-01',
        "endDate"  : '1865-2-28',
        "minDate"  : "1830-01-01",
        "maxDate"  : "1870-12-31",
        "opens"    : "center"
        }, 
        function (start, end, label) {
        });
	

	var hoverEvent = false;

	var slideIndex = {
		TRANSCRIBE: 0,
		TAG: 1,
		CONNECT: 2
	};

	$('#myCarousel').carousel({
		interval:   4000
	});

	$('#myCarousel').on('mouseenter', '.nav a', function() {
		hoverEvent = true;
		$('.nav li').removeClass('active');
		$(this).parent().addClass('active');

		var slideNumber = $(this).parent()[0].getAttribute('data-slide-to');
		$("#myCarousel").carousel(parseInt(slideNumber));
	}).on('click', '.nav a', function() {
		var slideNumber;

		slideNumber = $(this).parent()[0].getAttribute('data-slide-to');
		slideNumber = parseInt(slideNumber);

		if (slideNumber === slideIndex.TRANSCRIBE) {
			window.location = '<?php echo getFullInciteUrl(); ?>/documents/transcribe';
		} else if (slideNumber === slideIndex.TAG) {
			window.location = '<?php echo getFullInciteUrl(); ?>/documents/tag';
		} else if (slideNumber === slideIndex.CONNECT) {
			window.location = '<?php echo getFullInciteUrl(); ?>/documents/connect';
		}
	}).on('slid.bs.carousel', function(e) {
		if (!hoverEvent) {
			var count = $('#myCarousel > .nav').children().length -1;

			var current = $('.carouselNavLi.active');

			current.removeClass('active').next().addClass('active');
			var id = parseInt(current.data('slide-to'));
			if(count == id) {
				$('#myCarousel > .nav li').first().addClass('active');	
			}
		}
		hoverEvent = false;
	});
});
</script>

</body>

</html>
