<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>


<!-- Page Content -->
<div class="container">


  <div class="row">
    <div class="jumbotron">
      <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
        <h1>Welcome to Mapping The Fourth!</h1>
        <p class="lead"> Help us better understand the history of our country.</p>

        <p style="margin-bottom: 20px;"> </p>
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


</body>

</html>
