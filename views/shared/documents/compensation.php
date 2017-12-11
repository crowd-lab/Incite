<!DOCTYPE html>
<html lang="en">
<?php
$task = "transcribe";
include(dirname(__FILE__).'/../common/header.php');

$mturk_url = "";
$endpoint = "production";
//$endpoint = "sandbox";
if ($endpoint == "production")
    $mturk_url = "https://www.mturk.com/mturk/externalSubmit";
else
    $mturk_url = "https://workersandbox.mturk.com/mturk/externalSubmit";


?>


<div style="text-align: center">
<h2>This is a compensation HIT for invitation only.</h2>
<h2>Please click the "Finish" button to get your compensation.</h2>
<h2>Button will show up after you accept the HIT.</h2>
<h2>Uninvited submission will be rejected.</h2>
<h2>Thank you for your participation.</h2>
</div>
<br>
<br>

<form method="post" action="<?php echo $mturk_url; ?>">
<input id="assignment-form" type="hidden" name="assignmentId" value="<?php echo $this->assignment_id; ?>" />
<input type="hidden" name="dummy" value="completed" />
<?php if (isset($this->assignment_id) && $this->assignment_id != ""): ?>
<div style="text-align: center"><button style="width: 100px;" class="btn btn-primary">Finish</button></div>
<? endif; ?>
</form>

<script>
$(function() {
    window.onbeforeunload = "";
});
</script>
