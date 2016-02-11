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
                <p class="header-step"><i>Step 1 of 1: Mark all categories that apply to this document</i></p>

                <form id="subject-form" method="post">
                    <?php foreach ((array)$this->subjects as $subject): ?>
                                        <input type="checkbox" class="subject-checkbox" name="subjects[]" value="<?php echo $subject['id']; ?>">  
                                        <label><a data-toggle="popover" data-trigger="hover" data-title="Definition" data-content="<?php echo $subject['definition']; ?>"><?php echo $subject['name']; ?></a></label>
                                        <br>
                    <?php endforeach; ?>
                    <input type="checkbox" class="none-checkbox" name="no_subjects" value="-1">  
                    <label>None</label>
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
            $(".subject-checkbox").on('click', function(e) {
                $(".none-checkbox").prop('checked', false);
            });

            $(".none-checkbox").on('click', function(e) {
                $(".subject-checkbox").each(function(index, checkbox) {
                    $(this).prop('checked', false);
                });
            });

            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            $("#submit-selection-btn").click(function() {
                if ($('input[type="checkbox"]:checked').length === 0) {
                    notifyOfErrorInForm("At least one category must be selected")
                    return;
                }

                //from progress_indicator.php
                styleProgressIndicatorForCompletion();

                $("#subject-form").submit();
            });
        });
    </script>

    <style>
        .discussion-seperation-line {
            margin-top: 60px;
        }
    </style>
</body>
</html>
