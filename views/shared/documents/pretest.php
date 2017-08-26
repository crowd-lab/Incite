<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $task_description = "Pre-test";
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
                    <p class="header-step">Background: Imagine you were a historian who is interested in the Depression and currently investigating a historical question, "<u>What was the life of a boy like during the Depression?</u>"</p>
                    <p class="header-step">Step <?php echo $task_seq; ?>a: Read the related historical document on the left.</p>
                    <p class="header-step">Step <?php echo $task_seq; ?>b: Write your response below to the historical document and question.</p>
                    <form id="interpretation-form" method="post">
                        <textarea style="width:100%;" name="response" rows="10" placeholder="Your response here."></textarea>
                        <input type="hidden" id="start" name="start" value="">
                        <input type="hidden" id="end" name="end" value="">
                        <button type="button" class="btn btn-primary pull-right" id="confirm-button">Submit</button>
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
        $('#start').val(getNow());
        $('#confirm-button').on('click', function(e) {
            $(this).prop('disabled', true);
            window.onbeforeunload = null;
            $('#end').val(getNow());
            $('#interpretation-form').submit();
        });
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