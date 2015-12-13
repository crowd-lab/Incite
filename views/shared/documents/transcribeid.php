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
                    <div>Title: <?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?></div>
                    <div>Date: <?php echo metadata($this->transcription, array('Dublin Core', 'Date')); ?></div>
                    <div>Location: <?php echo metadata($this->transcription, array('Item Type Metadata', 'Location')); ?></div>
                    <div>Description: <?php echo metadata($this->transcription, array('Dublin Core', 'Description')); ?></div>
                    <div class="wrapper">
                        <div id="viewer2" class="viewer"></div>
<!--                        <img src="<?php echo $this->transcription->getFile()->getProperty('uri'); ?>" alt="<?php echo metadata($this->transcription, array('Dublin Core', 'Title')); ?>">
-->
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="submit-zone">
                <form method="post" id="transcribe-form">
                    <textarea name="transcription" rows="40" style="width: 100%;" placeholder="Transcription"></textarea>
                    <textarea name="summary" rows="5" style="width: 100%;" placeholder="Summary"></textarea>
                    <div class="form-group">
                        <label class="control-label">Tone of the document:</label>
                        <select class="form-control" name="tone">
                            <option value=""></option>
                            <option value="anxiety">Anxiety</option>
                            <option value="optimism">Optimism</option>
                            <option value="sarcasm">Sarcasm</option>
                            <option value="pride">Pride</option>
                            <option value="aggression">Aggression</option>
                        </select>
                    </div>
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
                                <?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted **/): ?>
                                <button type="button" class="btn btn-default" id="">Reply</button>
                                <?php endif; ?>
                            </div>
                        </li>
                    </ul>
<?php if (isset($_SESSION['Incite']['IS_LOGIN_VALID']) && $_SESSION['Incite']['IS_LOGIN_VALID'] == true /** && is_permitted **/): ?>
                    <textarea name="transcribe_text" cols="60" rows="10" id="comment" placeholder="Your comment"></textarea>
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

$(function(){

    //start with `NewContent` being the HTML to add to the page
<<<<<<< HEAD
    var NewContent='<textarea name="transcribe_text" cols="60" rows="10" id="comment" placeholder="Your comment"></textarea><button type="button" class="btn btn-default" id="">Submit</button>';
=======
    var NewContent='<textarea name="comment_text" cols="60" rows="10" id="comment" placeholder="Your comment"></textarea>
                    <button type="button" class="btn btn-default" id="">Submit</button>'
>>>>>>> 3a53f1f6216f029efb57495d41752b2a636a3771
    $(".add").click(function(){

        //check if `NewContent` is empty or not
        if (NewContent != '') {
            $("#cmmnt").after(NewContent);

            //now that `NewContent` has been added to the DOM, reset it's value to an empty string so this doesn't happen again
            NewContent = '';
        } else {

            //this is not the first click, so just toggle the appearance of the element that has already been added to the DOM
            //since we injected the element just after the `#spin` element we can select it relatively to that element by using `.next()`
            $('#cmmnt').next().toggle();
        }
    });
});

</script>

<script type="text/javascript">
    $('#work-zone').ready(function() {
        $('#work-view').width($('#work-zone').width());
    });
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
