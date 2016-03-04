<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>


    <!-- Page Content -->
    <div class="container">

        <!-- /.row -->

        <div class="row"> 
        	<div class="jumbotron">
                <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                    <h1>Welcome to Mapping The Fourth!</h1>
                    <p class="lead"> Help us better understand the history of our country.</p>

                    <p style="margin-bottom: 20px;"> The long crisis of the Civil War, stretching from the 1840s to the 1870s, forced Americans to confront difficult questions about the meaning and the boundaries of their nation. What did it mean to be an American? Who was included and excluded? Where did the nation’s borders lie? But it was on one particular day each year—July 4—that they left the most explicit evidence of their views. In newspapers and speeches, in personal diaries and letters to their friends and family, Americans gave voice to typically unspoken beliefs about national identity.</p>
                    <!-- <a class="btn" href="" style="" id="large-btn">Get Started</a> -->
                </div> 
                <div class="row"> 
                    <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                        <h2>How You Can Help</h2>
                        <p class="lead">There are three main tasks you can choose to contribute.</p>
                        <!-- <a class="btn" href="" style="" id="large-btn">Get Started</a> -->
                    </div> 
                </div>
        	</div>
        </div>
    </div>

<style>
body { padding-top: 60px; 
	   padding-bottom: 60px;
}
#myCarousel .nav a small {
    display:block;
}
#myCarousel .nav {
	background:#eee;
}
#myCarousel .nav a {
    border-radius:0px;
}
</style>
	<div class="container">
		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			
			<!-- Wrapper for slides -->
			<div class="carousel-inner">
			  
				<div class="item active">
					<a href="<?php echo getFullInciteUrl(); ?>/documents/transcribe">
				   		<img src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/Transcribe.png">

				    	<div class="carousel-caption">
							<h3>Transcribe</h3>
							<p>Introduction to transcribe task.</p>
				  		</div>
				  	</a>
				</div><!-- End Item -->
				
				<div class="item">
					<a href="<?php echo getFullInciteUrl(); ?>/documents/tag">
						<img src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/Tag.png">
					
						<div class="carousel-caption">
							<h3>Tag</h3>
							<p>Intro to Tag task. </p>
					  	</div>
				  	</a>
				</div><!-- End Item -->
				
				<div class="item">
					<a href="<?php echo getFullInciteUrl(); ?>/documents/connect">
				  		<img src="<?php echo getFullOmekaUrl(); ?>/plugins/Incite/views/shared/images/Connect.png">
				  	
					   	<div class="carousel-caption">
							<h3>Connect</h3>
							<p>Introduction to connect task.</p>
					  	</div>
					</a>
				</div><!-- End Item -->
						
			</div><!-- End Carousel Inner -->


			<ul class="nav nav-pills nav-justified">
				<li data-target="#myCarousel" class="carouselNavLi active" data-slide-to="0"><a href="#">Transcribe<small>Searchable texts</small></a></li>
				<li data-target="#myCarousel" class="carouselNavLi" data-slide-to="1"><a href="#">Tag<small>Find people, locations and organizations</small></a></li>
				<li data-target="#myCarousel" class="carouselNavLi" data-slide-to="2"><a href="#">Connect<small>Concepts of documents</small></a></li>
			</ul>

		</div><!-- End Carousel -->

			<div class="row"> 
				<div class="col-lg-12 text-center" style="margin-bottom: 30px;">
					<h2></h2>

					<p style="margin-bottom: 30px; margin-top: 50px;">Based on the available information, we have found some search criteria for discovering documents you might be interested in. Click "Discover" to start when you are ready. Or you can tell us what you are interested in by changing the pre-populated information and click "Discover".</p>
                    <button class="btn btn-primary" onclick="$('#navbar_search_button').click();">Surprise Me!</button>
					<!-- <a class="btn" href="" style="" id="large-btn">Get Started</a> -->
				</div> 
			</div>

		</div>
    </div>
</div>

    </div>
    <!-- /.container -->

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
