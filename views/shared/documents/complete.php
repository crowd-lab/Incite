<!DOCTYPE html>
<html lang="en">
<?php
$task = "transcribe";
include(dirname(__FILE__).'/../common/header.php');

$mturk_url = "";
//$endpoint = "production";
$endpoint = "sandbox";
if ($endpoint == "production")
    $mturk_url = "https://www.mturk.com/mturk/externalSubmit";
else
    $mturk_url = "https://workersandbox.mturk.com/mturk/externalSubmit";


?>


<div style="text-align: center">
<h2>You have completed the study.</h2>
<h2>Please click the following button to finish the HIT.</h2>
<h2>Thank you for your participation.</h2>
</div>
<br>
<br>

<form method="post" action="<?php echo $mturk_url; ?>">
<div style="text-align: center"><button style="width: 100px;" class="btn btn-primary">Finish</button></div>
</form>

<script>
$(function() {
    window.onbeforeunload = "";
});
</script>
