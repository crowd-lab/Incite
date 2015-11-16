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
            <div class="col-md-6" id="work-zone">
                <div style="position: fixed; width: 35%;" id="work-view">
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

                <div id="container">
                    <h3> Discussion </h3>
                    <ul id="comments">
                        <li class="cmmnt">
<!--                            <div class="avatar"><a href="javascript:void(0);"><img src="images/dark-cubes.png" width="55" height="55" alt="DarkCubes photo avatar"></a></div>
-->
                            <div class="cmmnt-content">
                                <header><a href="javascript:void(0);" class="userlink">DarkCubes</a> - <span class="pubdate">posted 1 week ago</span></header>
                                <p>Ut nec interdum libero. Sed felis lorem, venenatis sed malesuada vitae, tempor vel turpis. Mauris in dui velit, vitae mollis risus. Cras lacinia lorem sit amet augue mattis vel cursus enim laoreet. Vestibulum faucibus scelerisque nisi vel sodales. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis pellentesque massa ac justo tempor eu pretium massa accumsan. In pharetra mattis mi et ultricies. Nunc vel eleifend augue. Donec venenatis egestas iaculis.</p>
                            </div>
                        </li>
                    </ul>
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted **/): ?>
                    <textarea name="transcribe_text" cols="60" rows="10" id="comment">Your comment</textarea>
                    <button type="button" class="btn btn-default" id="">Submit</button>
<?php else: ?>
                    Please login or signup to join the discussion!
                </div>
<?php endif; ?>
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
		$('#work-view').width($('#work-zone').width());

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
