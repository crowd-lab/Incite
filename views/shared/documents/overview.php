<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include(dirname(__FILE__) . '/../common/header.php');
    ?>

    <script type="text/javascript">
        $(document).ready(function () {
            <?php
                if (isset($_SESSION['incite']['message'])) {
                    echo "notifyOfSuccessfulActionNoTimeout('" . $_SESSION["incite"]["message"] . "');";
                    unset($_SESSION['incite']['message']);
                }
            ?>

            $("#document_img").iviewer({
                src: "<?php echo $this->image_url; ?>",
                zoom_min: 1,
                zoom: "fit"
            });
        });
    </script>

    <style> 
        #document-header {
            text-align: center;
        }
    </style>
</head>
    
<body>
    <div id="document-header">
        <?php
            echo '<h1> Document Title: ' . metadata($this->transcription, array('Dublin Core', 'Title')) . '</h1>';
        ?>
    </div>

    <div class="container-fluid">
        <div class="col-md-6" id="work-zone">
            <div class="wrapper">
                <div id="document_img" class="viewer"></div>
            </div>
        </div>

        <div class="col-md-6">
        </div>
    </div>
</body>
</html>

