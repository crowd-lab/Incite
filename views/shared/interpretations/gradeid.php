<?php

include(dirname(__FILE__).'/../common/header.php');
?>

    <div style="margin-top: 20px;" class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Rubric and SCIM</h3>
            </div>
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#rubric">Rubric</a></li>
                <li><a data-toggle="tab" href="#SCIM">SCIM</a></li>
            </ul>
            <div class="tab-content">
                <div id="SCIM" class="tab-pane fade">
                Summarizing:
                    <ol>
                        <li>What type of historical document is the source?</li>
                        <li>What specific information, details and/or perspectives does the source provide?</li>
                        <li>What is the subject and/or purpose of the source?</li>
                        <li>Who was the author and/or audience of the source?</li>
                    </ol>


                Contextualizing:
                    <ol>
                        <li>When and where was the source produced?</li>
                        <li>Why was the source produced?</li>
                        <li>What was happening within the immediate and /lioader context at the time the source was produced?</li>
                        <li>What summarizing information can place the source in time and place?</li>
                    </ol>


                Inferring:
                    <ol>
                        <li>What is suggested by the source?</li>
                        <li>What interpretations may be drawn from the source?</li>
                        <li>What perspectives or points of view are indicated in the source?</li>
                        <li>What inferences may be drawn from absences or omissions in the source?</li>
                    </ol>


                Monitoring:
                    <ol>
                        <li>What additional evidence beyond the source is necessary to answer the historical question?</li>
                        <li>What ideas, images, or terms need further defining from the source?</li>
                        <li>How useful or significant is the source for its intended purpose in answering the historical question?</li>
                        <li>What questions from the previous stages need to be revisited in order to analyze the source satisfactorily?</li>
                    </ol>
                </div>
                <div id="rubric" class="tab-pane fade in active">
                    Summarizing (1 point each) 
                    <ol>
                        <li>Does the response indicate the subject of the source? </li>
                        <li>Does the response indicate the audience for the source? </li>
                        <li>Does the response indicate the author of the source? </li>
                        <li>Does the response include specific details from the source? </li>
                    </ol>
                    Contextualizing (1 point each) 
                    <ol>
                        <li>Does the response indicate when the source was produced? </li>
                        <li>Does the response indicate where the source was produced? </li>
                        <li>Does the response indicate why the source was produced? </li>
                        <li>Does the response indicate the immediate or /lioader context?</li> 
                    </ol>
                    Inferring (2 points each) 
                    <ol>
                        <li>Does the response include explicit and/or implicit inferences? </li>
                        <li>Does the response include inferences based on omissions? </li>
                    </ol>
                    Monitoring (2 points each) 
                    <ol>
                        <li>Does the response indicate the need for information beyond the source? </li>
                        <li>Does the response evaluate the usefulness or significance of the source?</li>
                    </ol>
                </div>
            </div>
        </div> 
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Grading...</h3>
            </div>
            <table class="table">
                <thead><tr><td>Original Primary Source:</td></tr></thead>
                <tbody>
                    <tr style="border: solid 1px;">
                        <td>
                            Title: <br><?php echo $this->title; ?><br><br>
                            Content: <br><?php echo $this->text; ?><br>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table">
                <thead><tr><td>Guiding Historical Question:</td></tr></thead>
                <tbody>
                    <tr><td><?php echo $this->question; ?></td></tr>
                </tbody>
            </table>
            <table class="table">
                <thead><tr><td style="width: 45%;">Response</td><td style="width: 55%;">Your Grading</td></tr></thead>
                <tbody>
                    <tr><td style="font-size: 120%;"><?php echo $this->response; ?></td>
                        <td>
                            <form method="post">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-12 col-form-label" style="text-align: center;">Summarizing</label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate the subject of the source?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="s1" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="s1" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate the audience for the source?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="s2" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="s2" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate the author of the source?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="s3" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="s3" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response include specific details from the source?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="s4" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="s4" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-12 col-form-label" style="text-align: center;">Contextualizing</label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate when the source was produced?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="c1" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="c1" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate where the source was produced?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="c2" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="c2" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate why the source was produced?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="c3" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="c3" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate the immediate or broader context?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="c4" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="c4" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-12 col-form-label" style="text-align: center;">Inferring</label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response include explicit and/or implicit inferences?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="i1" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="i1" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response include inferences based on omissions?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="i2" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="i2" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-12 col-form-label" style="text-align: center;">Monitoring</label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response indicate the need for information beyond the source?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="m1" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="m1" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-9 col-form-label">Does the response evaluate the usefulness or significance of the source?</label>
                                    <div class="col-sm-3">
                                        <label class="radio-inline"><input type="radio" name="m2" value="0">No</label>
                                        <label class="radio-inline"><input type="radio" name="m2" value="1">Yes</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="or">Grading reasoning and/or comment:</label>
                                    <textarea id="reasoning" class="form-control" rows="4" id="or" name="or"></textarea>
                                </div>
                                <button id="submit-button" class="btn btn-primary" type="button">Submit</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> 
    </div>


<script>

    function areScoresReady() {
        return true;
    }


    $(document).ready(function () {
        $('#submit-button').on('click', function (e) {
            if ($('input:radio:checked').length < 12) {
                alert('You need to select No/Yes for all question!');
                return;
            }
            if ($('#reasoning').val() == "") {
                alert('You need to provide reasoning or comment for your grading');
                return;
            }
            $('form').submit();
        });


    });


</script>
