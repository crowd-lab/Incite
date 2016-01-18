
<?php
include(dirname(__FILE__).'/../common/header.php');
?>



<h1><?php echo $this->redirect['message'].' in <span id="time">'.(isset($this->redirect['time']) ? $this->redirect['time'] : 10).'</span> seconds'; ?> (or you can click <a href="<?php echo $this->redirect['url']; ?>">here</a> to go now!)</h1>

</body>

<script>
    var time = <?php echo (isset($this->redirect['time']) ? $this->redirect['time'] : 10); ?>;

    function countDownAndRedirect() {
        if (time <= 0)
            window.location.href = '<?php echo $this->redirect['url']; ?>';
        else {
            time--;
            $('#time').text(time);
        }
    }

    setInterval(countDownAndRedirect, 1000);
</script>


</html>
