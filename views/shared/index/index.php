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
				   <img src="/m4j/plugins/Incite/views/shared/images/Transcribe.png">
				   <div class="carousel-caption">
					<h3>Transcribe</h3>
					<p>Introduction to transcribe task.</p>
				  </div>
				</div><!-- End Item -->
				
				<div class="item">
				  <img src="/m4j/plugins/Incite/views/shared/images/Tag.png">
				   <div class="carousel-caption">
					<h3>Tag</h3>
					<p>Intro to Tag task. </p>
				  </div>
				</div><!-- End Item -->
				
				<div class="item">
				  <img src="/m4j/plugins/Incite/views/shared/images/Connect.png">
				   <div class="carousel-caption">
					<h3>Connect</h3>
					<p>Introduction to connect task.</p>
				  </div>
				</div><!-- End Item -->
						
			  </div><!-- End Carousel Inner -->


				<ul class="nav nav-pills nav-justified">
				  <li data-target="#myCarousel" data-slide-to="0" class="active"><a href="#">Transcribe<small>Searchable texts</small></a></li>
				  <li data-target="#myCarousel" data-slide-to="1"><a href="#">Tag<small>Find entities</small></a></li>
				  <li data-target="#myCarousel" data-slide-to="2"><a href="#">Connect<small>Concepts of documents</small></a></li>
				</ul>


			</div><!-- End Carousel -->

			<div class="row"> 
				<div class="col-lg-12 text-center" style="margin-bottom: 30px;">
					<h2></h2>

					<p style="margin-bottom: 30px; margin-top: 50px;">Based on the available information, we have found some documents you might be interested in. Click "Search" to start when you are ready. Or you can tell us what you are interested in by changing the pre-populated information and click "Search".</p>
					<!-- <a class="btn" href="" style="" id="large-btn">Get Started</a> -->
				</div> 
			</div>

		</div>

    </div>


<?php 

//print_r(metadata($this->Item, array('Dublin Core', 'Subject'), array('all' => true))); 
//echo $this->Item;

?>

  
</div>

    </div>
    <!-- /.container -->

    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });

	$('#myCarousel').carousel({
		interval:   4000
	});
	
	var clickEvent = false;
	$('#myCarousel').on('click', '.nav a', function() {
			clickEvent = true;
			$('.nav li').removeClass('active');
			$(this).parent().addClass('active');		
	}).on('slid.bs.carousel', function(e) {
		if(!clickEvent) {
			var count = $('#myCarousel > .nav').children().length -1;
			var current = $('.nav li.active');
			current.removeClass('active').next().addClass('active');
			var id = parseInt(current.data('slide-to'));
			if(count == id) {
				$('#myCarousel > .nav li').first().addClass('active');	
			}
		}
		clickEvent = false;
	});

});
</script>

</body>

</html>
