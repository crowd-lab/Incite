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

            <div class="col-md-6">
                <p class="header-step">
                    <i>Step 1 of 1: Mark all categories that apply to this document</i>
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
                                        <input type="checkbox" class="subject-checkbox" name="subjects[]" value="<?php echo $subject['id']; ?>">  
                                        <label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label>
                                        <br>
                    <?php endforeach; ?>
                    <input type="checkbox" class="none-checkbox" name="no_subjects" value="100">  
                    <label>None of the above topics applied</label>
                    <br>
                    <input type="hidden" name="query_str" value="<?php echo (isset($this->query_str) ? $this->query_str : ""); ?>">  
                    <button type="button" id="submit-selection-btn" class="btn btn-primary pull-right">Submit</button>
                </form>

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
                checkPositiveSubjects();
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
    </script>

    <style>
        .discussion-seperation-line {
            margin-top: 60px;
        }
    </style>
</body>
</html>
