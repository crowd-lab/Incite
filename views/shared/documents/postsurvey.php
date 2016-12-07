<?php

function question_generator($question) {
    if ($question['type'] == 'r') {
        radio_question_generator($question);
    } else { // true or false
        tf_question_generator($question);
    }
}

function tf_question_generator($question) {
        $options = $question['options'];
        shuffle($options);

        echo '<div class="form-group">';
        echo '    <label for="exampleSelect1">'.$question['q'].'</label>';
        echo '<table class="table">';
        foreach($options as $option) {
            //echo ' <div><div class="radio" style="display: inline;"><label><input type="radio" name="q'.$question['num']."".$option['val'].'" value="1">Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="radio" name="q'.$question['num']."".$option['val'].'" value="0">No</label></div><span style="margin-left: 5px;"><b>:</b></span><span style="margin-left: 15px; width: 100px;">'.$option['label'].'</span></div>';
            echo ' <tr><td><span><input type="radio" name="q'.$question['num']."".$option['val'].'" value="1">Yes</span></td><td><span><input type="radio" name="q'.$question['num']."".$option['val'].'" value="-1">No</span></td><td style="width: 80%;">'.$option['label'].'</td></tr>';
        }
        echo '</table>';
        echo '</div>';

}

function radio_question_generator($question) {

        $options = $question['options'];
        shuffle($options);

        echo '<div class="form-group">';
        echo '    <label for="exampleSelect1">'.$question['q'].'</label>';
        foreach($options as $option) {
            echo '    <div class="radio"><label><input type="radio" name="q'.$question['num'].'" value="'.$option['val'].'">'.$option['label'].'</label></div>';
        }
        echo '</div>';
    
}



?>
<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();

include(dirname(__FILE__).'/../common/header.php');
?>
<head>
<style>

.panel-heading .accordion-toggle:after {
    /* symbol for "opening" panels */
    font-family: 'Glyphicons Halflings';  /* essential for enabling glyphicon */
    content: "\e114";    /* adjust as needed, taken from bootstrap.css */
    float: right;        /* adjust as needed */
    color: grey;         /* adjust as needed */
}
.panel-heading .accordion-toggle.collapsed:after {
    /* symbol for "collapsed" panels */
    content: "\e080";
  }

.incomplete-question {
    border: 2px solid red;
}

</style>

<script>


function check_input () {
    var questions_names = {};
    $('input:radio').each(function (idx) { 
        questions_names[this.name]=true;
    })

    var questions = Object.keys(questions_names);
    for (var i = 0; i < questions.length; i++) {
        var q = $('#demo-form input:radio[name='+questions[i]+']:checked');
        if (q.length === 0) {
            var elem_to_highlight = $($('#demo-form input:radio[name='+questions[i]+']')[0]).parent().parent().parent();
            elem_to_highlight.addClass('incomplete-question');
            $('body').animate({
                scrollTop: elem_to_highlight.offset().top-75
            }, 500);

            notif({
                msg: "You haven't answered this questions yet!",
                type: "error",
                position: "right",
                timeout: 2000
            });

            return false;
        }
    }
    return true;
}

$( function () {
    setInterval(function() {$('#count_down_timer').text("Time left: "+numToTime(allowed_time--)); timeIsUpCheck();}, 1000);
    $('#submit-demo').on('click', function (e) {
        if (check_input()) {
            window.onbeforeunload = "";
            $('#demo-form').submit();
        }
    });

    $('#demo-form input:radio').on('change', function (e) {
            $(this).parent().parent().parent().removeClass('incomplete-question');
    });
});

</script>

</head>


<body>
    <form action="" method="post" id="demo-form">
        <?php 
            if (isset($_SESSION['study2']['qa-set'])) {
                switch ($_SESSION['study2']['qa-set']) {
                    case 1: include('postsurvey1.php'); break; //Bobby Murray
                    case 2: include('postsurvey2.php'); break; //Washington
                    case 3: include('postsurvey3.php'); break; //Civil War
                    default: include('postsurvey_error.php');
                }
            } else {
                include('postsurvey_error.php');
            }
        ?>
    </form>
</body>

</html>
