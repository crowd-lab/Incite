<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__).'/connect_include.php');
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
                                            Step <?php echo $task_seq; ?>.1a: Look through the themes and rate how useful the historical document is to each of the themes.
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
                                            Step <?php echo $task_seq; ?>.1b: Please provide your reasoning for your above choices.</i>
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
                                        <a data-toggle="collapse" href="#phase2-panel" id="phase2-link">Phase 2: Learn Historical Thinking</a>
                                    </h4>
                                </div>
                                <div id="phase2-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">Background: With some historical question of interest in mind, a historian analyzes and investigates historical documents to find answers to those questions. You are now asked to analyze a historical document to help the historian investigate the below historical question by thinking like a historian.</p>
                                        <p class="header-step">Historical Question: <u>What was the role of spies during the American Revolutionary War?</u></p>
                                        <p class="header-step">Historical Thinking: To think like a historian, the third and fourth steps are to <u>infer</u> and <u>monitor</u> a historical document by identifying answers to some key inferential and monitoring questions. Please read the text on the left and provide your answer to each of the questions below.</p>
                                        <p class="header-step">What interpretations, inferences, perspectives or points of view may be drawn from or indicated by the source?</p>
                                        <textarea style="width:100%;" id="iq2" name="iq2" rows="3"></textarea>
                                        <p class="header-step">What additional evidence beyond the source is necessary to answer the historical question?</p>
                                        <textarea style="width:100%;" id="mq1" name="mq1" rows="3"></textarea>
                                        <p class="header-step">What ideas, images, or terms need further defining from the source?</p>
                                        <textarea style="width:100%;" id="mq2" name="mq2" rows="3"></textarea>
                                        <p class="header-step">How useful or siginficant is the source for its intended purpose in answering the historical question?</p>
                                        <textarea style="width:100%;" id="mq3" name="mq3" rows="3"></textarea>
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
                                            Step <?php echo $task_seq; ?>.3a: Look through the themes and rate how useful the historical document is to each of the themes.
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
                                            Step <?php echo $task_seq; ?>.3b: Please provide your reasoning for your above choices.</i>
                                        </p>
                                        <textarea id="revsubjectreasoning" style="width:100%;" name="reasoning" rows="5"></textarea>
                                        <br>
                                        <br>
                    <form id="connect-form" method="post">
                        <input type="hidden" id="start" name="start" value="">
                        <input type="hidden" id="baseline" name="baseline" value="">
                        <input type="hidden" id="condition" name="condition" value="">
                        <input type="hidden" id="revised" name="revised" value="">
                        <input type="hidden" id="end" name="end" value="">
                        <button type="button" class="btn btn-primary pull-right" id="phase3-button">Submit</button>
                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>
    var phase2events = [];
    var baseline = {};
    var condition = {};
    var revised = {};
        $(document).ready(function() {
        $('#start').val(getNow());
        baseline['start'] = getNow();
        $('#phase2-link').on('click', function(e) {
            if ($('#phase2-link').hasClass('collapsed')) { //event will be the opposite
                phase2events.push(['expand', getNow()]);
            } else {
                phase2events.push(['collapse', getNow()]);
            }
        });
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
                baseline['end'] = getNow();
                $('#phase1-panel').collapse('hide');
                $('#phase1-panel').on('show.bs.collapse', function(e) {
                    e.preventDefault();
                });
                $('#phase1-link').addClass('disabled');
                $('#phase2-panel-group').show();
                $('#phase2-panel').collapse('show');
                $("html, body").animate({ scrollTop: 0 }, "slow");
                baseline['response'] = {};
                <?php foreach ((array)$this->subjects as $subject): ?>
                    baseline['response']["subject<?php echo $subject['id']; ?>"] = $('input[name=subject<?php echo $subject['id']; ?>]:checked').val();
                <?php endforeach; ?>
                baseline['response']["subjectreasoning"] = $('#subjectreasoning').val();
                $('#baseline').val(JSON.stringify(baseline));
                condition['start'] = getNow();
            });
            $('#phase2-button').on('click', function(e) {
                condition['end'] = getNow();
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
                condition['response'] = {};
                condition['response']['question1'] = $('#iq2').val();
                condition['response']['question2'] = $('#mq1').val();
                condition['response']['question3'] = $('#mq2').val();
                condition['response']['question4'] = $('#mq3').val();
                $('#condition').val(JSON.stringify(condition));
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
                revised['start'] = getNow();
            });
            $('#phase3-button').on('click', function(e) {
                window.onbeforeunload = null;
                $('#end').val(getNow());
                revised['response'] = {};
                <?php foreach ((array)$this->subjects as $subject): ?>
                revised['response']["subject<?php echo $subject['id']; ?>"] = $('input[name=revsubject<?php echo $subject['id']; ?>]:checked').val();
                <?php endforeach; ?>
                revised['response']["subjectreasoning"] = $('#revsubjectreasoning').val();
                revised['phase2events'] = phase2events;
                revised['end'] = getNow();
                $('#revised').val(JSON.stringify(revised));
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
