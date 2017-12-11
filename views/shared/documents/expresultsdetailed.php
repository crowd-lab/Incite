
<!DOCTYPE html>
<html lang="en">
<?php
$task = "transcribe";
include(dirname(__FILE__).'/../common/header.php');


?>


<div style="text-align: center;" class="col-md-10 col-md-offset-1">
    <h2>Results</h2>
    <h5 style="text-align: right;">*(min, mean, max) - counts</h5>
    <table class="table">

    <tr><td></td><td colspan="2">baseline</td><td colspan="2">scim</td></tr>
    <tr><td></td><td>pretest</td><td>posttest</td><td>pretest</td><td>posttest</td></tr>
    <tr><td>Summary</td><td>(<?php echo $this->baseline_summarytone_pre_min; ?>, <?php echo $this->baseline_summarytone_pre_mean; ?>, <?php echo $this->baseline_summarytone_pre_max; ?>) - <?php echo $this->baseline_summarytone_pre_counts; ?></td><td>(<?php echo $this->baseline_summarytone_post_min; ?>, <?php echo $this->baseline_summarytone_post_mean; ?>, <?php echo $this->baseline_summarytone_post_max; ?>) - <?php echo $this->baseline_summarytone_post_counts; ?></td><td>(<?php echo $this->scim_summarytone_pre_min; ?>, <?php echo $this->scim_summarytone_pre_mean; ?>, <?php echo $this->scim_summarytone_pre_max; ?>) - <?php echo $this->scim_summarytone_pre_counts; ?></td><td>(<?php echo $this->scim_summarytone_post_mean; ?>, <?php echo $this->scim_summarytone_post_min; ?>, <?php echo $this->scim_summarytone_post_max; ?>) - <?php echo $this->scim_summarytone_post_counts; ?></td></tr>
    <tr><td>Tag</td><td>(<?php echo $this->baseline_tag_pre_min; ?>, <?php echo $this->baseline_tag_pre_mean; ?>, <?php echo $this->baseline_tag_pre_max; ?>) - <?php echo $this->baseline_tag_pre_counts; ?></td><td>(<?php echo $this->baseline_tag_post_min; ?>, <?php echo $this->baseline_tag_post_mean; ?>, <?php echo $this->baseline_tag_post_max; ?>) - <?php echo $this->baseline_tag_post_counts; ?></td><td>(<?php echo $this->scim_tag_pre_min; ?>, <?php echo $this->scim_tag_pre_mean; ?>, <?php echo $this->scim_tag_pre_max; ?>) - <?php echo $this->scim_tag_pre_counts; ?></td><td>(<?php echo $this->scim_tag_post_mean; ?>, <?php echo $this->scim_tag_post_min; ?>, <?php echo $this->scim_tag_post_max; ?>) - <?php echo $this->scim_tag_post_counts; ?></td></tr>
    <tr><td>Connect</td><td>(<?php echo $this->baseline_connect_pre_min; ?>, <?php echo $this->baseline_connect_pre_mean; ?>, <?php echo $this->baseline_connect_pre_max; ?>) - <?php echo $this->baseline_connect_pre_counts; ?></td><td>(<?php echo $this->baseline_connect_post_min; ?>, <?php echo $this->baseline_connect_post_mean; ?>, <?php echo $this->baseline_connect_post_max; ?>) - <?php echo $this->baseline_connect_post_counts; ?></td><td>(<?php echo $this->scim_connect_pre_min; ?>, <?php echo $this->scim_connect_pre_mean; ?>, <?php echo $this->scim_connect_pre_max; ?>) - <?php echo $this->scim_connect_pre_counts; ?></td><td>(<?php echo $this->scim_connect_post_mean; ?>, <?php echo $this->scim_connect_post_min; ?>, <?php echo $this->scim_connect_post_max; ?>) - <?php echo $this->scim_connect_post_counts; ?></td></tr>
<!-- separate display: version 1
    <tr><td><br>Summary</td><td>mean: <?php echo $this->baseline_summarytone_pre_mean; ?><br>min: <?php echo $this->baseline_summarytone_pre_min; ?><br>max: <?php echo $this->baseline_summarytone_pre_max; ?><br></td><td>mean: <?php echo $this->baseline_summarytone_post_mean; ?><br>min: <?php echo $this->baseline_summarytone_post_min; ?><br>max: <?php echo $this->baseline_summarytone_post_max; ?><br></td><td>mean: <?php echo $this->scim_summarytone_pre_mean; ?><br>min: <?php echo $this->scim_summarytone_pre_min; ?><br>max: <?php echo $this->scim_summarytone_pre_max; ?><br></td><td>mean: <?php echo $this->scim_summarytone_post_mean; ?><br>min: <?php echo $this->scim_summarytone_post_min; ?><br>max: <?php echo $this->scim_summarytone_post_max; ?><br></td></tr>
    <tr><td><br>Tag</td><td>mean: <?php echo $this->baseline_tag_pre_mean; ?><br>min: <?php echo $this->baseline_tag_pre_min; ?><br>max: <?php echo $this->baseline_tag_pre_max; ?><br></td><td>mean: <?php echo $this->baseline_tag_post_mean; ?><br>min: <?php echo $this->baseline_tag_post_min; ?><br>max: <?php echo $this->baseline_tag_post_max; ?><br></td><td>mean: <?php echo $this->scim_tag_pre_mean; ?><br>min: <?php echo $this->scim_tag_pre_min; ?><br>max: <?php echo $this->scim_tag_pre_max; ?><br></td><td>mean: <?php echo $this->scim_tag_post_mean; ?><br>min: <?php echo $this->scim_tag_post_min; ?><br>max: <?php echo $this->scim_tag_post_max; ?><br></td></tr>
    <tr><td><br>Connect</td><td>mean: <?php echo $this->baseline_connect_pre_mean; ?><br>min: <?php echo $this->baseline_connect_pre_min; ?><br>max: <?php echo $this->baseline_connect_pre_max; ?><br></td><td>mean: <?php echo $this->baseline_connect_post_mean; ?><br>min: <?php echo $this->baseline_connect_post_min; ?><br>max: <?php echo $this->baseline_connect_post_max; ?><br></td><td>mean: <?php echo $this->scim_connect_pre_mean; ?><br>min: <?php echo $this->scim_connect_pre_min; ?><br>max: <?php echo $this->scim_connect_pre_max; ?><br></td><td>mean: <?php echo $this->scim_connect_post_mean; ?><br>min: <?php echo $this->scim_connect_post_min; ?><br>max: <?php echo $this->scim_connect_post_max; ?><br></td></tr>
-->
    </table>
</div>
<br>
<br>

<script>
$(function() {
    window.onbeforeunload = "";
});
</script>
