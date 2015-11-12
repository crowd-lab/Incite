<!DOCTYPE html>
<html lang="en">

<?php
include(dirname(__FILE__).'/../common/header.php');
//$this->transcription must exist because controller has ensured it. If it doesn't exist, then controller should've redirected it to the right place!
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-md-8">
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-default">Subscribe</button>
                <button type="button" class="btn btn-default">Guide</button>
            </div>
        </div>
        <div class="container">
            <div class="col-md-6">
                <div style="position: fixed; width: 35%;">
                    <div>Title: <?php echo metadata($this->    transcription, array('Dublin Core', 'Title')); ?></div>
                    <div>Date: 11/12/1855</div>
                    <div>Location:</div>
                    <div>Description:</div>
                    <div>Author:</div>
                    <div class="wrapper">
                        <div id="viewer2" class="viewer"></div>
<!--                        <img src="<?php echo $this->transcription->getFile()->getProperty('uri'); ?>" alt="<?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?>">
-->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <form method="post">
                    <textarea name="transcription" rows="40" style="width: 100%;">Transcription</textarea>
                    <textarea name="transcription" rows="5" style="width: 100%;">Summary</textarea>
                    <button type="submit" class="btn btn-default">Done</button>
                </form>
            </div> 
        </div>




  
</div>

    </div>
    <!-- /.container -->

    <script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover({ trigger: "hover" });
});
</script>

<script type="text/javascript">
            var $ = jQuery;
            $(document).ready(function(){

                  var iv2 = $("#viewer2").iviewer(
                  {
                      src: "<?php echo $this->transcription->getFile()->getProperty('uri'); ?>"
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
