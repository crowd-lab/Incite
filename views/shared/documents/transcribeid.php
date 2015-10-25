<!DOCTYPE html>
<html lang="en">

<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();
include(dirname(__FILE__).'/../common/header.php');
//$this->transcription must exist because controller has ensured it. If it doesn't exist, then controller should've redirected it to the right place!
?>

    <!-- Page Content -->
    <div class="container">
        <div class="second header">
            <!-- Primary Navbar -->
            <div class="navbar navbar-inverse" style="margin-right:-40px;">
              <div class="container">
                <div class="navbar-header">
                  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
                  <a class="navbar-brand" href="#"><?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?></a>
                </div>
                <div class="nav navbar-nav navbar-right"> 
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="transcription_document.html">Transcribe</a></li>
                        <li><a href="#about">Menu Item 1</a></li>
                        <li><a href="#contact">Menu Item 2</a></li>
                        <li><a href="#about">Menu Item 3</a></li>
                    </ul>
                </div> 
              </div>
            </div>
        </div>

        <div class="row">
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="30"
                    aria-valuemin="0" aria-valuemax="100" style="width:30%">
                    30%
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-default">Subscribe</button>
                <button type="button" class="btn btn-default">Guide</button>
            </div>
        </div>
        <div class="container">
            <div class="col-md-8">
                <img src="<?php echo $this->transcription->getFile()->getProperty('uri'); ?>" alt="<?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?>" style="max-width:604px;max-height:800px;">
				            </div>
            <div class="col-md-4">
                <br>
                <div class="col-md-6">
                    <input type="text" name="FirstName" value="Tombstone"><br>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-default">Revise</button>
                </div>
				<form method="post">
					<textarea name="transcription" cols="70" rows="20">Transcription</textarea>
					<textarea name="summary" cols="70" rows="10">Summary</textarea>
					<button type="submit" class="btn btn-default">Done</button>
				</form>
            </div> 
        </div>




  
</div>

    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
});
</script>

</body>

</html>
