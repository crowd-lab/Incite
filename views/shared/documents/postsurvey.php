
<!DOCTYPE html>
<html lang="en">
<?php
queue_css_file(array('bootstrap', 'style', 'bootstrap.min'));
$db = get_db();
$task_description = "Post-survey";

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
    var controls = $('#demographics-form .form-control');
    for (var i = 0; i < controls.length; i++) {
        if ($(controls[i]).val() === "") {
            notif({
              msg: "<b>Error: </b> "+'The "'+controls[i].name.substring(0,1).toUpperCase()+controls[i].name.substring(1)+'" is not specified yet',
              type: "error"
            });
            return false;
        }
    }
    if ($('#age').val() < 18 || $('#age').val() >= 120) {
            notif({
              msg: "<b>Error: </b> Please enter a reasonable age (18-120)",
              type: "error"
            });
            return false;
    } 
    //controls = $('#learning-form .form-control');
    controls = [$('input[name=sys_help]:checked'), $('input[name=learned]:checked'), $('input[name=fun]:checked')];
    for (var i = 0; i < controls.length; i++) {
        if (controls[i].length == 0) {
            notif({
              msg: '<b>Error: </b> "L'+(i+1)+'" is not specified yet',
              type: "error"
            });
            return false;
        }
    }
    controls = [$('input[name=tlx_men]:checked'), $('input[name=tlx_phy]:checked'), $('input[name=tlx_tem]:checked'), $('input[name=tlx_per]:checked'), $('input[name=tlx_eff]:checked'), $('input[name=tlx_fru]:checked')];
    for (var i = 0; i < controls.length; i++) {
        if (controls[i].length == 0) {
            notif({
              msg: '<b>Error: </b> "T'+(i+1)+'" is not specified yet',
              type: "error"
            });
            return false;
        }
    }
    if ($('#feedback').val() == "") {
        notif({
          msg: "<b>Error: </b> You haven't provided any feedback or comments yet!",
          type: "error"
        });
        return false;
    }
    return true;
}

function generate_response() {
    //assume check_input has been done
    var reponse = {};
    response['demographics'] = {};
    response['learning'] = {};
    response['tlx'] = {};
    response['feedback'] = {};

    $('#demographics-form .form-control').each(function(idx) {
        response['demographics'][this.name] = $(this).val();
    });
    $('#learning-form input[type=radio]:checked').each(function(idx) {
        response['learning'][this.name] = $(this).val();
    });
    $('#tlx-form input[type=radio]:checked').each(function(idx) {
        response['tlx'][this.name] = $(this).val();
    });
    response['feedback'] = $('#feedback').val();
    return JSON.stringify(response);
}

$( function () {
    $('#start').val(getNow());
    $('#submit-form').on('click', function (e) {
        if (check_input()) {
            window.onbeforeunload = "";
            $('#end').val(getNow());
            $('#response').val(generate_response());
            $('#postsurvey-form').submit();
        }
    });
});

</script>

</head>

<body>

    <div style="margin-top: 20px;" class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Post-task Survey</h3>
            </div>
            <div id="demographics-form">
                <div style="padding: 15px;">
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
                    <div class="form-group row">
                      <label for="example-text-input" class="col-xs-2 col-form-label">Education</label>
                      <div class="col-xs-10">
                        <select name="education" class="form-control" id="education">
                            <option value="" selected>Choose highest degree</option>
                            <option value="h">High School</option>
                            <option value="a">Associate</option>
                            <option value="b">Bachelor</option>
                            <option value="m">Master</option>
                            <option value="p">PhD</option>
                            <option value="o">Other</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-text-input" class="col-xs-2 col-form-label">Occupation</label>
                      <div class="col-xs-10">
                        <input name="occupation" class="form-control" type="text" placeholder="" id="occupation">
                      </div>
                    </div>
                </div>
            </div>
            <hr>
            <div id="learning-form">
                <div style="padding: 15px;">
                    <div class="form-group">
                        <div>
                            <div><label>L1: How much help did the system give you in analyzing the document?</label></div>
                            <label class="radio-inline"><input type="radio" name="sys_help" value="1">1 (very little)</label>
                            <label class="radio-inline"><input type="radio" name="sys_help" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="sys_help" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="sys_help" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="sys_help" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="sys_help" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="sys_help" value="7">7 (very much)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div><label>L2: How much did you learn from analyzing the document?</label></div>
                            <label class="radio-inline"><input type="radio" name="learned" value="1">1 (very little)</label>
                            <label class="radio-inline"><input type="radio" name="learned" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="learned" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="learned" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="learned" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="learned" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="learned" value="7">7 (very much)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div><label>L3: How much fun did you have analyzing the document?</label></div>
                            <label class="radio-inline"><input type="radio" name="fun" value="1">1 (very little)</label>
                            <label class="radio-inline"><input type="radio" name="fun" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="fun" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="fun" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="fun" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="fun" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="fun" value="7">7 (very much)</label>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <hr>
            <div id="tlx-form">
                <div style="padding: 15px;">
                    <div class="form-group">
                        <div>
                            <div><label>T1: How mentally demanding was the task?</label></div>
                            <label class="radio-inline"><input type="radio" name="tlx_men" value="1">1 (very low)</label>
                            <label class="radio-inline"><input type="radio" name="tlx_men" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="tlx_men" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="tlx_men" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="tlx_men" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="tlx_men" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="tlx_men" value="7">7 (very high)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div><label>T2: How physically demanding was the task?</label></div>
                            <label class="radio-inline"><input type="radio" name="tlx_phy" value="1">1 (very low)</label>
                            <label class="radio-inline"><input type="radio" name="tlx_phy" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="tlx_phy" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="tlx_phy" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="tlx_phy" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="tlx_phy" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="tlx_phy" value="7">7 (very high)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div><label>T3: How hurried or rushed was the pace of the task?</label></div>
                            <label class="radio-inline"><input type="radio" name="tlx_tem" value="1">1 (very low)</label>
                            <label class="radio-inline"><input type="radio" name="tlx_tem" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="tlx_tem" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="tlx_tem" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="tlx_tem" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="tlx_tem" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="tlx_tem" value="7">7 (very high)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div><label>T4: How successful were you in accomplishing what you were asked to do?</label></div>
                            <label class="radio-inline"><input type="radio" name="tlx_per" value="1">1 (very low)</label>
                            <label class="radio-inline"><input type="radio" name="tlx_per" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="tlx_per" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="tlx_per" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="tlx_per" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="tlx_per" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="tlx_per" value="7">7 (very high)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div><label>T5: How hard did you have to work to accomplish your level of performance?</label></div>
                            <label class="radio-inline"><input class="form-check-input" type="radio" name="tlx_eff" value="1">1 (very low)</label>
                            <label class="radio-inline"><input type="radio" name="tlx_eff" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="tlx_eff" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="tlx_eff" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="tlx_eff" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="tlx_eff" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="tlx_eff" value="7">7 (very high)</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div>
                            <div><label>T6: How insecure, discouraged, irritated, stressed, and annoyed were you?</label></div>
                            <label class="radio-inline"><input type="radio" name="tlx_fru" value="1">1 (very low)</label>
                            <label class="radio-inline"><input type="radio" name="tlx_fru" value="2">2</label>
                            <label class="radio-inline"><input type="radio" name="tlx_fru" value="3">3</label>
                            <label class="radio-inline"><input type="radio" name="tlx_fru" value="4">4</label>
                            <label class="radio-inline"><input type="radio" name="tlx_fru" value="5">5</label>
                            <label class="radio-inline"><input type="radio" name="tlx_fru" value="6">6</label>
                            <label class="radio-inline"><input type="radio" name="tlx_fru" value="7">7 (very high)</label>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div id="feedback-form">
                <div style="padding: 15px;">
                    <div class="form-group">
                      <label for="example-text-input" class="col-form-label">Please use the box below to give feedback or comments on the task, such as any confusions or improvements.</label>
                      <div class="col-xs-10">
                        <textarea name="feedback" class="form-control" id="feedback" rows="6"></textarea>
                      </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
            <br>
                    <div class="row" style="margin: 10px;">
                        <form action="" method="post" id="postsurvey-form">
                            <input type="hidden" id="start" name="start" value="">
                            <input type="hidden" id="response" name="response" value="">
                            <input type="hidden" id="end" name="end" value="">
                            <button type="button" id="submit-form" class="btn btn-primary pull-right">Submit</button>
                        </form>
                    </div>
            <div style="clearfix"></div>
        </div> 
    </div>
</body>

</html>
