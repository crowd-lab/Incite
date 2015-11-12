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
            <div class="col-md-8">
            </div>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-default">Subscribe</button>
                <button type="button" class="btn btn-default">Guide</button>
            </div>
        </div>
        <div class="container">
            <div class="col-md-6">
            
                <div style="position: fixed; width: 35%;">
                    <textarea name="transcribe_text" rows="20" id="transcribe_copy" style="width: 100%;"><?php print_r($this->transcription); ?></textarea>
                        <div class="wrapper">
                            <div id="document_img" class="viewer"></div>
                        </div>
                    <button type="button" class="btn btn-default" id="show">Document</button>
                    <button type="button" class="btn btn-default" id="hide">Transcription</button>
                </div>
            </div>
            <div class="col-md-6">
				<table class="table">
					<tr><th>Entities</th><th>Category</th><th>Subcategory</th><th>Details</th></tr>
					<tr><td><input type="text" class="form-control" value="Richmond"></td><td><input class="form-control" type="text" value="Place"></td><td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td></tr>
					<tr><td>Jetersville</td><td>Place</td><td><input type="text"></td><td><input type="text"></td></tr>
					<tr><td>Amelia Springs</td><td>Place</td><td><input type="text"></td><td><input type="text"></td></tr>
					<tr><td>R.E. Hughson</td><td>People</td><td><input type="text"></td><td><input type="text"></td></tr>
				</table>
				<button type="button" class="btn btn-primary">Add more</button>
<!--
                <p>Are these the same as the following entities? </p>
                <form role="form">
                    <label class="radio-inline">
                      <input type="radio" name="optradio">George Washington
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="optradio">Washington State
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="optradio">Not an Entity
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="optradio">No
                    </label>
                </form>

            	<form role="form" method="post">
					<p>Please type the tag name in the following text box:</p>
						  <input type="text" name="tag_text">
					<br />
					<p>Please choose what type the tag is:</p>
                    <label class="radio-inline">
                      <input type="radio" name="tag_category" value="People">People
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tag_category" value="Places">Places
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tag_category" value="Organizations">Organizations
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tag_category" value="Events">Events
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tag_category" value="Ideas">Ideas
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="tag_category" value="Time">Time/Date
                    </label>
                	<textarea name="tag_description" cols="60" rows="15" id="tag_description">Details</textarea>
                	<button type="submit" class="btn btn-default">Submit</button>
                </form>
-->
            </div> 
        </div>




  
</div>

    </div>
    <!-- /.container -->

    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
        $("#document_img").hide();
    $("#hide").click(function(){
        $("#document_img").hide();
        $("#transcribe_copy").show();
    });
    $("#show").click(function(){
        $("#document_img").show();
        $("#transcribe_copy").hide();
    });
});
</script>
<script type="text/javascript">
            var $ = jQuery;
            $(document).ready(function(){

                  var iv2 = $("#document_img").iviewer(
                  {
                      src: "<?php echo $this->tag->getFile()->getProperty('uri'); ?>"
                  });

            });
            $('.viewer').height($(window).height()*68/100);
        </script>
<style>
            .viewer
            {
                width: 100%;
                border: 1px solid black;
                position: relative;
            }
            
            .wrapper
            {
                overflow: hidden;
            }
        </style>

</body>

</html>
