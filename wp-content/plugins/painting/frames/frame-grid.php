<?php

include_once(dirname(__FILE__).'/../config.php');

global $wpdb;

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    $http = "https://";
} else {
    $http = "http://";
}

// prepare and bind

$results = $wpdb->get_results("SELECT * from frames where active = 1 order by sort asc");

if (empty($results)) {
    exit('No rows');
}

?>
<div class="frame_grid">
<!-- Frame starts here -->

<!-- Button trigger frame grid popup -->
<button type="button" data-toggle="modal" data-target="#frameGridModal">
    <?php echo $helper->getHebrewText('select_frame');?>
</button>

<!-- Frame Grid PopPup -->
<div class="modal fade" id="frameGridModal" tabindex="-1" role="dialog" aria-labelledby="frameGridModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="frame_popup">
                <div class="form-group choose_frame_field">
                    <div class="form-group">
                        <label for="filter_frame_type"><?php echo $helper->getHebrewText('select_frame_type');?>:
                        </label>
                        <select name="filter_frame_type" class="filter_frame_type" id="filter_frame_type">
                            <?php
            $options = "<option value=''>".$helper->getHebrewText('choose_one')."</option>";
            $frames = $jsondata['categories']['frames']['types'];

            if (isset($frames) && !empty($frames)) {
                foreach ($frames as $key_frame => $frame) {
                    if ($frame != 'from_catalogue') {
                        $newframe= $helper->getHebrewText($frame);
                        $options .= '<option value="'.$frame.'">'.$newframe.'</option>';
                    }
                }
            }

            echo $options;

            ?>

                        </select>

                    </div>

                    <div class="form-group field_color">

                        <label for="filter_frame_colors"><?php echo $helper->getHebrewText('select_color');?>:
                        </label>

                        <select name="filter_frame_colors" class="filter_frame_colors" id="filter_frame_colors">

                            <?php

            $options = "<option selected='selected' value=''>".$helper->getHebrewText('choose_one')."</option>";

            $colors = $jsondata['categories']['frames']['sizes'][0]['colors'];

            if (isset($colors) && !empty($colors)) {
                foreach ($colors as $key_color => $color) {
                    $color_lang = $helper->getHebrewText($color);
                    $options .= '<option value="'.$color.'">'.$color_lang.'</option>';
                }
            }

            echo $options;

            ?>

                        </select>

                    </div>





                </div>

                <div class="table" id="table">

                    <?php

    $i = 0;

    foreach ($results as $row) {
        $data = json_encode($row);

        $row = (array)$row;

        if ($i % 3 == 0) {
            ?>

                    <div class="tr">

                        <?php
        }

        $images = json_decode($results[$i]->img_path);
        $needle = '-p';
        $primary = key(array_filter($images, function($var) use ($needle){
            return strpos($var, $needle) !== false;
        }));

        $needle = '-s';
        $secondary = key(array_filter($images, function($var) use ($needle){
            return strpos($var, $needle) !== false;
        }));
        ?>

        <div class="td">
            <img onclick="javascript:onframechose(this, true)" width="150px" height="150px" class="frame_img" src="<?php echo plugins_url($images[$primary], dirname(__FILE__)); ?>" />
            <input type="hidden" name="frame_secondary_image_<?php echo $i; ?>" id="frame_secondary_image_<?php echo $i; ?>" value="<?php echo plugins_url($images[$secondary], dirname(__FILE__)); ?>"/>
            <input type="hidden" class="frame_data" name="frame_data_<?php echo $i; ?>" id="frame_data_<?php echo $i; ?>" value='<?php echo $data; ?>' />
        </div>

        <?php

        if ($i > 0 && (($i+1) % 3 == 0 || ($i+1) == count($results))) {
            ?>

            </div>

            <?php
        }

        $i++;
    }

    ?>

                </div>
            </div>

        </div>
    </div>
</div>

<div class="selected_frame_detail frame_table hide_on_cat" style="display:none;">
    <h3><?php echo $helper->getHebrewText('selected_frame_detail');?>
    </h3>
    <ul>
        <li>
            <label><?php echo $helper->getHebrewText('frame_name');?>
            </label>
            <div id="selected_frame_name" class="value"></div>
        </li>
        <li>
            <label><?php echo $helper->getHebrewText('frame_description');?>
            </label>
            <div id="selected_frame_description" class="value"></div>
        </li>
        <li>
            <label><?php echo $helper->getHebrewText('frame_color');?>
            </label>
            <div id="selected_frame_color" class="value"></div>
        </li>
        <li>
            <label><?php echo $helper->getHebrewText('frame_type');?>
            </label>
            <div id="selected_frame_type" class="value"></div>
        </li>
        <li>
            <label><?php echo $helper->getHebrewText('frame_price');?>
            </label>
            <div id="selected_frame_price" class="value"></div>
        </li>
    </ul>
</div>
<!-- Frame ends here -->
</div>
<script>
function onframechose(elem, is_allow) {
    var frame_data = ($("#frame_data_" + $(".frame_img").index(elem)).val());
    $("#frame_selected").val(frame_data);
    $(".frame_img").removeClass("selected");
    $(elem).addClass("selected");
    $(".frame_chosen").remove();
    $(".selected_frame_detail").css("display", "block");
    var parseJson = JSON.parse(frame_data);
    if(typeof $("#print_type") !== "undefined" && $("#print_type").val() === 'paper')
    {
        var sec_image = $("#frame_secondary_image_" + $(".frame_img").index(elem)).val();
        var frame1 = document.getElementById("image_content");
        frame1.style.borderImage = "url(" + sec_image + ") 38 round";
        imgInFrame = document.getElementById("print_image");
        if (imgInFrame.height == 0) {
            frame1.style.width = "275px";
            frame1.style.height = "275px";
        } else {
            frame1.style.width = imgInFrame.width + 30 + "px";
            frame1.style.height = imgInFrame.height + 30 + "px";
        }
    }


    $("#selected_frame_name").html(parseJson.name);
    $("#selected_frame_description").html(parseJson.description);
    $("#selected_frame_color").html(parseJson.color);
    $("#selected_frame_type").html(parseJson.type);
    $("#selected_frame_price").html(parseJson.price);
    calc();
    if(is_allow){
        $("#frameGridModal").modal("hide");
    }
}

$(document).ready(function() {
    //onframechose($(".frame_img:eq(0)"), false);
    $("#filter_frame_type, #filter_frame_colors").on("change", function() {
        $("#frame_selected").val("");
        var type = $("#filter_frame_type").val();
        var color = $("#filter_frame_colors").val();
        $.ajax({
                url: "<?php echo plugins_url('getFrames.php', __FILE__); ?>",
                data: {
                    type: type,
                    color: color
                },
                type: 'POST',
                success: function(result)
                {
                    $("#selected_frame_name, #selected_frame_description, #selected_frame_color, #selected_frame_type, #selected_frame_price").html("");
                    if (result != "")
                    {
                        $("#table").html(result);
                        //onframechose($(".frame_img:eq(0)"), false);
                    } else
                    {
                        $("#table").html("No rows");
                        $("#frame_selected").val("");
                    }
                    calc();
                },
                error: function(error)
                {
                }
            }
        );
    })
})
</script>
<style>
.selected_frame_detail{
    display: none;
}
</style>
