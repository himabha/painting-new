<form action="/action_page.php" id="glass_form">
    <div class="woocommerce-message" role="alert">
    <a href="cart" tabindex="1" class="button wc-forward">View cart</a><span class="message"></span>
</div>
<div class="painting-container two_sections">
    <div class="left_section">

            <input type="hidden" name="product_id" id="product_id" value="<?php echo $productID;?>">
            <input type="hidden" name="frame_selected" id="frame_selected" value="">
            <div class="form-group">
                <?php
                    $options = "<option value=''>".$helper->getHebrewText('choose_one')."</option>";
                    $glasses = $jsondata['categories']['glass'];
                    if (isset($glasses) && !empty($glasses)) {
                        ?>
                <label for="glass_width"><?php echo $helper->getHebrewText('select_glass'); ?>: </label>
                <select name="glass_type" class="glass_type" id="glass_type">
                    <?php
            foreach ($glasses as $index => $glass) {
                echo $index;
                $glass = $helper->getHebrewText($index);
                $options .= '<option value="'.$index.'">'.$glass.'</option>';
            }
        echo $options; ?>
                </select>
                <?php
    }
    ?>
            </div>

            <!--<div class="form-group fieldsdisplay" id="glass_thickness_div"></div>-->

            <div class="form-group thickness_dropdown">
                <label for='glass_thickness'><?php echo $helper->getHebrewText('select_thickness');?>: </label>
                <select id='glass_thickness' class="glass_thickness" onchange='javascript:onthicknesschange()'
                    name='glass_thickness'>
                    <option value=''><?php echo $helper->getHebrewText('choose_one');?></option>
                </select>
            </div>

            <div class="form-group thickness_line">
                <label for="glass_width"><?php echo $helper->getHebrewText('thickness');?>: <span
                        id="thickness_content"></span></label><input type="hidden" name="glass_thickness"
                    class="glass_thickness" id="glass_thickness" value="" />
            </div>

            <div class="form-group">
                <input name="glass_width" placeholder="<?php echo $helper->getHebrewText('width');?>" class="glass_size" id="glass_width" type="number" pattern="[0-9]"/>
                <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
            </div>
            <div class="form-group">
                <input name="glass_height" placeholder="<?php echo $helper->getHebrewText('height');?>"
                    class="glass_size" id="glass_height" type="number" pattern="[0-9]"/>
                <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
            </div>
            <div class="form-group frame_required">
        		<label>
                    <input type="checkbox" name="frame_required" id="frame_required" value="" onclick="onframerequired(this)"; />
                    <?php echo $helper->getHebrewText('with_frame');?>
                </label>
        	</div>
            <div class="form-group">
                <span class="glass_error" id="glass_size_error"></span>
            </div>

    </div>
    <div class="mid_section"></div>
    <div class="right_section">
        <div class="frame_info">
            <?php include(plugin_dir_path(dirname(__FILE__)).'frames/frame-grid.php'); ?>
            <div class="info"><?php echo $helper->getHebrewText('all_glasses_are_polished');?></div>
            <div class="notice"><?php echo $helper->getHebrewText('shipment_cost_will_be_priced_seperately');?></div>
        </div>
        <div class="frame_bot">
            <div class="form-group field_quantity item">
                <label for="glass_quantity"><?php echo $helper->getHebrewText('quantity'); ?>:</label>
                <input class="style2 value" id="glass_quantity" min="1" value="1" type="number" name="glass_quantity" onchange="onquantitychange(this)" onkeyup="onquantitychange(this)" pattern="[0-9]"/>
            </div>
            <div class="form-group item">
                <label><?php echo $helper->getHebrewText('total_price');?>:</label>
                <span class="value" id="price"></span>
                <input type="hidden" name="input_price" id="input_price" value="" />
            </div>
        </div>
        <div class="frame_action">
			<button type="button" class="green" id="add_to_cart"><i class="fa fa-plus"></i> &nbsp; <span><?php echo $helper->getHebrewText('add_to_cart');?></span></button>
		</div>
    </div>
</div>
</form>
<script>
function onquantitychange(elem) {
    calc();
}

function calc() {
    var page = $("#glass_type").val();
    var glass_width = $("#glass_width").val();
    var glass_height = $("#glass_height").val();
    var thickness = $(".glass_thickness.glass_enabled").val();
    var quantity = $("#glass_quantity").val();
    if ((typeof glass_width != 'undefined' && glass_width > 0) && (typeof glass_height != 'undefined' && glass_height > 0)) {
        $.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>',
            function(data) {
                if (typeof data.categories.glass[page] != 'undefined') {
                    $(data.categories.glass[page].sizes).each(function(index) {
                        var size = this;
                        if (size.thickness_by_mm == thickness) {
                            var max_max_dimensions = Math.max.apply(null, size.max_dimensions);
                            var min_max_dimensions = Math.min.apply(null, size.max_dimensions);
                            var max_min_dimensions = Math.max.apply(null, size.min_dimensions);
                            var min_min_dimensions = Math.min.apply(null, size.min_dimensions);

                            //Checking for min width or height -> start here
                            if (glass_width < min_min_dimensions || glass_height < min_min_dimensions ||
                                glass_width > max_max_dimensions || glass_height > max_max_dimensions) {
                                $("#glass_size_error").css("display", "block");
                                $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                                    $("#glass_size_error").html(data['size_can_not_be_less_than'] + " " + size.min_dimensions[0] + "X" + size.min_dimensions[1] + " " +  data['and_can_not_be_more_than'] + " " + size.max_dimensions[0] + "X" + size.max_dimensions[1]);
                                });
                                $("#price").html("");
                                $("#input_price").val(0);
                                $("#add_to_cart").css("display", "none");
                                return false;
                            } else if (((glass_width >= min_min_dimensions && glass_width <
                                    max_min_dimensions) && glass_height < max_min_dimensions) || ((
                                    glass_height >= min_min_dimensions && glass_height <
                                    max_min_dimensions) && glass_width < max_min_dimensions)) {
                                $("#glass_size_error").css("display", "block");
                                $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                                    $("#glass_size_error").html(data['size_can_not_be_less_than'] + " " + size.min_dimensions[0] + "X" + size.min_dimensions[1] + " " + data['and_can_not_be_more_than'] + " " + size
                                    .max_dimensions[0] + "X" + size.max_dimensions[1]);
                                });
                                $("#price").html("");
                                $("#input_price").val(0);
                                $("#add_to_cart").css("display", "none");
                                return false;
                            } else {
                                $("#glass_size_error").html("");
                            }
                            var calc_price = size.price / 10000 * (glass_width * glass_height);
                            if (calc_price < size.min_price) {
                                calc_price = size.min_price;
                            }
                            $("#price").html(Math.round(calc_price) * quantity);
                            $("#input_price").val(Math.round(calc_price) * quantity);
                            $("#add_to_cart").css("display", "block");
                            window.glass_price = Math.round(calc_price)*quantity;
                            if($("#frame_required").is(":checked") == true)
        					{
        						frame_calc();
        					}
                        }
                    });

                }
            });
    }
}

function onthicknesschange() {
    //$("#glass_width").val("");
    //$("#glass_height").val("");
    $("#price").html("");
    $("#input_price").val(0);
    $("#add_to_cart").css("display", "none");
    //$(".fieldsdisplay").css("display", "block");
    calc();
}

function onframerequired(elem)
{
	if($(elem).is(":checked") == true)
	{
		$(".frame_grid").css("display", "block");
		//onframechose($(".frame_img:eq(0)"));
	}
	else{
		$(".frame_grid").css("display", "none");
		$("#frame_selected").val("");
        $(".frame_img").removeClass("selected");
        $(".frame_grid, .selected_frame_detail").css("display", "none");
        $("#selected_frame_name, #selected_frame_description, #selected_frame_color, #selected_frame_type, #selected_frame_price").html("");
	}
	calc();
}

function frame_calc(){
    var frame_type = $("#frame_type").val();
    if(frame_type != "from_catalogue")
    {
        var frame_width = parseInt($("#glass_width").val());
        var frame_height = parseInt($("#glass_height").val());
        var frame_extra = $("#frame_extra").val();
        var frame_cover = $("#frame_cover").val();
        var frame_colors = $("#frame_colors").val();
        var quantity = $("#glass_quantity").val();
        var frame_selected = $("#frame_selected").val();
        if(typeof frame_selected != "undefined" && frame_selected != "")
        {
            if(typeof frame_width != 'undefined' && frame_width > 0 && typeof frame_height != 'undefined' && frame_height > 0)
            {
                frame_selected = JSON.parse(frame_selected);
                var price = frame_selected.price * (frame_width + frame_height) * 2 / 100;
                $.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
                    if(typeof data.categories.frames != 'undefined')
                    {
                        if(price < data.categories.frames.sizes[0].min_price)
                        {
                            price = data.categories.frames.sizes[0].min_price;
                        }

                        $("#price").html(parseInt(window.glass_price)+Math.round(quantity*price));
                        $("#input_price").val(parseInt(window.glass_price)+Math.round(quantity*price));
                        $("#add_to_cart").css("display", "block");
                    }

                });
            }
        }
        else{
            $("#price").html(parseInt(window.glass_price));
            $("#input_price").val(parseInt(window.glass_price));
            $("#add_to_cart").css("display", "block");
        }
    }
}

$(document).ready(function() {
    $("#glass_type").on("change", function() {
        var val = $(this).val();
        if (val != '') {
            $.getJSON("<?php echo plugins_url('types.json', dirname(__FILE__));?>",
                function(data) {
                    var content = data.categories.glass[val];
                    if (typeof content.sizes != "undefined") {
                        /*if(content.sizes.length > 1)
                        {
                        	var thickness = "<label for='glass_thickness'>Select thickness: </label><select id='glass_thickness' onchange='javascript:onthicknesschange()' name='glass_thickness'><option value=''>Select thickness</option>";
                        	$(content.sizes).each(function(index){
                        		thickness += "<option value="+this.thickness_by_mm+">"+this.thickness_by_mm+"mm</option>";
                        	});
                        	thickness += "</select>";
                        	$("#glass_thickness_div").html(thickness);
                        	$(".fieldsdisplay").css("display", "none");
                        	$("#glass_thickness_div").css("display", "block");
                        }
                        else{
                        	$("#glass_thickness_div").html('<label for="glass_width">Thickness: '+content.sizes[0].thickness_by_mm+'mm</label><input type="hidden" name="glass_thickness" class="glass_thickness" id="glass_thickness" value="'+content.sizes[0].thickness_by_mm+'"/>');
                        	$(".fieldsdisplay").css("display", "block");
                        }*/
                        if (content.sizes.length > 1) {
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                            var thickness = "";
                            $(content.sizes).each(function(index) {
                                thickness += "<option value=" + this.thickness_by_mm + ">" +
                                    this.thickness_by_mm + data['mm'] + "</option>";
                            });
                            thickness += "</select>";
                            $(".glass_thickness").removeClass("glass_enabled");
                            $(".glass_thickness:eq(0)").addClass("glass_enabled");
                            $(".glass_thickness:eq(0) option").not($(
                                ".glass_thickness:eq(0) option:eq(0)")).remove();
                            $(".glass_thickness:eq(0)").append(thickness);
                            $(".thickness_line").css("display", "none");
                            $(".thickness_dropdown").css("display", "block");
                            //$(".fieldsdisplay").css("display", "none");
                            //$("#glass_thickness_div").css("display", "block");
                            });
                        } else {
                            $("#thickness_content").html(content.sizes[0].thickness_by_mm + "mm");
                            $(".glass_thickness").removeClass("glass_enabled");
                            $(".glass_thickness:eq(1)").addClass("glass_enabled");
                            $(".glass_thickness:eq(1)").val(content.sizes[0].thickness_by_mm);
                            $(".thickness_line").css("display", "block");
                            $(".thickness_dropdown").css("display", "none");
                            /* $(".fieldsdisplay").css("display", "block");
                            $("#glass_finishing").val("");
                            $("#choose_frame").val("");
                            $("#choose_frame").css("display", "none");
                            $("#glass_size").val("");
                            $("#glass_size_div").css("display", "none"); */
                        }
                        calc();
                    }
                });
            //$("#glass_width").val("");
            //$("#glass_height").val("");
            $("#price").html("");
            $("#input_price").val(0);
            $("#add_to_cart").css("display", "none");
            //calc();
        } else {
            $("#glass_width").val("");
            $("#glass_height").val("");
            $("#price").html("");
            $("#input_price").val(0);
            $("#add_to_cart").css("display", "none");
            $(".fieldsdisplay").css("display", "none");
        }

    });

    $(".glass_size").on("focus", function() {
        $("#price").html("");
        $("#input_price").val(0);
        $("#add_to_cart").css("display", "none");
    });

    $(".glass_size").on("keyup blur", function() {
        var id = $(this).attr("id");
        var splittedVal = $("#" + id).val().split(".");
        if (splittedVal.length > 1) {
            $("#" + id).val(splittedVal[0]);
        }
        calc();
    });
});
$("#add_to_cart").on("click", function() {
    var formdata = $("#glass_form").serializeArray();
    formdata.push({
        name: 'glass_thickness_enabled',
        value: $(".glass_thickness.glass_enabled").val()
    });
    $.ajax({
        url: "<?php echo plugin_dir_url(__FILE__).'add_to_woocommerce.php';?>",
        data: formdata,
        type: 'POST',
        success: function(result) {
            /*//$.ajax({
                url: '?wc-ajax=add_to_cart',
                data: {product_sku: '', product_id: <?php echo $productID; ?>, quantity: 1},
                type: 'POST',
                success: function(result){
                    console.log(result);
                }
            });*/
            var result = JSON.parse(result);
            if(result.success == "true"){
                if(result.status !== "true"){
                    $(".woocommerce-message").css("display", "block");
                    $(".cart-contents-count").text(result.count);
                    $(".woocommerce-message .message").html('"' + result.name +
                        '" has been added to your cart.');
                    $('html, body').animate({
                        scrollTop: $(".woocommerce-message").offset().top
                    }, 1000)
                } else {
                    $(".woocommerce-message").css("display", "block");
                    $(".woocommerce-message .message").html('"' + result.name +
                        '" has been updated to your cart.');
                    $('html, body').animate({
                        scrollTop: $(".woocommerce-message").offset().top
                    }, 1000)
                }
            }
        }
    });
});
</script>
<style>
.glass_error {
    display: block;
    color: red;
}

.thickness_line {
    display: none;
}
.frame_grid{
    display: none;
}
</style>
