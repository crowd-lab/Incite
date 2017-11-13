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
                        <li> Does the response indicate the subject of the source? </li>
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
                                    <label for="staticEmail" class="col-sm-5 col-form-label">Summarizing Score:</label>
                                    <div class="col-sm-7">
                                        <label class="radio-inline"><input type="radio" name="ss" value="0">0</label>
                                        <label class="radio-inline"><input type="radio" name="ss" value="1">1</label>
                                        <label class="radio-inline"><input type="radio" name="ss" value="2">2</label>
                                        <label class="radio-inline"><input type="radio" name="ss" value="3">3</label>
                                        <label class="radio-inline"><input type="radio" name="ss" value="4">4</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="or">Reasoning of Grading (Summarizing):</label>
                                    <textarea class="form-control" rows="2" id="sr" name="sr"></textarea>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-5 col-form-label">Contextualizing Score:</label>
                                    <div class="col-sm-7">
                                        <label class="radio-inline"><input type="radio" name="cs" value="0">0</label>
                                        <label class="radio-inline"><input type="radio" name="cs" value="1">1</label>
                                        <label class="radio-inline"><input type="radio" name="cs" value="2">2</label>
                                        <label class="radio-inline"><input type="radio" name="cs" value="3">3</label>
                                        <label class="radio-inline"><input type="radio" name="cs" value="4">4</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="or">Reasoning of Grading (Contextualizing):</label>
                                    <textarea class="form-control" rows="2" id="cr" name="cr"></textarea>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-5 col-form-label">Inferring Score:</label>
                                    <div class="col-sm-7">
                                        <label class="radio-inline"><input type="radio" name="is" value="0">0</label>
                                        <label class="radio-inline"><input type="radio" name="is" value="1">1</label>
                                        <label class="radio-inline"><input type="radio" name="is" value="2">2</label>
                                        <label class="radio-inline"><input type="radio" name="is" value="3">3</label>
                                        <label class="radio-inline"><input type="radio" name="is" value="4">4</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="or">Reasoning of Grading (Inferring):</label>
                                    <textarea class="form-control" rows="2" id="ir" name="ir"></textarea>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-5 col-form-label">Monitoring Score:</label>
                                    <div class="col-sm-7">
                                        <label class="radio-inline"><input type="radio" name="ms" value="0">0</label>
                                        <label class="radio-inline"><input type="radio" name="ms" value="1">1</label>
                                        <label class="radio-inline"><input type="radio" name="ms" value="2">2</label>
                                        <label class="radio-inline"><input type="radio" name="ms" value="3">3</label>
                                        <label class="radio-inline"><input type="radio" name="ms" value="4">4</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="or">Reasoning of Grading (Inferring):</label>
                                    <textarea class="form-control" rows="2" id="mr" name="mr"></textarea>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-5 col-form-label">Overall Score:</label>
                                    <div class="col-sm-7">
                                        <span id="os">0</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="or">Overall Comment:</label>
                                    <textarea class="form-control" rows="2" id="or" name="or"></textarea>
                                </div>
                                <button type="submit">Submit</button>
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

    function updateOverallScore() {
        var ss = 0, cs = 0, is = 0, ms = 0;
        if ($('input[name=ss]:checked').length != 0) {
            ss = parseInt($('input[name=ss]:checked').val());
        }
        if ($('input[name=cs]:checked').length != 0) {
            cs = parseInt($('input[name=cs]:checked').val());
        }
        if ($('input[name=is]:checked').length != 0) {
            is = parseInt($('input[name=is]:checked').val());
        }
        if ($('input[name=ms]:checked').length != 0) {
            ms = parseInt($('input[name=ms]:checked').val());
        }
        $('#os').text(ss+cs+is+ms);

    }

    $(document).ready(function () {

        $('input:radio').on('click', function (e) {
            updateOverallScore();
        });


    });


</script>
