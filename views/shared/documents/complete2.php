<!DOCTYPE html>
<html lang="en">
<?php
$mturk_url = "";
//$endpoint = "production";
$endpoint = "sandbox";
if ($endpoint == "production")
    $mturk_url = "https://www.mturk.com/mturk/externalSubmit";
else
    $mturk_url = "https://workersandbox.mturk.com/mturk/externalSubmit";


?>
<body>

<div style="text-align: center">
<h2>You have completed the study.</h2>
<h2>Please click the following button to finish the HIT.</h2>
<h2>Thank you for your participation.</h2>
</div>
<br>
<br>
<?php echo $_GET['assignmentId']; ?>
<form method="post" action="<?php echo $mturk_url; ?>">
<input style="width:300px;" type="text" name="assignmentId" value="" />
<input type="hidden" name="test" value="mytest" />
<input type="hidden" name="test2" value="mytest2" />
<input type="submit" value="submit" />
</form>
</body>

</html>
