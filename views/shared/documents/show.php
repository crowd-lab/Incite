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
            <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                <h1>Welcome to Mapping The Fourth!</h1>
                <p class="lead"> Help us better understand the history of our country.</p>

                <p style="margin-bottom: 20px;"> The long crisis of the Civil War, stretching from the 1840s to the 1870s, forced Americans to confront difficult questions about the meaning and the boundaries of their nation. What did it mean to be an American? Who was included and excluded? Where did the nation’s borders lie? But it was on one particular day each year—July 4—that they left the most explicit evidence of their views. In newspapers and speeches, in personal diaries and letters to their friends and family, Americans gave voice to typically unspoken beliefs about national identity.</p>
                <!-- <a class="btn" href="" style="" id="large-btn">Get Started</a> -->
            </div> 
        </div>

        <div class="row">
            <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                <p>
                    We found a few documents close to <a> Blacksburg</a>. Or You can tell us anything you have in mind about civil war below and see what we can find for you! 
                </p>
                <form class="form-wrapper" >
                    <input type="text" id="search" placeholder="Search for Interesting Places!" required>
                    <input type="submit" value="Search" id="submit">
                </form>  
            </div>
        </div>

        <div class="row">
            <div>
                <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
                    <h2> Here are a list of projects that others are working on!</h2>
                </div>
                <p style="margin-left:1em">Sort by: <a href="">completion</a>-<a href="">types</a>-<a href="">time</a>-<a href="">last updated</a> </p>
            </div>
        </div>

<div class="row">
    <div class="col-lg-2 col-sm-3 col-xs-4">
        <a href="#" data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">
             <img src="http://www.placecage.com/200/200" class="thumbnail img-responsive">
        </a>
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                <span class="sr-only">45% Complete</span>
            </div>
        </div>
    </div>
    


<?php 

//print_r(metadata($this->Item, array('Dublin Core', 'Title'), array('all' => true))); 
//echo $this->Item;

?>

  
</div>

    </div>
    <!-- /.container -->

    <script>
$(document).ready(function(){

	$.ajax({
		  url: 'http://localhost/m4j/incite/ajax/login',
		  type: 'POST',
		  data: {username:"test", password:"no"},
		  success: function(data) {
			//called when successful
			alert(data);
		  },
		  error: function(e) {
			//called when there is an error
			console.log(e.message);
		  }
		});

});
</script>

</body>

</html>
