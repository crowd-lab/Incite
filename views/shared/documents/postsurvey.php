
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

</style>

<script>

function check_input () {
    var controls = $('#demo-form .form-control');
    for (var i = 0; i < controls.length; i++) {
        if ($(controls[i]).val() === "") {
            alert('The "'+controls[i].name.substring(0,1).toUpperCase()+controls[i].name.substring(1)+'" is not specified yet');
            return false;
        }
    }
    return true;
}

$( function () {
    $('#submit-demo').on('click', function (e) {
        if (check_input())
            $('#demo-form').submit();
    });
});

</script>

</head>

<?php $_SESSION['study2']['qa-set'] = 1; ?>

<body>
    <form action="" method="post" id="demo-form">
        <?php 
            if (isset($_SESSION['study2']['qa-set'])) {
                switch ($_SESSION['study2']['qa-set'] == 1) {
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
