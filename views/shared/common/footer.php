
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
    <?php if (empty(get_option('delete_sponsor1')) || get_option('delete_sponsor1') == "no"): ?>
        <?php if (empty(get_option('sponsorlink1'))): ?>
        <a target="_blank" ><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors1.png"></a>
        <?php else: ?>
        <a target="_blank" href="<?php echo get_option('sponsorlink1'); ?>"><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors1.png"></a>
        <?php endif ?>
    <?php endif ?>
    <?php if (empty(get_option('delete_sponsor2')) || get_option('delete_sponsor2') == "no"): ?>
        <?php if (empty(get_option('sponsorlink2'))): ?>
        <a target="_blank" ><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors2.png"></a>
        <?php else: ?>
        <a target="_blank" href="<?php echo get_option('sponsorlink2'); ?>"><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors2.png"></a>
        <?php endif ?>
    <?php endif ?>
    <?php if (empty(get_option('delete_sponsor3')) || get_option('delete_sponsor3') == "no"): ?>
        <?php if (empty(get_option('sponsorlink3'))): ?>
        <a target="_blank" ><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors3.png"></a>
        <?php else: ?>
        <a target="_blank" href="<?php echo get_option('sponsorlink3'); ?>"><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors3.png"></a>
        <?php endif ?>
    <?php endif ?>
    <?php if (empty(get_option('delete_sponsor4')) || get_option('delete_sponsor4') == "no"): ?>
        <?php if (empty(get_option('sponsorlink4'))): ?>
        <a target="_blank" ><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors4.png"></a>
        <?php else: ?>
        <a target="_blank" href="<?php echo get_option('sponsorlink4'); ?>"><img class="footer-sponsor-logo-img" style="width:200px" src="<?php echo getFullOmekaUrl(); ?>plugins/Incite/views/shared/images/customized_sponsors4.png"></a>
        <?php endif ?>
    <?php endif ?>
</div>




