<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__).'/../common/header.php');
        include(dirname(__FILE__).'/../common/progress_indicator.php');

        $category_object = getAllCategories();
    ?>

    <!-- Page Content -->
    <script type="text/javascript">
        var comment_type = 2;
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="container-fluid" style="padding: 0px;">

            <div class="col-md-6" id="work-zone">
               <?php
                    include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
                ?>
            </div>

            <div class="col-md-6" id="connecting-work-area">
                <div id="connecting-container">
                    <form id="connect-form" method="post">
                        <div class="panel-group" id="phase1-panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                         <a data-toggle="collapse" href="#phase1-panel" id="phase1-link">Phase 1: Connect</a>
                                    </h4>
                                </div>
                                <div id="phase1-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">
                                            <i>Step 1 of 2: What themes in the following could this document help a historian research/investigate? Please rate based on usefulness.</i>
                                        </p>
                                        <table class="table">
                                            <thead>
                                                <th>Themes</th>
                                                <th>Not at all</th>
                                                <th>Weakly</th>
                                                <th>Moderately</th>
                                                <th>Strongly</th>
                                            </thead>
                                        <?php foreach ((array)$this->subjects as $subject): ?>
                                            <tr>
                                                <td><label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label></td>
                                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="0"></td>
                                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="1"></td>
                                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="2"></td>
                                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="3"></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </table>
                                        <p class="header-step">
                                            <i>Step 2 of 2: Please provide your reasoning for your above choices.</i>
                                        </p>
                                        <textarea id="subjectreasoning" style="width:100%;" name="reasoning" rows="5"></textarea>
                                        <br>
                                        <br>
                                        <button type="button" class="btn btn-primary pull-right" id="phase1-button">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="phase2-panel-group" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#phase2-panel" id="phase2-link">Phase 2: Infer and Monitor</a>
                                    </h4>
                                </div>
                                <div id="phase2-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step"><i>Background: With some historical question of interest in mind, a historian analyzes and investigates historical documents to find answers to those questions. You are now asked to analyze a historical document to help the historian investigate the below historical question by thinking like a historian.</i></p>
                                        <p class="header-step"><i>Historical Question: <u>What was the role of spies during the American Revolutionary War?</u></i></p>
                                        <p class="header-step"><i>Historical Thinking: To think like a historian, the third and fourth steps are to <u>infer</u> and <u>monitor</u> a historical document by identifying answers to some key inferential and monitoring questions. Please read the text on the left and provide your answer to each of the questions below.</i></p>
                                        <p class="header-step"><i>Q1: What is suggested by the source?</i></p>
                                        <textarea style="width:100%;" name="iq1" rows="3"></textarea>
                                        <p class="header-step"><i>Q2: What interpretations may be drawn from the source?</i></p>
                                        <textarea style="width:100%;" name="iq2" rows="3"></textarea>
                                        <p class="header-step"><i>Q3: What perspectives or points of view are indicated in the source?</i></p>
                                        <textarea style="width:100%;" name="iq3" rows="3"></textarea>
                                        <p class="header-step"><i>Q4: What inferences may be drawn from absences or omissions in the source?</i></p>
                                        <textarea style="width:100%;" name="iq4" rows="3"></textarea>
                                        <p class="header-step"><i>Q5: What additional evidence beyond the source is necessary to answer the historical question?</i></p>
                                        <textarea style="width:100%;" name="mq1" rows="3"></textarea>
                                        <p class="header-step"><i>Q6: What ideas, images, or terms need further defining from the source?</i></p>
                                        <textarea style="width:100%;" name="mq2" rows="3"></textarea>
                                        <p class="header-step"><i>Q7: How useful or siginficant is the source for its intended purpose in answering the historical question?</i></p>
                                        <textarea style="width:100%;" name="mq3" rows="3"></textarea>
                                        <p class="header-step"><i>Q8: What questions from the previous stages need to be revisited in order to analyze the source satisfactorily?</i></p>
                                        <textarea style="width:100%;" name="mq4" rows="3"></textarea>
                                        <button type="button" class="btn btn-primary pull-right" id="phase2-button">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="phase3-panel-group" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                         <a data-toggle="collapse" href="#phase3-panel" id="phase3-link">Phase 3: Revise</a>
                                    </h4>
                                </div>
                                <div id="phase3-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step"><i>Based on what you learned from Phase 2, please revise your responses from Phase 1. Your previous responses have been copied here and you may go back to see your answers in Phase 2.</i></p>
                                        <p class="header-step">
                                            <i>Step 1 of 2: What themes in the following could this document help a historian research/investigate? Please rate based on usefulness.</i>
                                        </p>
                                        <table class="table">
                                            <thead>
                                                <th>Themes</th>
                                                <th>Not at all</th>
                                                <th>Weakly</th>
                                                <th>Moderately</th>
                                                <th>Strongly</th>
                                            </thead>
                                        <?php foreach ((array)$this->subjects as $subject): ?>
                                            <tr>
                                                <td><label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label></td>
                                                <td><input type="radio" name="revsubject<?php echo $subject['id']; ?>" value="0"></td>
                                                <td><input type="radio" name="revsubject<?php echo $subject['id']; ?>" value="1"></td>
                                                <td><input type="radio" name="revsubject<?php echo $subject['id']; ?>" value="2"></td>
                                                <td><input type="radio" name="revsubject<?php echo $subject['id']; ?>" value="3"></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </table>
                                        <p class="header-step">
                                            <i>Step 2 of 2: Please provide your reasoning for your above choices.</i>
                                        </p>
                                        <textarea id="revsubjectreasoning" style="width:100%;" name="reasoning" rows="5"></textarea>
                                        <br>
                                        <br>
                                        <button type="button" class="btn btn-primary pull-right" id="phase3-button">Finish</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>
        $(document).ready(function() {
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            addButtonAndCheckboxListeners();
            setInterval(function() {$('#count_down_timer').text("Time left: "+numToTime(allowed_time >= 0 ? allowed_time-- : 0)); timeIsUpCheck();}, 1000);

            <?php if ($this->is_being_edited): ?>
                styleForEditing();
            <?php endif; ?>
        });

        $('form input:radio').on('change', function (e) {
            $(this).parent().parent().removeClass('unrated-theme');
        });

        function addButtonAndCheckboxListeners() {
            $("#submit-selection-btn").click(function() {
                var themes = {};
                $('form input:radio').each( function(e) { themes[this.name]=true; });
                themes = Object.keys(themes);
                for (var i = 0; i < themes.length; i++) {
                    if ($('input:radio[name='+themes[i]+']:checked').length === 0) {
                        $($('input:radio[name='+themes[i]+']')[0]).parent().parent().addClass('unrated-theme');
                        notifyOfErrorInForm("Please rate ALL the themes.")
                        return;
                    }
                }

                if ($('textarea[name=reasoning]').val() === "") {
                    notifyOfErrorInForm("Please provide your reasoning for your choices.")
                    return;
                }
/*
                if ($('input[type="checkbox"]:checked').length === 0) {
                    notifyOfErrorInForm("At least one category must be selected")
                    return;
                }
*/

                //from progress_indicator.php
                //styleProgressIndicatorForCompletion();
                window.onbeforeunload = "";

                $("#subject-form").submit();
            });

            $(".subject-checkbox").on('click', function(e) {
                $(".none-checkbox").prop('checked', false);
            });

            $(".none-checkbox").on('click', function(e) {
                $(".subject-checkbox").each(function(index, checkbox) {
                    $(this).prop('checked', false);
                });
            });
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
                $('#revsubjectreasoning').val($('#subjectreasoning').val());
                <?php foreach ((array)$this->subjects as $subject): ?>
                    $('input[name="revsubject<?php echo $subject['id']; ?>"][value='+$('input[name="subject<?php echo $subject['id']; ?>"]:checked').val()+']').prop('checked', true)
                <?php endforeach; ?>
                $('#iq1').prop('disabled', true);
                $('#iq1').css('color', '#999');
                $('#iq2').prop('disabled', true);
                $('#iq2').css('color', '#999');
                $('#iq3').prop('disabled', true);
                $('#iq3').css('color', '#999');
                $('#iq4').prop('disabled', true);
                $('#iq4').css('color', '#999');
                $('#mq1').prop('disabled', true);
                $('#mq1').css('color', '#999');
                $('#mq2').prop('disabled', true);
                $('#mq2').css('color', '#999');
                $('#mq3').prop('disabled', true);
                $('#mq3').css('color', '#999');
                $('#mq4').prop('disabled', true);
                $('#mq4').css('color', '#999');
            });
            $('#phase3-button').on('click', function(e) {
                window.onbeforeunload = null;
                $('#connect-form').submit();
            });
            $('#phase1-panel').collapse('show');
        }

        function styleForEditing() {
            checkPositiveSubjects();
            addRevisionHistoryListeners();
        }

        function checkPositiveSubjects() {
            var hasNoPositiveSubjects = true;

            <?php foreach ((array)$this->newest_n_subjects as $subject): ?>
                <?php if ($subject['is_positive']): ?>
                    hasNoPositiveSubjects = false;

                    $(".subject-checkbox").each(function() {
                        if ($(this).val() === String(<?php echo $subject['subject_id']; ?>)) {
                            $(this).prop('checked', true);
                        }
                    });
                <?php endif; ?>
            <?php endforeach; ?>

            if (hasNoPositiveSubjects) {
                $(".none-checkbox").prop('checked', true);
            }
        }

        function addRevisionHistoryListeners() {
            $('#view-revision-history-link').show();

            $('#view-revision-history-link').click(function(e) {
                $('#connecting-container').hide();
                $('#revision-history-container').show();
            });

            $('#view-editing-link').click(function(e) {
                $('#revision-history-container').hide();
                $('#connecting-container').show();
            });

        }
    </script>

    <style>
        .discussion-seperation-line {
            margin-top: 60px;
        }

        #view-revision-history-link {
            position: absolute;
            right: 0;
            cursor: pointer;
        }

        #connecting-work-area {
            margin-top: -32px;
        }

        .unrated-theme {
            border: 2px solid red;
        }
    </style>
</body>
</html>
