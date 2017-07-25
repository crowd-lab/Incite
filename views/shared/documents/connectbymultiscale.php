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
    <?php
        include(dirname(__FILE__) . '/../common/task_header.php');
    ?>
    <div class="container-fluid">
        <div class="container-fluid" style="padding: 0px;">

            <div class="col-md-6" id="work-zone">
               <?php
                    include(dirname(__FILE__) . '/../common/document_viewer_section_with_transcription.php');
                ?>
            </div>

            <div class="col-md-6" id="connecting-work-area">
                <div id="connecting-container">
                    <p class="header-step">
                        <i>Step 1 of 1: What themes in the following could this document help a historian research/inve    stigate? Please rate based on usefulness</i>
                        <a id="view-revision-history-link" style="display: none;">View Revision History...  </a>
                        <span class="glyphicon glyphicon-info-sign step-instruction-glyphicon"
                            aria-hidden="true" data-trigger="hover"
                            data-toggle="popover" data-html="true"
                            data-viewport="#subject-form";
                            data-title="<strong>Marking Categories</strong>"
                            data-content="<?php echo "Simply choose all of the categories you think apply to this document. If none apply, select 'None of the above topics applied'." ?>"
                            data-placement="bottom" data-id="<?php echo $transcription->id; ?>">
                        </span>
                    </p>

                    <form id="subject-form" method="post">
                        <table class="table">
                            <thead>
                                <td>Themes</td>
                                <td>Not useful</td>
                                <td>Somewhat useful</td>
                                <td>Useful</td>
                                <td>Very useful</td>
                                <td>Extremely useful</td>
                            </thead>
                        <?php foreach ((array)$this->subjects as $subject): ?>
                            <tr>
                                <td><label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="0"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="1"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="2"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="3"></td>
                                <td><input type="radio" name="subject<?php echo $subject['id']; ?>" value="4"></td>
                            </tr>
                        <?php endforeach; ?>
                        </table>
                        <p class="header-step">
                            <i>Step 2 of 2: Please provide your reasoning for your above choices.</i>
                        </p>
                        <textarea style="width:100%;" name="reasoning" rows="5" id = "reasoning"></textarea>
                        <p style = "float: right">Character Count: <span id = "word-counting">0</span></p>
                        <br>
                        <br>
                        <input type="hidden" name="connection_type" value="multiscale">
                        <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">
                        <input id="links" type="hidden" value="" name="link"> </input>
                        <button type="button" id="submit-continue-connect" class="btn btn-primary">Submit & Continue Connect</button>
                        <button type="button" id="submit-selection-btn" class="btn btn-primary">Submit & Transcribe</button>
                    </form>
                </div>

                <?php
                    include(dirname(__FILE__) . '/../common/revision_history_for_task_id_pages.php');
                ?>

                <hr size=2 class="discussion-seperation-line">

                <?php
                    include(dirname(__FILE__) . '/../common/task_comments_section.php');
                ?>
            </div>
        </div>
    </div>
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->

    <!-- Bootstrap Core JavaScript -->
    <script>
        $(document).ready(function() {
          $('#reasoning').keyup(function() {
            var text_length = $('#reasoning').val().length;
            $('#word-counting').text(text_length);

          });
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            addButtonAndCheckboxListeners();

            <?php if ($this->is_being_edited): ?>
                styleForEditing();
            <?php endif; ?>
        });

        function addButtonAndCheckboxListeners() {
            $("#submit-selection-btn").click(function() {
              /*  if ($('input[type="checkbox"]:checked').length == 0) {
                    notifyOfErrorInForm("At least one category must be selected")
                    return;
                }
*/
                //from progress_indicator.php
                styleProgressIndicatorForCompletion();
                $("#links").val('1');
                $("#subject-form").submit();
            });
            $("#submit-continue-connect").click(function() {
              $("#links").val('2');
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

        #step-0 .btn-end { display: block; }

        #step-5 .btn-end { display: block; }

        .tooltip {
            position: fixed;
        }

        .btn-end {
            display: none;
        }
        #submit-selection-btn {
          float:right;
        }

    </style>
</body>
</html>
