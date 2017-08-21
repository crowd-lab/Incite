<div class="field">
    <div id="alertbox">
    <p class="explanation"><?php echo __('* Required Fields'); ?></p>
    </div>
    <div class="two columns alpha">
        <label id="logo_label" for="per_page"><?php echo __('Logo*'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Upload Your Logo Here (We only accept .png format images)'); ?>.</p>
        <p class="explanation"><?php echo __('Your current logo'); ?>.</p>
        <img src="<?php echo getFullOmekaUrl(); ?>../plugins/Incite/views/shared/images/customized_logo.png" height="100" width="400">
        <br>
        <br>
        <div class="input-block">
        <input type="file" class="textinput"  name="logo" accept=".png" id="logo" />
        </div>
    </div>

    <div class="two columns alpha">
        <label id="title_label" for="per_page"><?php echo __('Homepage Title*'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Write Your Homepage Title Here'); ?>.</p>
        <div class="input-block">
        <?php if (empty(get_option('title'))): ?>
        <input type="text" class="textinput"  name="title" value="Your project title/description here" id="title" />
        <?php else: ?>
        <input type="text" class="textinput"  name="title" value="<?php echo get_option('title'); ?>" id="title" />
        <?php endif; ?>
        </div>
    </div>
    
    <div class="two columns alpha">
        <label id="intro_label" for="per_page"><?php echo __('HomePage Introduction*'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Write Your Homepage Introduction Here'); ?>.</p>
        <div class="input-block">
        <?php if (empty(get_option('intro'))): ?>
        <textarea placeholder=""rows="15" cols="50" name="intro" id="intro">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla maximus velit sed felis gravida, et pellentesque libero posuere. Nullam augue mi, lacinia eu mauris iaculis, suscipit hendrerit elit. Ut consectetur nunc eget lorem venenatis, et vehicula nisl vestibulum. Vivamus vel aliquam lectus. Aliquam pulvinar dictum tellus a feugiat. Praesent a eros sed velit suscipit semper eu et orci. Donec elementum tempor sagittis. Duis orci nisl, semper ut erat ac, aliquet commodo purus. Morbi erat massa, dictum quis eleifend at, ornare vitae risus. </textarea>
        <?php else: ?>
        <textarea placeholder=""rows="15" cols="50" name="intro" id="intro"><?php echo get_option('intro'); ?></textarea>
        <?php endif; ?>
        </div>
    </div>

     <div class="two columns alpha">
        <label for="per_page"><?php echo __('Social Feeds'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Provide Your Social Feeds Here'); ?>.</p>
        <div class="input-block">
        <div id="twitter_feeds">
            <p class="explanation"><?php echo __('<strong>Twitter (Settings and privacy -> Widgets -> Create new)</strong>'); ?></p>
            <label style="clear: both;margin-right:-45px;">Timeline:</label>
            <?php if (empty(get_option('twitter_timeline'))): ?>
            <textarea placeholder=""rows="10" cols="50" name="twitter_timeline"><?php echo '&lt;a class="twitter-timeline" data-width="100%" data-height="220" data-theme="light" data-link-color="#2B7BB9    " href="https://twitter.com/July4CivilWar"&gt;Tweets by July4CivilWar&lt;/a&gt; &lt;script async src="//platform.twitter.com/widget    s.js" charset="utf-8"&gt;&lt;/script&gt'; ?> </textarea>
            <?php else: ?>
            <textarea placeholder=""rows="10" cols="50" name="twitter_timeline"><?php echo get_option('twitter_timeline'); ?></textarea>
            <?php endif; ?>
            <label style="clear: both;margin-right:-45px;">Buttons:</label>
            <?php if (empty(get_option('twitter_button'))): ?>
            <textarea placeholder=""rows="10" cols="50" name="twitter_button"><?php echo '&lt;a href="https://twitter.com/July4CivilWar" class="twitter-follow-button" data-show-count="false"&gt;Follow @July4CivilWar&lt;/a&gt;&lt;script async src="//platform.twitter.com/widgets.js" charset="utf-8"&gt;&lt;/script&gt'; ?> </textarea>
            <?php else: ?>
            <textarea placeholder=""rows="10" cols="50" name="twitter_button"><?php echo get_option('twitter_button'); ?></textarea>
            <?php endif; ?>
        </div>
        <div id="fb_feeds">
            <p class="explanation"><?php echo __('<strong>FaceBook (you can go to <a target="_blank" href="https://developers.facebook.com/docs/plugins">here</a> for help)</strong>'); ?></p>
            <label style="clear: both;margin-right:-45px;">Facebook :</label>
            <?php if (empty(get_option('fb'))): ?>
            <textarea placeholder=""rows="12" cols="50" name="fb"><?php echo '&lt;div class="fb-page" data-href="https://www.facebook.com/July4CivilWar/" data-tabs="timeline" data-width="500" data-height="220" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false"&gt;&lt;blockquote cite="https://www.facebook.com/July4CivilWar/" class="fb-xfbml-parse-ignore"&gt;&lt;a href="https://www.facebook.com/July4CivilWar/"&gt;Mapping the Fourth of July: Exploring Independence in the Civil War Era&lt;/a&gt;&lt;/blockquote>&lt;/div&gt'; ?> </textarea>
            <?php else: ?>
            <textarea placeholder=""rows="12" cols="50" name="fb"><?php echo get_option('fb'); ?></textarea>
            <?php endif; ?>
        </div>
        </div>
    </div>

    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Introduction Page'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <div class="input-block">
            <p class="explanation"><?php echo __('Choose to show or not show the about page by check or uncheck'); ?></p>
            <input type="checkbox" name="active" id="active" value="no">
            <span class="explanation"> Active</span>
        </div>
    </div>
    
    <div class="two columns alpha">
        <label id="theme_label" for="per_page"><?php echo __('Themes*'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <div id="connect">
            <p class="explanation"><?php echo __('We need at least <b>one</b> theme to make sure the connect task working'); ?>.</p>
            <label style="clear: both;margin-right:-45px;">Concept :</label>
            <input style="width: auto;" type="text" class="textinput"  name="concept_place" />
            <label style="clear: both;margin-right:-45px;">Definition :</label>
            <input style="width: auto;" type="text" class="textinput"  name="def_place" />
            <input type="button" id="add_button" value="add" />
            <input type="hidden"  name="encoded_concept" id="encoded_concept"/>
            <input type="hidden"  name="encoded_def" id="encoded_def"/>
        </div>
        <div id="c_table">
            <table style="width:300px">
                <tr>
                    <th>Concepts</th>
                    <th></th>
                </tr>
                
            </table>
        </div>
    </div>

    <!-- First sponsor -->
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Sponsor1'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Upload Your Sponsor Here (Please use the full url including http)'); ?>.</p>
        <?php if (empty(get_option('delete_sponsor1')) || get_option('delete_sponsor1') == "no"): ?>
        <div id="spon1">
        <p class="explanation"><?php echo __('Your current sponsor 1'); ?>.</p>
        <img src="<?php echo getFullOmekaUrl(); ?>../plugins/Incite/views/shared/images/customized_sponsors1.png" height="100" width="400">
        <input type="button" id="delete_button1" value="delete" />
        <br>
        <br>
        </div>
        <?php endif; ?>
        <input type="hidden"  name="delete_sponsor1" id="delete_sponsor1" value="<?php echo (empty(get_option('delete_sponsor1')) || get_option('delete_sponsor1') == 'no')? 'no' : 'yes' ?>"/>
        <div class="input-block">
        <label style="clear: both;margin-right:-75px;">Link: </label>
        <input style="width: auto;" type="text" class="textinput"  name="sponsorlink1" value="<?php echo get_option('sponsorlink1'); ?>" id="sponsorlink1" />
        <input type="file" class="textinput"  name="sponsor1" accept=".png" id="sponsor1" />
        </div>
    </div>
    <!-- Second sponsor -->
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Sponsor2'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Upload Your Sponsor Here (Please use the full url including http)'); ?>.</p>
        <?php if (empty(get_option('delete_sponsor2')) || get_option('delete_sponsor2') == "no"): ?>
        <div id="spon2">
        <p class="explanation"><?php echo __('Your current sponsor 2'); ?>.</p>
        <img src="<?php echo getFullOmekaUrl(); ?>../plugins/Incite/views/shared/images/customized_sponsors1.png" height="100" width="400">
        <input type="button" id="delete_button2" value="delete" />
        <br>
        <br>
        </div>
        <?php endif; ?>
        <input type="hidden"  name="delete_sponsor2" id="delete_sponsor2" value="<?php echo (empty(get_option('delete_sponsor2')) || get_option('delete_sponsor2') == 'no')? 'no' : 'yes' ?>"/>
        <div class="input-block">
        <label style="clear: both;margin-right:-75px;">Link: </label>
        <input style="width: auto;" type="text" class="textinput"  name="sponsorlink2" value="<?php echo get_option('sponsorlink2'); ?>" id="sponsorlink2" />
        <input type="file" class="textinput"  name="sponsor2" accept=".png" id="sponsor2" />
        </div>
    </div>
    <!-- Third sponsor -->
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Sponsor3'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Upload Your Sponsor Here (Please use the full url including http)'); ?>.</p>
        <?php if (empty(get_option('delete_sponsor3')) || get_option('delete_sponsor3') == "no"): ?>
        <div id="spon3">
        <p class="explanation"><?php echo __('Your current sponsor 3'); ?>.</p>
        <img src="<?php echo getFullOmekaUrl(); ?>../plugins/Incite/views/shared/images/customized_sponsors1.png" height="100" width="400">
        <input type="button" id="delete_button3" value="delete" />
        <br>
        <br>
        </div>
        <?php endif; ?>
        <input type="hidden"  name="delete_sponsor3" id="delete_sponsor3" value="<?php echo (empty(get_option('delete_sponsor3')) || get_option('delete_sponsor3') == 'no')? 'no' : 'yes' ?>"/>
        <div class="input-block">
        <label style="clear: both;margin-right:-75px;">Link: </label>
        <input style="width: auto;" type="text" class="textinput"  name="sponsorlink3" value="<?php echo get_option('sponsorlink3'); ?>" id="sponsorlink3" />
        <input type="file" class="textinput"  name="sponsor3" accept=".png" id="sponsor3" />
        </div>
    </div>
    <!-- Fourth sponsor -->
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Sponsor4'); ?></label>
    </div>
    <div class="inputs five columns omega">
        <p class="explanation"><?php echo __('Upload Your Sponsor Here (Please use the full url including http)'); ?>.</p>
        <?php if (empty(get_option('delete_sponsor4')) || get_option('delete_sponsor4') == "no"): ?>
        <div id="spon4">
        <p class="explanation"><?php echo __('Your current sponsor 4'); ?>.</p>
        <img src="<?php echo getFullOmekaUrl(); ?>../plugins/Incite/views/shared/images/customized_sponsors1.png" height="100" width="400">
        <input type="button" id="delete_button4" value="delete" />
        <br>
        <br>
        </div>
        <?php endif; ?>
        <input type="hidden"  name="delete_sponsor4" id="delete_sponsor4" value="<?php echo (empty(get_option('delete_sponsor4')) || get_option('delete_sponsor4') == 'no')? 'no' : 'yes' ?>"/>
        <div class="input-block">
        <label style="clear: both;margin-right:-75px;">Link: </label>
        <input style="width: auto;" type="text" class="textinput"  name="sponsorlink4" value="<?php echo get_option('sponsorlink4'); ?>" id="sponsorlink4" />
        <input type="file" class="textinput"  name="sponsor4" accept=".png" id="sponsor4" />
        </div>
    </div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
var concept_arr= [];
<?php if(!empty(get_option('encoded_concept'))): ?>
    concept_arr = <?php echo get_option('encoded_concept'); ?>;
    $("#encoded_concept").val(JSON.stringify(concept_arr));
<?php endif; ?>
var def_arr = [];
<?php if(!empty(get_option('encoded_concept'))): ?>
    def_arr = <?php echo get_option('encoded_def'); ?>;
    $("#encoded_def").val(JSON.stringify(def_arr));
<?php endif; ?>
$(document ).ready(function() {
    activeCheck();
    getConcept();
    $('[data-toggle="popover"]').popover(); 
    $("#install_plugin").click(function(event){
        if ($("#sponsor1").val() != "") {
            $("#delete_sponsor1").val('no');
        }
        if ($("#sponsor2").val() != "") {
            $("#delete_sponsor2").val('no');
        }
        if ($("#sponsor3").val() != "") {
            $("#delete_sponsor3").val('no');
        }
        if ($("#sponsor4").val() != "") {
            $("#delete_sponsor4").val('no');
        }
        var num_of_delete = 0;
        jQuery.each($(".delete_button"), function() {
            num_of_delete += (($(this).val() == "undo") ? 1 : 0);
        });
        if ($("#title").val() != "" && $("#intro").val() != "" && ($("#c_table td").length != 0 && num_of_delete < $("#c_table tr").length - 1) && ($("#logo").val() != "" || <?php echo json_encode(get_option('logo_set')); ?> == "true")) {
            //submit button working fine
        }
        else {
            event.preventDefault();
            var error_message = "";
            if ($("#title").val() == "") {
                error_message += "*Title is empty </br>";
                $("#title_label").css("color", "red");
            }
            if ($("#intro").val() == "") {
                error_message += "*Introduction is empty</br>";
                $("#intro_label").css("color", "red");
            }
            
            if ($("#c_table td").length == 0 || num_of_delete == $("#c_table tr").length - 1) {
                error_message += "*Theme table is empty</br>";
                $("#theme_label").css("color", "red");
            }
            if ($("#logo").val() == "" && (<?php echo json_encode(get_option('logo_set')); ?> == "false" || <?php echo json_encode(get_option('logo_set')); ?> == null)) {
                error_message += "*Logo is empty</br>";
                $("#logo_label").css("color", "red");
            }
            if ($("#alert").length == 0)
                $("#alertbox").append('<p id="alert" style = "border-style: solid; color: red;">' + error_message + '</div>');
            else {
                $("#alert").remove();
                $("#alertbox").append('<p id="alert" style = "border-style: solid; color: red;">' + error_message + '</div>');
            }
            window.scrollTo(0, 0);
        }
    });
    del_sponsor_check();
    $("#add_button").prop("disabled",true);
    $("#active").click(function(){
        if ($('#active:checkbox:checked').length > 0) {
            $("#active").val("yes");
        }
        else {
            $("#active").val("no");
        }
    });
    $("input[name = 'concept_place']").change(function() {
        if ($("input[name = 'concept_place']").val() != "" && $("input[name = 'def_place']").val() != "") {
            $("#add_button").prop("disabled",false);
        }
        if ($("input[name = 'concept_place']").val() == "" || $("input[name = 'def_place']").val() == "") {
            $("#add_button").prop("disabled",true);
        }
    });
    $("input[name = 'def_place']").change(function() {
        if ($("input[name = 'concept_place']").val() != "" && $("input[name = 'def_place']").val() != "") {
            $("#add_button").prop("disabled",false);
        }
        if ($("input[name = 'concept_place']").val() == "" || $("input[name = 'def_place']").val() == "") {
            $("#add_button").prop("disabled",true);
        }
    });
    $("#add_button" ).click(function() {
        var concept = $("input[name = 'concept_place']").val();
        var def = $("input[name = 'def_place']").val();
        concept_arr.push(concept);
        def_arr.push(def);
        $("input[name = 'concept_place']").val("");
        $("input[name = 'def_place']").val("");
        var concept_element = "<input type='hidden' value='" + concept + "' name='concept[]' />";
        var def_element = "<input type='hidden' value='" + def + "' name='def[]' />";
        var trash_button = '<td><input type="button" class="delete_button" value="delete" /></td>';
        var popover = '<td><a tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="left" title="Defination" data-content="' + def + '">' + concept + '</a>'
        $("#c_table table").append("<tr>" + popover + concept_element + def_element + "</td>" + trash_button + "</tr>");
        $("#encoded_concept").val(JSON.stringify(concept_arr));
        $("#encoded_def").val(JSON.stringify(def_arr));
        $("#add_button").prop("disabled",true);
        $('[data-toggle="popover"]').popover(); 
    });
    $("#c_table").on("mouseover", "td", function(){
        $($(this).find(".defs")).css("display", "inline");
        $($(this).find(".defs")).css("background", "#fffAF0");
    });
    $("#c_table").on("mouseleave", "td", function(){
        $($(this).find(".defs")).css("display", "none");
        $($(this).find(".defs")).css("background", "#fff");
    });
    $(document).on("click", ".delete_button", function(){
        if ($(this).val() == "delete") {
            var first_td = $($($($($(this).parent()).parent()).children().first())[0]).html();
            var con_and_def = $($($($($(this).parent()).parent()).children().first())[0]).text();
            var index_of_concept = first_td.indexOf("<span");
            var concept = first_td.substring(0, index_of_concept);
            removeConcept(concept);
            var def = con_and_def.substring(concept.length + 1, con_and_def.length);
            var delete_concept_element = "<input type='hidden' value='" + concept + "' name='del_concept[]' />";
            var delete_def_element = "<input type='hidden' value='" + def + "' name='del_def[]' />";
            var strike = "<strike>" + first_td + "</strike>" + delete_concept_element + delete_def_element;
            $($($($($(this).parent()).parent()).children().first())[0]).html(strike);
            $(this).val("undo");
        }
        else {
            var first_td = $($($($($(this).parent()).parent()).children().first())[0]).html();
            //select the concept
            var con_and_def = $($($($($(this).parent()).parent()).children().first())[0]).text();
            var index_of_concept = first_td.indexOf("<span");
            var concept = first_td.substring(0, index_of_concept);
            concept = concept.replace('<strike>','');
            var def = con_and_def.substring(concept.length + 1, con_and_def.length);
            concept_arr.push(concept);
            def_arr.push(def);
            //cut the strike tag
            var strike = first_td.replace('<strike>','');
            strike = strike.replace('</strike>','');
            var index_of_hidden_input = first_td.indexOf("<input");
            strike = strike.substring(0, index_of_hidden_input);
            $($($($($(this).parent()).parent()).children().first())[0]).html(strike);
            $(this).val("delete");
        }
        $("#encoded_concept").val(JSON.stringify(concept_arr));
        $("#encoded_def").val(JSON.stringify(def_arr));
    });
});

function getConcept() {
    var con_arr;
     <?php if(!empty(get_option('encoded_concept'))): ?>
         con_arr = <?php echo get_option('encoded_concept'); ?>;
     <?php endif; ?>
    var def_arr;
    <?php if(!empty(get_option('encoded_def'))): ?>
         def_arr = <?php echo get_option('encoded_def'); ?>;
    <?php endif; ?>
    <?php if(!empty(get_option('encoded_concept'))): ?>
    for (var i = 0; i < con_arr.length; i++) {
        if (con_arr[i] != null) {
            var trash_button = '<td><input type="button" class="delete_button" value="delete" /></td>';
            var popover = '<td><a tabindex="0" data-toggle="popover" title="Defination" data-trigger="focus" data-placement="left"  data-content="' + def_arr[i] + '">' + con_arr[i] + '</a></td>'
            $("#c_table table").append("<tr>" + popover + trash_button + "</tr>");
        
        }
    }
    <?php endif; ?>
}

function removeConcept(concept) {
    var index = -1;
    for (var i = 0; i < concept_arr.length; i++) {
        if (concept_arr[i] == concept){
            delete concept_arr[i];
            index = i;
        }
    }
    if (index != -1) {
        delete def_arr[index];
    }
}

function activeCheck () {
    var checked = <?php echo json_encode(get_option('active')); ?>;
    if (checked == "yes")
        $('#active').prop('checked', true);
    else
        $('#active').prop('checked', false);
}

function del_sponsor_check() {
    $("#delete_button1").click(function(){
        $("#delete_sponsor1").val("yes");
        $("#spon1").remove();
        $("#sponsorlink1").val("");
    });
    $("#delete_button2").click(function(){
        $("#delete_sponsor2").val("yes");
        $("#spon2").remove();
        $("#sponsorlink2").val("");
    });
    $("#delete_button3").click(function(){
        $("#delete_sponsor3").val("yes");
        $("#spon3").remove();
        $("#sponsorlink3").val("");
    });
    $("#delete_button4").click(function(){
        $("#delete_sponsor4").val("yes");
        $("#spon4").remove();
        $("#sponsorlink4").val("");
    });
}

</script>
<style>
.popover-content {
    word-break: break-all;
}

table tr td {
    padding: 5px 5px;
}
</style>


