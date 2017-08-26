<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            include(dirname(__FILE__) . '/summarytone_include.php');
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
                        <div class="panel-group" id="phase1-panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                         <a data-toggle="collapse" href="#phase1-panel" id="phase1-link">Phase 1: Summary and Tone</a>
                                    </h4>
                                </div>
                                <div id="phase1-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">Step <?php echo $task_seq; ?>a: Without changing the meaning of the text on the left, please shorten the text to no more than <u>3</u> sentences and place your shortened text below.</p>
                                        <textarea id="summary" style="width:100%;" name="summary" rows="5" placeholder="Your shortened text here"></textarea>
                                        <p class="header-step">Step <?php echo $task_seq; ?>b: Please rate how much each of the following tones reflects the author's attitude towards the subject.</p>
                                        <br>
                                        <br>
                                        <table class="table">
                                            <thead>
                                                <th>Tone</th>
                                                <th>Not at all</th>
                                                <th>Weakly</th>
                                                <th>Moderately</th>
                                                <th>Strongly</th>
                                            </thead>
                                            <tr>
                                                <td>Informational</td>
                                                <td><input type="radio" name="tone1" value="0"></td>
                                                <td><input type="radio" name="tone1" value="1"></td>
                                                <td><input type="radio" name="tone1" value="2"></td>
                                                <td><input type="radio" name="tone1" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Anxious</td>
                                                <td><input type="radio" name="tone2" value="0"></td>
                                                <td><input type="radio" name="tone2" value="1"></td>
                                                <td><input type="radio" name="tone2" value="2"></td>
                                                <td><input type="radio" name="tone2" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Optimistic</td>
                                                <td><input type="radio" name="tone3" value="0"></td>
                                                <td><input type="radio" name="tone3" value="1"></td>
                                                <td><input type="radio" name="tone3" value="2"></td>
                                                <td><input type="radio" name="tone3" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Sarcastic</td>
                                                <td><input type="radio" name="tone4" value="0"></td>
                                                <td><input type="radio" name="tone4" value="1"></td>
                                                <td><input type="radio" name="tone4" value="2"></td>
                                                <td><input type="radio" name="tone4" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Prideful</td>
                                                <td><input type="radio" name="tone5" value="0"></td>
                                                <td><input type="radio" name="tone5" value="1"></td>
                                                <td><input type="radio" name="tone5" value="2"></td>
                                                <td><input type="radio" name="tone5" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Aggressive</td>
                                                <td><input type="radio" name="tone6" value="0"></td>
                                                <td><input type="radio" name="tone6" value="1"></td>
                                                <td><input type="radio" name="tone6" value="2"></td>
                                                <td><input type="radio" name="tone6" value="3"></td>
                                            </tr>
                                        </table>
                                        <p class="header-step">Step <?php echo $task_seq; ?>c: Please provide your reasoning for ratings above.</i></p>
                                        <textarea id="tonereasoning" style="width:100%;" name="tonereasoning" rows="6" placeholder="Your reasoning here."></textarea>
                                        <button type="button" class="btn btn-primary pull-right" id="phase1-button">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="phase2-panel-group" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#phase2-panel" id="phase2-link">Phase 2: Evaluate</a>
                                    </h4>
                                </div>
                                <div id="phase2-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">In this phase, read your own work and provide the following assessment:</p>
                                        <p><b>Your Work: </b></p>
                                        <p>Your summary: <div style="padding: 2px; background-color: #eee" id="ori-summary"></div></p>
                                        <br>
                                        <p>Your tone ratings: </p>
                                        <p>
                                            <table class="table table-striped table-condensed">
                                                <tr><td>Informational</td><td><span id="ori-informational"></span></td></tr>
                                                <tr><td>Anxious</td><td><span id="ori-anxious"></span></td></tr>
                                                <tr><td>Optimistic</td><td><span id="ori-optimistic"></span></td></tr>
                                                <tr><td>Sarcastic</td><td><span id="ori-sarcastic"></span></td></tr>
                                                <tr><td>Prideful</td><td><span id="ori-prideful"></span></td></tr>
                                                <tr><td>Aggressive</td><td><span id="ori-aggressive"></span></td></tr>
                                            </table>
                                        </p>
                                        <p>Your tone reasoning: <div style="padding: 2px; background-color: #eee" id="ori-tonereasoning"></div></p>
                                        <hr>
                                        <p><b>Self Assessment: </b></p>
                                        <p>Checklist:</p>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="originality" name="originality">I wrote an original summary. I did not plagiarize.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="coverage" name="coverage">I wrote a summary that has balanced coverage. The summary does not focus on some specific parts of the original document.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="neutrality" name="neutrality">I wrote a summary without adding personal opinions nor emotions.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="detail" name="detail">I wrote an summary with sufficient information and details from the original document.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="spelling" name="spelling">I did not have spelling and grammar mistakes.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="length" name="length">I wrote the right amount (3 sentences).</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="subject" name="subject">I correctly identified the subject of the document.</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="" id="evidence" name="evidence">I provided reasoning with evidence to support my tone ratings such as keywords implying emotions or attitudes.</label>
                                        </div>
                                        <p>How effective is your summary?</p>
                                        <select id="eff_summary" class="form-control">
                                            <option value="0"></option>
                                            <option value="9">9 Excellent</option>
                                            <option value="8">8</option>
                                            <option value="7">7 Very Good</option>
                                            <option value="6">6</option>
                                            <option value="5">5 Acceptable</option>
                                            <option value="4">4</option>
                                            <option value="3">3 Borderline</option>
                                            <option value="2">2</option>
                                            <option value="1">1 Poor</option>
                                        </select>
                                        <p>How effective are your tone ratings?</p>
                                        <select id="eff_tone" class="form-control">
                                            <option value="0"></option>
                                            <option value="9">9 Excellent</option>
                                            <option value="8">8</option>
                                            <option value="7">7 Very Good</option>
                                            <option value="6">6</option>
                                            <option value="5">5 Acceptable</option>
                                            <option value="4">4</option>
                                            <option value="3">3 Borderline</option>
                                            <option value="2">2</option>
                                            <option value="1">1 Poor</option>
                                        </select>
                                        <p>How can you improve my work?</p>
                                        <textarea style="width:100%;" rows="4" id="feedback"></textarea>
                                        <button type="button" class="btn btn-primary pull-right" id="phase2-button">Next</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group" id="phase3-panel-group" style="display: none;">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                         <a data-toggle="collapse" href="#phase3-panel" id="phase3-link">Phase 3: Revise</a>
                                    </h4>
                                </div>
                                <div id="phase3-panel" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <p class="header-step">Based on what you learned from Phase 2, please revise your responses from Phase 1. Your previous responses have been copied here and you may go back to see your answers in Phase 2.</p>
                                        <p class="header-step">Step <?php echo $task_seq; ?>a: Please revise your summary to reflect what you learned in Phase 2.</p>
                                        <textarea id="revsummary" style="width:100%;" name="revsummary" rows="6"></textarea>
                                        <br>
                                        <br>
                                        <p class="header-step">Step <?php echo $task_seq; ?>b: Please revise the ratings of the tones to reflect what you learned in Phase 2.</p>
                                        <table class="table">
                                            <thead>
                                                <th>Tone</th>
                                                <th>Not at all</th>
                                                <th>Weakly</th>
                                                <th>Moderately</th>
                                                <th>Strongly</th>
                                            </thead>
                                            <tr>
                                                <td>Informational</td>
                                                <td><input type="radio" name="revtone1" value="0"></td>
                                                <td><input type="radio" name="revtone1" value="1"></td>
                                                <td><input type="radio" name="revtone1" value="2"></td>
                                                <td><input type="radio" name="revtone1" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Anxious</td>
                                                <td><input type="radio" name="revtone2" value="0"></td>
                                                <td><input type="radio" name="revtone2" value="1"></td>
                                                <td><input type="radio" name="revtone2" value="2"></td>
                                                <td><input type="radio" name="revtone2" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Optimistic</td>
                                                <td><input type="radio" name="revtone3" value="0"></td>
                                                <td><input type="radio" name="revtone3" value="1"></td>
                                                <td><input type="radio" name="revtone3" value="2"></td>
                                                <td><input type="radio" name="revtone3" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Sarcastic</td>
                                                <td><input type="radio" name="revtone4" value="0"></td>
                                                <td><input type="radio" name="revtone4" value="1"></td>
                                                <td><input type="radio" name="revtone4" value="2"></td>
                                                <td><input type="radio" name="revtone4" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Prideful</td>
                                                <td><input type="radio" name="revtone5" value="0"></td>
                                                <td><input type="radio" name="revtone5" value="1"></td>
                                                <td><input type="radio" name="revtone5" value="2"></td>
                                                <td><input type="radio" name="revtone5" value="3"></td>
                                            </tr>
                                            <tr>
                                                <td>Aggressive</td>
                                                <td><input type="radio" name="revtone6" value="0"></td>
                                                <td><input type="radio" name="revtone6" value="1"></td>
                                                <td><input type="radio" name="revtone6" value="2"></td>
                                                <td><input type="radio" name="revtone6" value="3"></td>
                                            </tr>
                                        </table>
                                        <p class="header-step">Step <?php echo $task_seq; ?>c: Please revise your reasoning to reflect what you learned in Phase 2.</p>
                                        <textarea id="revtonereasoning" style="width:100%;" name="revtonereasoning" rows="6"></textarea>
                    <form id="summarytone-form" method="post">
                        <input type="hidden" id="start" name="start" value="">
                        <input type="hidden" id="baseline" name="baseline" value="">
                        <input type="hidden" id="condition" name="condition" value="">
                        <input type="hidden" id="revised" name="revised" value="">
                        <input type="hidden" id="end" name="end" value="">
                        <button type="button" class="btn btn-primary pull-right" id="phase3-button">Submit</button>
                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>

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

    function toneValueToDescription(toneValue) {
        switch (toneValue) {
            case "0": return "Not at all reflects author's attitude";
            case "1": return "Weekly reflects author's attitude";
            case "2": return "Moderately reflects author's attitude";
            case "3": return "Strongly reflects author's attitude";
            default: return "Not selected";
        }
    }

    var phase2events = [];
    var baseline = {};
    var condition = {};
    var revised = {};


    $(document).ready(function () {
        $('#start').val(getNow());
        setInterval(function() {$('#count_down_timer').text("Time left: "+numToTime(allowed_time >= 0 ? allowed_time-- : 0)); timeIsUpCheck();}, 1000);
        baseline['start'] = getNow();
        $('#phase2-link').on('click', function(e) {
            if ($('#phase2-link').hasClass('collapsed')) { //event will be the opposite
                phase2events.push(['expand', getNow()]);
            } else {
                phase2events.push(['collapse', getNow()]);
            }
        });
        $('#phase1-button').on('click', function(e) {
            //window.onbeforeunload = null;
            //$('#interpretation-form').submit();
            baseline['end'] = getNow();
            $('#phase1-panel').collapse('hide');
            $('#phase1-panel').on('show.bs.collapse', function(e) {
                e.preventDefault();
            });
            $('#phase1-link').addClass('disabled');
            $('#phase2-panel-group').show();
            $('#ori-summary').text($('#summary').val());
            $('#ori-informational').text(toneValueToDescription($('input[name="tone1"]:checked').val()));
            $('#ori-anxious').text(toneValueToDescription($('input[name="tone2"]:checked').val()));
            $('#ori-optimistic').text(toneValueToDescription($('input[name="tone3"]:checked').val()));
            $('#ori-sarcastic').text(toneValueToDescription($('input[name="tone4"]:checked').val()));
            $('#ori-prideful').text(toneValueToDescription($('input[name="tone5"]:checked').val()));
            $('#ori-aggressive').text(toneValueToDescription($('input[name="tone6"]:checked').val()));
            $('#ori-tonereasoning').text($('#tonereasoning').val());
            $('#phase2-panel').collapse('show');
            $("html, body").animate({ scrollTop: 0 }, "slow");
            baseline['response'] = {};
            baseline['response']["summary"] = $('#summary').val();
            for (var i = 1; i <= 6; i++) {
                baseline['response']["tone"+i] = $('input[name=tone'+i+']:checked').val();
            }
            baseline['response']["tonereasoning"] = $('#tonereasoning').val();
            $('#baseline').val(JSON.stringify(baseline));
            condition['start'] = getNow();
        });
        $('#phase2-button').on('click', function(e) {
            condition['end'] = getNow();
            $('#phase2-panel').collapse('hide');
            $('#phase3-panel-group').show();
            $('#phase3-panel').collapse('show');
            $("html, body").animate({ scrollTop: 0 }, "slow");
            $('#phase2-button').hide();
            $('#revsummary').val($('#summary').val());
            $('#revtonereasoning').val($('#tonereasoning').val());
            $('input[name="revtone1"][value='+$('input[name="tone1"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone2"][value='+$('input[name="tone2"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone3"][value='+$('input[name="tone3"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone4"][value='+$('input[name="tone4"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone5"][value='+$('input[name="tone5"]:checked').val()+']').prop('checked', true)
            $('input[name="revtone6"][value='+$('input[name="tone6"]:checked').val()+']').prop('checked', true)
            condition['response'] = {};
            condition['response']['checklist'] = {};
            $('input[type=checkbox]').each(function (idx) {
                if (this.checked) {
                    condition['response']['checklist'][this.name] = 1;
                } else {
                    condition['response']['checklist'][this.name] = 0;
                }
            });
            condition['response']['eff_summary'] = $('#eff_summary').val();
            condition['response']['eff_tone'] = $('#eff_tone').val();
            condition['response']['feedback'] = $('#feedback').val();
            $('#condition').val(JSON.stringify(condition));
            $('input[type=checkbox]').prop('disabled', true)
            $('label:has(input[type=checkbox][disabled])').css('color', '#999')
            $('select').prop('disabled', true);
            $('#feedback').prop('disabled', true);
            $('#feedback').css('color', '#999');
            revised['start'] = getNow();
        });
        $('#phase3-button').on('click', function(e) {
            window.onbeforeunload = null;
            $(this).prop('disabled', true);
            $('#end').val(getNow());
            revised['response'] = {};
            revised['response']["summary"] = $('#revsummary').val();
            for (var i = 1; i <= 6; i++) {
                revised['response']["tone"+i] = $('input[name=revtone'+i+']:checked').val();
            }
            revised['response']["tonereasoning"] = $('#revtonereasoning').val();
            revised['phase2events'] = phase2events;
            revised['end'] = getNow();
            $('#revised').val(JSON.stringify(revised));
            $('#summarytone-form').submit();
        });
        $('#phase1-panel').collapse('show');
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
