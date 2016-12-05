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
                        <i>Step 1 of 1: Mark all categories that apply to this document</i>
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
                        <?php foreach ((array)$this->subjects as $subject): ?>
                                            <input type="checkbox" class="subject-checkbox" name="subjects[]" value="<?php echo $subject['id']; ?>" id="checkBoxAnswer">
                                            <label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label>
                                            <br>
                        <?php endforeach; ?>
                        <input type="checkbox" class="none-checkbox" name="no_subjects" value="100">
                        <label>None of the above topics applied</label>
                        <br>
                        <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">
                        <button type="button" id="submit-selection-btn" class="btn btn-primary pull-right">Submit</button>
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
                if ($('input[type="checkbox"]:checked').length === 0) {
                    notifyOfErrorInForm("At least one category must be selected")
                    return;
                }

                //from progress_indicator.php
                styleProgressIndicatorForCompletion();

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
        var tour = new Tour({
            
            template: "<div class='popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><nav class='popover-navigation'><div class='btn-group'><button class='btn btn-default' data-role='prev'>« Prev</button><button class='btn btn-default' data-role='next'>Next »</button></div><button class='btn btn-default btn-end' data-role='end'>End tour</button></nav></div>",
        steps: [
            {
                element: '#work-view',
                title: "Welcome!",
                content: "It looks like you haven’t connected a document before. We have a short tutorial to guide you through the process. If you already know all this information, press End Tour now.",
                placement: "right",
            },
            {
                element: '#work-view',
                title: "",
                content: "This is a document that has already been transcribed and tagged. Go ahead and read through it now.",
                placement: "right",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
            },
            {
                element: "#connecting-container",
                title: "Connect Task",
                content: 'Now that you’ve read the transcription, check each category that relates to the provided document.',
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=next]").prop("disabled", true);
                    $('input[type="checkbox"]').one("mouseup", function() {
                        if (this.value == 100) {
                            tour.next();
                        }
                    });
                }
            },
            {
                element: '#connecting-container',
                title: "Great!",
                content: "Good job! Click next to continue",
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
            },
            {
                element: "#comment-container",
                title: "Comment",
                content: '1. This area shows comments from others about this document.<br>2. If you are logged in, you will be able to make comments.',
                placement: "left",
                onShown: function() {
                    $(".popover.tour-tour .popover-navigation .btn-group .btn[data-role=end]").prop("disabled", true);
                }
            },
            {
                element: "#work-view",
                title: "Congratulations! You've finished the Connect Tutorial.",
                content: 'You’re all done!  <br>Press End Tour to exit this tutorial.',
                placement: "right"
            }
        ],
        backdrop: true,
        storage: false});

        // Initialize the tour
        tour.init();

        // Start the tour
        tour.start(true);
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
    </style>
</body>
</html>
