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
        <label for="per_page"><?php echo __('Show your project introduction page? '); ?></label>
    </div>
    <div class="inputs five columns omega">
        <div class="input-block">
            <input type="checkbox" name="intro" value="page">
            <span class="explanation"> Active</span>
        </div>
    </div>
    <div class="two columns alpha">
        <label for="per_page"><?php echo __('Themes '); ?></label>
    </div>
    <div class="inputs five columns omega">
        <div id="connect">
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
                    <th>Operation</th>
                </tr>
                
            </table>
        </div>
    </div>
    
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
    getConcept();
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
        $("#c_table table").append("<tr><td>" + concept + "<span class='defs' style='display: none'> " + def + "</span>" + concept_element + def_element + trash_button + "</tr>");
        $("#encoded_concept").val(JSON.stringify(concept_arr));
        $("#encoded_def").val(JSON.stringify(def_arr));
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
            $("#c_table table").append("<tr><td>" + con_arr[i] + "<span class='defs' style='display: none'> " + def_arr[i] + "</span></td>" + trash_button + "</tr>");
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

</script>


