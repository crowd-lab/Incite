
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
        if (check_input()) {
            window.onbeforeunload = "";
            $('#demo-form').submit();
        }
    });
});

</script>

</head>

<body>

    <div style="margin-top: 20px;" class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Demographics</h3>
            </div>
            <form action="" method="post" id="demo-form">
                <div style="padding: 15px;">
                    <div class="form-group row">
                      <label for="example-text-input" class="col-xs-2 col-form-label">Name</label>
                      <div class="col-xs-10">
                        <input name="name" class="form-control" type="text" value="" id="name" placeholder="Your name that will be reported to your class for extra credit">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-text-input" class="col-xs-2 col-form-label">Age</label>
                      <div class="col-xs-10">
                        <input name="age" class="form-control" type="text" placeholder="Your age (please round to the closest whole number)" id="age">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-text-input" class="col-xs-2 col-form-label">Gender</label>
                      <div class="col-xs-10">
                        <select name="gender" class="form-control" id="gender">
                            <option value="" selected></option>
                            <option value="m">Male</option>
                            <option value="f">Female</option>
                            <option value="o">Other</option>
                        </select>
                      </div>
                    </div>
                    <div class="row" style="margin: 10px;"><button type="button" id="submit-demo" class="btn btn-primary pull-right">Submit</button></div>
                </div>
            </form>
            <div style="clearfix"></div>
        </div> 
    </div>
</body>

</html>
