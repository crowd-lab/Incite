
<style>


    #footer {
        margin-left: 150px;
        margin-right: 150px;
        margin-top: 90px;
        margin-bottom: 35px;
        border-top: 1px solid;
        border-top-color: #B2B1B1;
        text-align: center;
    }

    .footer-sponsor-logo-img {
        max-height: 135px;
        margin-left: 30px;
        margin-right: 30px;
    }

</style>

<div id="footer" style="">
    <?php if(!empty(get_option('sponsor_arr'))): ?>
        <div style="color: #B2B1B1; margin-bottom: 12px;">Sponsored by</div>
    <?php endif; ?>
    <?php $sponsor_arr = json_decode(get_option('sponsor_arr')); foreach ((array)$sponsor_arr as $sponsor): ?>
        <a target="_blank" href="<?php echo get_option('sponsorlink'.$sponsor) ?>"><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors<?php echo $sponsor; ?>.png"></a>
    <?php endforeach; ?>
</div>




