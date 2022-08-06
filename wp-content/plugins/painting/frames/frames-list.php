<?php

include_once(dirname(__FILE__) . '/../config.php');
global $wpdb;

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    $http = "https://";
} else {
    $http = "http://";
}

// prepare and bind

$results = $wpdb->get_results("SELECT * from frames where active = 1 order by sort asc");
$types = [];
foreach ($results as $frame) {
    $types[$frame->type][] = $frame;
}

if (empty($results)) {
    exit('No rows');
}
?>
<?php
$frames = $jsondata['categories']['frames']['types'];
if (isset($frames) && !empty($frames)) {
    $i = 0;
    foreach ($types as $key_frame => $frames) {
?>
        <h4 class="list-changes"> <?= $key_frame ?> <i class="fas fa-angle-down"></i></h4>
        <p class="list-dec">קולקציה זו כוללת בתוכה מסגרות עץ ופוליאריטן מסוגננות ובעלות עיטורים שמתאימה בעיקר למסגור עבודות/איורים קלאסיים כאשר רוצים להוסיף מימד של יוקרה למסגור עצמו </p>
        <?php
        
        foreach ($frames as $frame) {
            $images = json_decode($frame->img_path);
            $needle = '-p';
            $primary = key(array_filter($images, function ($var) use ($needle) {
                return strpos($var, $needle) !== false;
            }));

            $needle = '-s';
            $secondary = key(array_filter($images, function ($var) use ($needle) {
                return strpos($var, $needle) !== false;
            }));
            $data = json_encode($frame);
        ?>
            <div class="img-select">
                <img onclick="javascript:onframechose(this, true)" class="frame_img img-fluid" src="<?php echo plugins_url($images[$primary], dirname(__FILE__)); ?>">
                <input type="hidden" name="frame_secondary_image_<?php echo $i; ?>" id="frame_secondary_image_<?php echo $i; ?>" value="<?php echo plugins_url($images[$secondary], dirname(__FILE__)); ?>" />
                <input type="hidden" class="frame_data" name="frame_data_<?php echo $i; ?>" id="frame_data_<?php echo $i; ?>" value='<?php echo $data; ?>' />
            </div>
            <h4 class="list-type mt-2"><i class="fas fa-info-circle font-i"></i><?= $frame->name ?></h4>
            <h5 class="img-type"><?= $frame->description ?></h5>

            <div class='tabs mt-2'>
                <input type="radio" name="tab" id="tab2" role="tab" checked>
                <label for="tab2" id="tab2-label"><?= $frame->color ?></label>
            </div>
<?php
            $i++;
        }
    }
}
?>
<script>
    function onframechose(elem, is_allow) {
        var frame_data = ($("#frame_data_" + $(".frame_img").index(elem)).val());
        $("#frame_selected").val(frame_data);
        $(".frame_img").removeClass("selected");
        $(elem).addClass("selected");
        $(".frame_chosen").remove();
        var print_type = $(".print_type:checked").val();
        var parseJson = JSON.parse(frame_data);
        if (print_type === 'paper') {
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
        if (is_allow) {
            $("#frameGridModal").modal("hide");
        }
    }

    /*  $(document).ready(function() {
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
                 success: function(result) {
                     $("#selected_frame_name, #selected_frame_description, #selected_frame_color, #selected_frame_type, #selected_frame_price").html("");
                     if (result != "") {
                         $("#table").html(result);
                         //onframechose($(".frame_img:eq(0)"), false);
                     } else {
                         $("#table").html("No rows");
                         $("#frame_selected").val("");
                     }
                     calc();
                 },
                 error: function(error) {}
             });
         })
     }) */
</script>
<style>
    .img-select {
        text-align: center;
    }

    .selected_frame_detail {
        display: none;
    }
</style>