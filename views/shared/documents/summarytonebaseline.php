<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include(dirname(__FILE__) . '/summarytone_include.php');
            include(dirname(__FILE__) . '/../common/header.php');
            include(dirname(__FILE__) . '/../common/progress_indicator.php');

            $category_object = getAllCategories();
            $category_id_name_table = getSubcategoryIdAndNames();
        ?>

        <script type="text/javascript">
            var msgbox;
            var comment_type = 1;
        </script>
    </head>

    <body> <!-- Page Content -->
        <div class="container-fluid">
            <div class="col-md-5" id="work-zone">
                <?php
                    include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
                ?>
            </div>

            <div class="col-md-7">
                <div id="tagging-container">
                    <br>
                    <form id="summarytone-form" method="post">
                                        <p class="header-step"><i>Step 1 of 3: Without changing the meaning of the text on the left, please shorten the text to no more than <u>3</u> sentences and place your shortened text below.</i></p>
                                        <textarea id="summary" style="width:100%;" name="summary" rows="5" placeholder="Your shortened text here"></textarea>
                                        <p class="header-step"><i>Step 2 of 3: Please rate how much each of the following tones reflects the author's attitude towards the subject.</i></p>
                                        <table class="table">
                                            <thead>
                                                <th>Tone</th>
                                                <th>Not at all</th>
                                                <th>Weakly</th>
                                                <th>Moderately</th>
                                                <th>Strongly</th>
                                            </thead>
                                            <tr>
                                                <td><label>Informational</label></td>
                                                <td><input type="radio" name="tone1" value="0"></td>
                                                <td><input type="radio" name="tone1" value="1"></td>
                                                <td><input type="radio" name="tone1" value="2"></td>
                                                <td><input type="radio" name="tone1" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td><label>Anxious</label></td>
                                                <td><input type="radio" name="tone2" value="0"></td>
                                                <td><input type="radio" name="tone2" value="1"></td>
                                                <td><input type="radio" name="tone2" value="2"></td>
                                                <td><input type="radio" name="tone2" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td><label>Optimistic</label></td>
                                                <td><input type="radio" name="tone3" value="0"></td>
                                                <td><input type="radio" name="tone3" value="1"></td>
                                                <td><input type="radio" name="tone3" value="2"></td>
                                                <td><input type="radio" name="tone3" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td><label>Sarcastic</label></td>
                                                <td><input type="radio" name="tone4" value="0"></td>
                                                <td><input type="radio" name="tone4" value="1"></td>
                                                <td><input type="radio" name="tone4" value="2"></td>
                                                <td><input type="radio" name="tone4" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td><label>Prideful</label></td>
                                                <td><input type="radio" name="tone5" value="0"></td>
                                                <td><input type="radio" name="tone5" value="1"></td>
                                                <td><input type="radio" name="tone5" value="2"></td>
                                                <td><input type="radio" name="tone5" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td><label>Aggressive</label></td>
                                                <td><input type="radio" name="tone6" value="0"></td>
                                                <td><input type="radio" name="tone6" value="1"></td>
                                                <td><input type="radio" name="tone6" value="2"></td>
                                                <td><input type="radio" name="tone6" value="3"></td>
                                            </tr>
                                        </table>
                                        <p class="header-step"><i>Step 3 of 3: Please provide your reasoning for rating the tones.</i></p>
                                        <textarea id="tonereasoning" style="width:100%;" name="tonereasoning" rows="6" placeholder="Your reasoning here."></textarea>
                                        <button type="button" class="btn btn-primary pull-right" id="phase3-button">Finish</button>
                    </form>

                </div>
                <hr size=2 class="discussion-seperation-line">

            </div>
        </div>
    <!-- End work container -->

<script type="text/javascript">
    //Global variable to store categories/counters
    var categories = <?php echo json_encode($category_object).";\n"; ?>
    // alert(categories[2]['subcategory'].length);
    var category_id_to_name_table = <?php echo json_encode($category_id_name_table).";\n"; ?>
    var tagid_id_counter = <?php echo (isset($this->tag_id_counter) ? $this->tag_id_counter : "0"); ?>;



    $(document).ready(function () {
        setInterval(function() {$('#count_down_timer').text("Time left: "+numToTime(allowed_time >= 0 ? allowed_time-- : 0)); timeIsUpCheck();}, 1000);
        $('#phase1-button').on('click', function(e) {
            //window.onbeforeunload = null;
            //$('#interpretation-form').submit();
            $('#phase1-panel').collapse('hide');
            $('#phase1-panel').on('show.bs.collapse', function(e) {
                e.preventDefault();
            });
            $('#phase1-link').addClass('disabled');
            $('#phase2-panel-group').show();
            $('#phase2-panel').collapse('show');
            $("html, body").animate({ scrollTop: 0 }, "slow");
        });
        $('#phase2-button').on('click', function(e) {
            $('#phase2-panel').collapse('hide');
            $('#phase3-panel-group').show();
            $('#phase3-panel').collapse('show');
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#phase2-button').hide();
            $('#revsummary').val($('#summary').val());
            $('#revtonereasoning').val($('#tonereasoning').val());
            $('input[name="revtone1"][value='+$('input[name="tone1"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone2"][value='+$('input[name="tone2"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone3"][value='+$('input[name="tone3"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone4"][value='+$('input[name="tone4"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone5"][value='+$('input[name="tone5"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone6"][value='+$('input[name="tone6"]:checked').val()+']').prop('checked', true)
        });
        $('#phase3-button').on('click', function(e) {
            window.onbeforeunload = null;
            $('#summarytone-form').submit();
        });
        $('#phase1-panel').collapse('show');
    });

</script>

<style>
    .discussion-seperation-line {
        margin-top: 100px;
    }

    #tagging-container {
        padding-right: 0px;
        margin-top: -32px;
    }

    .comments-section-container {
        padding-left: 15px;
    }

    #revision-history-container {
        padding-left: 1.5%;
    }

    #view-revision-history-link {
        position: absolute;
        right: 0;
        cursor: pointer;
        margin-top: -32px;
    }

</style>

</body>

</html>
