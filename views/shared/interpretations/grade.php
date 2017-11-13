<?php

include(dirname(__FILE__).'/../common/header.php');
?>

    <div style="margin-top: 20px;" class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ungraded</h3>
            </div>
            <table class="table">
                <thead><tr><td style="width: 80%;"><b>Responses</b></td><td><b>Action</b></td></tr></thead>
                <tbody>
<?php foreach ((array) $this->interpretations as $interpretation): ?>
                <tr><td><?php echo $interpretation['response']; ?></td><td><a href="<?php echo getFullInciteUrl()."/interpretations/grade/"; ?><?php echo $interpretation['id']; ?>">Grade Now</a></td></tr>
<?php endforeach; ?>
<?php if (count($this->interpretations) == 0): ?>
                <tr><td>No graded responses!</td><td></td></tr>
<?php endif; ?>
                </tbody>
            </table>
        </div> 
<!--
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Graded</h3>
            </div>
            <table class="table">
                <thead><tr><td style="width: 40%;">Response</td><td style="width: 40%;">Your Grading</td><td>Action</td></tr></thead>
                <tbody>
                    <tr><td>This is a long response that is from the crowd. And you are supposed to help grade this using the given rubric and your professional judgement to determine the final scores for the response. Don't forget to follow the rubric. If you have questions or feel ambiguities in the rubic, you should try to consult the SCIM framework</td><td>Summarizing: 1 because xyz. Contextualizing: 0 since it's something. Inferring: 0 (no inferences found). Monitoring: 0 (no monitoring found).</td><td>Regrade</td></tr>
                </tbody>
            </table>
        </div> 
    </div>
-->
