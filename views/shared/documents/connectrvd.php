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
                    <form id="connect-form" method="post">
                        <div class="panel-group" id="phase1-panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                         <a data-toggle="collapse" href="#phase1-panel" id="phase1-link">Phase 1: Theme</a>
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
                                        <a data-toggle="collapse" href="#phase2-panel" id="phase2-link">Phase 2: Evaluate</a>
                                    </h4>
                                </div>
                                <div id="phase2-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">In this phase, read another worker's work and provide the following assessment:</p>
                                        <p><b>The Worker's Work: </b></p>
                                        <p>The worker's theme ratings: </p>
                                        <p>
                                            <table class="table table-condensed table-striped">
                                                <?php foreach ((array)$this->subjects as $subject): ?>
                                                <tr><td><label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label></td><td><span id="ori-subject<?php echo strtolower($subject['id']); ?>"></span></td></tr>
                                                <?php endforeach; ?>
                                            </table>
                                        </p>
                                        <p>The worker's theme reasoning: <div style="padding: 2px; background-color: #eee;"  id="ori-subjectreasoning"></div></p>
                                        <hr>
                                        <p><b>Your Assessment for the Worker: </b></p>
                                        <p>Checklist:</p>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="understanding" name="understanding">The worker read and understood the definition of each theme. (as implied from the ratings and reasoning)</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="usefulness" name="usefulness">The worker made ratings based on how useful the document on the left could help a historian research the themes.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="reasoning" name="reasoning">The worker provided convincing reasons why the document is useful for research some of the themes if any.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="reasoningdegree" name="reasoningdegree">The worker provided convincing reasons why the document is useful with the specified degrees for research some of the themes if any.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="evidence" name="evidence">The worker provided concrete evidence from the document why the document is useful for research some of the themes if any.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="reasoningnot" name="reasoningnot">The worker provided convincing reasons why the document is NOT useful for research some of the themes if any.</label>
                                        </div>
                                        <p>How effective are the worker's theme ratings?</p>
                                        <select class="form-control">
                                            <option></option>
                                            <option>9 Excellent</option>
                                            <option>8</option>
                                            <option>7 Very Good</option>
                                            <option>6</option>
                                            <option>5 Acceptable</option>
                                            <option>4</option>
                                            <option>3 Borderline</option>
                                            <option>2</option>
                                            <option>1 Poor</option>
                                        </select>
                                        <p>How can the worker improve his or her work?</p>
                                        <textarea style="width:100%;" rows="4" id="feedback"></textarea>
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
                                        <p class="header-step">Based on what you learned from Phase 2, please revise your responses from Phase 1. Your previous responses have been copied here and you may go back to see your answers in Phase 2.</p>
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
                                        <button type="button" class="btn btn-primary pull-right" id="phase3-button">Submit</button>
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
    function valueToDescription(value) {
        switch (value) {
            case "0": return "Not at all useful";
            case "1": return "Weekly useful";
            case "2": return "Moderately useful";
            case "3": return "Strongly useful";
            default: return "Not selected";
        }
    }
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
                $('#ori-subject1').text(valueToDescription("0")); //religion
                $('#ori-subject2').text(valueToDescription("0")); //white supremacy
                $('#ori-subject3').text(valueToDescription("0")); //racial equality
                $('#ori-subject4').text(valueToDescription("0")); //gender equality/inequality
                $('#ori-subject5').text(valueToDescription("0")); //human equality
                $('#ori-subject6').text(valueToDescription("0")); //self government
                $('#ori-subject7').text(valueToDescription("0")); //america as a global beacon
                $('#ori-subject8').text(valueToDescription("0")); //celebration of revolutionary generation
                $('#ori-subject9').text(valueToDescription("0")); //white southerners
                $('#ori-subject10').text(valueToDescription("0")); //meritocracy
                $('#ori-subject11').text(valueToDescription("0")); //social-economic equality/inequality
                $('#ori-subject12').text(valueToDescription("0")); //economy
                $('#ori-subject13').text(valueToDescription("3")); //american revolutionary war
                $('#ori-subject14').text(valueToDescription("0")); //american civil war
                $('#ori-subjectreasoning').text("Among all the themes, the document should only be useful for American revolutionary war because this was a letter from Washington specifically about commands and information to Benjamin who seemed to conduct spying tasks close to Bedford. Since USA was technically not established during the war, most of the themes won't apply here such as american as a global beacon or american civil war.");
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
                $('input[type=checkbox]').prop('disabled', true)
                $('label:has(input[type=checkbox][disabled])').css('color', '#999')
                $('select').prop('disabled', true);
                $('#feedback').prop('disabled', true);
                $('#feedback').css('color', '#999');
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
