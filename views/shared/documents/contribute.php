
<html lang="en">
<?php
include(dirname(__FILE__).'/../common/header.php');
?>

    <div id="homepage-content" style="margin-top: 75px; margin-left: 12.5%; margin-right:12.5%; margin-bottom: 25px;">

        <form class="form-inline" id="contribute_form" method="get" action="<?php echo getFullInciteUrl(); ?>/discover">
            <div id="contribute-confirmation" style="margin: 40px; text-align: center;">
                <h2>I want to
                    <select class="form-control" style="font-size: 60%; width: 150px; height: 40px;" name="task">
                        <option value="transcribe" <?php if ($this->task_type === 'transcribe') echo 'selected="selected"'; ?>>transcribe</option>
                        <option value="tag" <?php if ($this->task_type === 'tag') echo 'selected="selected"'; ?>>tag</option>
                        <option value="connect" <?php if ($this->task_type === 'connect') echo 'selected="selected"'; ?>>connect</option>

                    </select> documents published in
                    <input type="text" class="form-control" value="" style="font-size: 60%; width: 150px; height: 40px;" name="location" placeholder="anywhere">
                    <br>
                    <br>
                    between
                    <select class="form-control" style="font-size: 60%; width: 95px;" id="time_from" name="time_from">
        <option value="1830" selected="selected">1830</option>
<?php for ($i = 1831; $i <= 1880; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor; ?>
                    </select> and
                    <select class="form-control" style="font-size: 60%; width: 95px;" id="time_to" name="time_to">
<?php for ($i = 1830; $i <= 1879; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php endfor; ?>
                        <option value="1880" selected="selected">1880</option>
                    </select>.
                </h2>
            </div>   <!-- contribute-confirmation -->
            <div style="text-align: center;">
                <button type="button" id="contribute_button" style="margin: 40px;" class="btn btn-danger">GET STARTED</button>
            </div>
        </form>
    </div> <!-- homepage-content -->


    <script>
       $(document).ready( function () {
            $('#contribute_button').on('click', function (e) {
                if ($('#time_from').val() > $('#time_to').val()) {
                    notif({
                        type: "warning",
                        msg: "<b>Warning:</b> \"from\" time cannot be later than \"to\" time!",
                        position: "right"
                    });
                } else {
                    $('#contribute_form').submit();
                }
            });

        });
    </script>


</html>
