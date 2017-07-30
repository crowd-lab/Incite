<div class="field">

    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Logo'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Upload Your Logo Here'); ?>.</p>
        <div class="input-block">
        <input type="file" class="textinput"  name="logo" accept=".png" id="logo" />
        <input type="hidden" class="textinput"  name="confirm-logo"/>
        </div>
    </div>
    
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Sponsors'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Write Your Sponsors Here'); ?>.</p>
        <div class="input-block">
        <input type="file" class="textinput"  name="sponsor" accept=".png" multiple id="sponsor" />
        <input type="hidden" class="textinput"  name="confirm-sponsor"/>
        </div>
    </div>
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Homepage Title'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Write Your Homepage Title Here'); ?>.</p>
        <div class="input-block">
        <input type="text" class="textinput"  name="title" value="<?php echo get_option('title'); ?>" id="title" />
        </div>
    </div>
    
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('HomePage Introduction'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Write Your Homepage Introduction Here'); ?>.</p>
        <div class="input-block">
        <input type="text" class="textinput"  name="intro" value="<?php echo get_option('intro'); ?>" id="intro" />
        </div>
    </div>
    
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Social Feeds'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Provide Your Social Feeds Here'); ?>.</p>
        <div class="input-block">
        <div id="twitter_feeds">
            <p class="explanation"><?php echo __('Twitter'); ?></p>
            <label style="clear: both;margin-right:-45px;">Timeline:</label>
            <input style="width: auto;" type="text" class="textinput"  name="twitter_timeline"  />
            <label style="clear: both;margin-right:-45px;">Buttons:</label>
            <input style="width: auto;" type="text" class="textinput"  name="twitter_button"  />
        </div>
        <div id="fb_feeds">
            <p class="explanation"><?php echo __('FaceBook'); ?></p>
            <label style="clear: both;margin-right:-45px;">Facebook :</label>
            <input style="width: auto;" type="text" class="textinput"  name="fb" />
        </div>
        </div>
    </div>
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Do you want to show your project introduction page? '); ?></label>
    </div>
    <div class="inputs five columns omega">
        <div class="input-block">
            <input type="checkbox" name="intro" value="page">
            <span class="explanation"> Active</span>
        </div>
    </div>
    
</div>
