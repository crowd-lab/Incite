<!DOCTYPE html>
<html lang="en">
<?php
include(dirname(__FILE__).'/../common/header.php');
?>


<body>
    <!-- Page Content -->
    <div class="container">


        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h1>Join existing discussions or <a class="" type="" href="<?php echo getFullInciteUrl().'/discussions/create'; ?>">Create</a> your own!</h1>
            </div>

            <div class="col-md-10 col-md-offset-1">
                <ul class="list-group">
<?php foreach ((array)$this->Discussions as $discussion): ?>
                    <li class="list-group-item">
                        <a href="<?php echo getFullInciteUrl().'/discussions/discuss/'.$discussion->id; ?>"><?php echo $discussion->discussion_text; ?></a> - created by <a href="<?php echo getFullInciteUrl().'/users/view/'.$discussion->user_id; ?>"><?php echo $discussion->user_first_name; ?></a><span class='db_time'><?php echo $discussion->timestamp_creation; ?></span><span class="badge">Comments: <?php echo $discussion->num_of_comments; ?></span>
                    </li>
<?php endforeach; ?>
                </ul>
            </div>
            <div class="col-md-12 text-center">
                <nav>
                    <ul class="pagination">
                        <li class="<?php echo ($this->current_page == 1 ? "disabled" : ""); ?>"><a <?php echo ($this->current_page == 1 ? "" : 'href="?page='.($this->current_page-1)); ?><?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
<?php for ($i = 0; $i < $this->total_pages; $i++): ?>
                        <li class="<?php if ($this->current_page == ($i+1)) echo 'active'; ?>"><a href="?page=<?php echo ($i+1); ?><?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>"><?php echo ($i+1); ?><span class="sr-only">(current)</span></a></li>
<?php endfor; ?>
                        <li class="<?php echo ($this->total_pages == $this->current_page ? "disabled" : ""); ?>"><a <?php echo ($this->current_page == $this->total_pages ? "" : 'href="?page='.($this->current_page+1)); ?><?php echo ($this->query_str == "" ? "" : "&".$this->query_str); ?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                  </ul>
                </nav>
            </div>
        </div>
    </div>
    <script type="text/javascript">

    $('#map-div').ready( function (e) {
        $('#map-div').height($('#map-div').width()/2);
    });

$(document).ready( function (e) {
    var ev = [
<?php for ($i = 0; $i < count($this->Transcriptions); $i++): ?>
            {
                id : <?php echo $i; ?>,
                name : "<?php echo metadata($this->Transcriptions[$i], array('Dublin Core', 'Title')); ?>",
                desc : "<?php echo metadata($this->Transcriptions[$i], array('Dublin Core', 'Description')); ?>",
                on : new Date("<?php echo metadata($this->Transcriptions[$i], array('Dublin Core', 'Date')); ?>")
            },
<?php endfor; ?>
    ]

var tl = $('#timeline').jqtimeline({
                            events : ev,
							numYears: (1880-1845),
							startYear: 1845,
                            endYear: 1880,
                            totalWidth: $('#timeline').width(),
                            click:function(e,event){
                                alert(event.desc);
                            }
                        });
        $('.db_time').each(function (idx) {
            $(this).text(' '+compareDates(new Date($(this).text())));
        });

<?php
    if (count($this->Discussions) == 0) {
        $displayMessage = 'No dicussions currently exist. Be the first to <a href="/m4j/incite/discussions/create">create one!</a>';
        echo "notifyOfRedirect('" . $displayMessage . "');";
    }
?>

});
</script>


    </div>
    <!-- /.container -->

</body>
