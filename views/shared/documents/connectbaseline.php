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
                        <p class="header-step">
                            Step <?php echo $task_seq; ?>a: Look through the themes and rate how useful the historical document is to each of the themes.
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
                            Step <?php echo $task_seq; ?>b: Please provide your reasoning for your above choices.
                        </p>
                        <textarea id="subjectreasoning" style="width:100%;" name="reasoning" rows="5"></textarea>
                        <br>
                        <br>
                        <button type="button" class="btn btn-primary pull-right" id="phase3-button">Submit</button>
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
