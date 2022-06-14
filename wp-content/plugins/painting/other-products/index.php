<form action="/action_page.php" id="other_products_form">
    <div class="woocommerce-message" role="alert">
<a href="cart" tabindex="1" class="button wc-forward">View cart</a><span class="message"></span></div>
<div class="painting-container two_sections">
	<div class="left_section">
		<input type="hidden" name="product_id" id="product_id" value="<?php echo $productID;?>">
		<div class="form-group">
		<?php
		$options = "<option value=''>".$helper->getHebrewText('choose_one')."</option>";
		$other_products = $jsondata['categories']['other_products'];
		if (isset($other_products) && !empty($other_products)) {
			?>
				<label for="other_products_type"><?php echo $helper->getHebrewText('select_other_products'); ?>: </label>
				<select name="other_products_type" class="other_products_type" id="other_products_type">
			<?php
				foreach ($other_products as $index => $other_product) {
					$other_product_lang = $helper->getHebrewText($index);
					$options .= '<option value="'.$index.'">'.$other_product_lang.'</option>';
				}
			echo $options; ?>
				</select>
		<?php
		}
		?>
		</div>

        <div class="form-group field_dynamic_color">
    		<label for="other_products_dynamic_colors"><?php echo $helper->getHebrewText('select_color'); ?>: </label>
    		<select name="other_products_dynamic_colors" class="other_products_dynamic_colors" id="other_products_dynamic_colors" onchange="onColorschange();">
    			<!--<option value=''><?php echo $helper->getHebrewText('choose_one'); ?></option>-->
    		</select>
    	</div>
	<div class="form-group thickness_dropdown">
		<label for='other_products_thickness'><?php echo $helper->getHebrewText('select_thickness'); ?>: </label>
		<select id='other_products_thickness' class="other_products_thickness" onchange='javascript:onthicknesschange()' name='other_products_thickness'>
		<option value=''><?php echo $helper->getHebrewText('choose_one'); ?></option>
		</select>
	</div>

	<div class="form-group thickness_range_dropdown">
		<label for='other_products_thickness_range'><?php echo $helper->getHebrewText('select_thickness'); ?>: </label>
	</div>

	<div class="form-group thickness_line">
		<label for="other_products_thickness"><?php echo $helper->getHebrewText('thickness'); ?>: <span id="thickness_content"></span></label><input type="hidden" name="other_products_thickness" class="other_products_thickness" id="other_products_thickness" value=""/>
	</div>
    <div class="form-group dimension_dropdown" >
        <label for='other_products_size'><?php echo $helper->getHebrewText('select_size'); ?>: </label>
        <select onchange='javascript:onsizechange()' name='other_products_size' class='other_products_size' id='other_products_size'><option value=''><?php echo $helper->getHebrewText('choose_one'); ?></option>
        </select>
    </div>

    <div class="form-group field_color">
        <label for="other_products_colors"><?php echo $helper->getHebrewText('select_color'); ?>: </label>
        <select name="other_products_colors" class="other_products_colors" id="other_products_colors" onchange="onColorschange();">
            <!--<option value=''><?php echo $helper->getHebrewText('choose_one'); ?></option>-->
        </select>
    </div>

		<div class="form-group field_width">
			<input placeholder="<?php echo $helper->getHebrewText('width'); ?>" name="other_products_width" class="frame_size" id="other_products_width" type="number" pattern="[0-9]"/>
			<span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
		</div>
		<div class="form-group field_height">
			<input placeholder="<?php echo $helper->getHebrewText('height'); ?>" name="other_products_height" class="frame_size" id="other_products_height" type="number" pattern="[0-9]"/>
			<span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
		</div>
        <!-- <div class="form-group field_color">
           <label for="other_products_colors"><?php echo $helper->getHebrewText('select_color');?>: </label>
           <select name="other_products_colors" class="other_products_colors" id="other_products_colors">
           </select>
        </div> -->
		<div class="form-group">
			<span class="other_products_error" id="other_products_size_error"></span>
		</div>
	<div class="form-group glue_checkbox" >
		<label for='glue_enabled'><?php echo $helper->getHebrewText('with_glue'); ?>: <input onclick="javascript:checkGlue();" type="checkbox" name="glue_enabled" checked id="glue_enabled"/>
		</label>
	</div>
	</div>
    <div class="mid_section"></div>
	<div class="right_section">
		<div class="frame_info">
			<div class="info"></div>
			<div class="notice"><?php echo $helper->getHebrewText('shipment_cost_will_be_priced_seperately');?></div>
		</div>
		<div class="frame_bot">
			<div class="form-group field_quantity item">
				<label for="other_products_quantity"><?php echo $helper->getHebrewText('quantity');?>:</label>
				<input class="style2 value" id="other_products_quantity" min="1" value="1" type="number" name="other_products_quantity" onchange="onquantitychange(this)" onkeyup="onquantitychange(this)" pattern="[0-9]"/>
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
</form>
<script>

function onquantitychange(elem)
{
	calc();
}

function onColorschange()
{
    var colorVal = $("#other_products_dynamic_colors").val();
    $("#price").html("");
    $("#input_price").val(0);
    $("#add_to_cart").css("display", "none");
        var page = $("#other_products_type").val();
        $.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
			if(page == 'plexiglass' && typeof data.categories.other_products[page] != 'undefined')
			{
                var content = data.categories.other_products[page];
                var colorsList = Object.keys(content['sizes'][0]['colors']);
                if(colorsList.indexOf(colorVal) !== -1){
                    if(typeof content['sizes'][0]['colors'][colorVal]['thickness_by_mm'] !== "undefined"){
                        $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                            var thickness = "";
                            $(".other_products_thickness_range").removeClass("other_products_thickness_enabled");
    						$(".other_products_thickness_range").val("");
    						$(".other_products_thickness").removeClass("other_products_thickness_enabled");
    						$(".other_products_thickness:eq(0)").addClass("other_products_thickness_enabled");
    						$(".other_products_thickness:eq(0) option").not($(".other_products_thickness:eq(0) option:eq(0)")).remove();
                            $(".thickness_dropdown").css("display", "block");
                            $(".thickness_range_dropdown, .thickness_line").css("display", "none");
                            $(content['sizes'][0]['colors'][colorVal]['thickness_by_mm']).each(function(index){
    							thickness += "<option value="+this+">"+this+data['mm']+"</option>";
    						});
                            $(".other_products_thickness:eq(0)").append(thickness);
                        });
                    }
                    if(typeof content['sizes'][0]['colors'][colorVal]['thickness_range_by_mm'] !== "undefined"){
                        $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                        var max_thickness = Math.max.apply(null, content['sizes'][0]['colors'][colorVal]['thickness_range_by_mm']);
                        var min_thickness = Math.min.apply(null, content['sizes'][0]['colors'][colorVal]['thickness_range_by_mm']);
                        $(".other_products_thickness").removeClass("other_products_thickness_enabled");
                        $(".thickness_dropdown, .glue_checkbox").css("display", "none");
                        $(".thickness_range_dropdown").css("display", "block");
                        $(".other_products_thickness_range_div").remove();
                        $(".thickness_range_dropdown").append("<span class='form-group other_products_thickness_range_div'><input type='number' id='other_products_thickness_range' min='"+min_thickness+"'  max='"+max_thickness+"' class='other_products_thickness_range' onkeyup='javascript:calc();' onchange='javascript:calc();' name='other_products_thickness_range'><span class='input-label'>"+data['mm']+"</span></span>");
                        $(".other_products_thickness_range").removeClass("other_products_thickness_range_enabled");
                        $(".other_products_thickness_range:eq(0)").addClass("other_products_thickness_range_enabled");
                        });
                    }
                }
            }
    	    calc();
        });
}

function calc(){
	var page = $("#other_products_type").val();
	var quantity = $("#other_products_quantity").val();
	if(typeof $(".other_products_size.other_products_size_enabled").val() != 'undefined')
	{
		var other_products_size = $(".other_products_size.other_products_size_enabled").val();
	}
	if(typeof $(".other_products_thickness.other_products_thickness_enabled").val() != 'undefined')
	{
		var thickness =  $(".other_products_thickness.other_products_thickness_enabled").val();
	}
	else if(typeof $(".other_products_thickness_range.other_products_thickness_range_enabled").val() != 'undefined')
	{
		var thickness = $(".other_products_thickness_range.other_products_thickness_range_enabled").val();
	}

	if((typeof thickness == 'undefined' && typeof other_products_size != 'undefined' && other_products_size !='') || (typeof thickness != 'undefined' && thickness !='') && (typeof other_products_size != 'undefined' && other_products_size !=''))
	{
		$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
			if(typeof data.categories.other_products[page] != 'undefined')
			{
				if(data.categories.other_products[page].sizes.length > 1)
				{
					var checkGlue = $("#glue_enabled").is(":checked");
					$(data.categories.other_products[page].sizes).each(function(index){
						var size = this;
						if(thickness == this.thickness_by_mm)
						{
							$(this.prices).each(function(index){
								if(checkGlue == this.with_glue)
								{
									var price = this.price_list[other_products_size];
									if(quantity < this.quantity_threshold[other_products_size])
									{
										var calc_price = (quantity * price) + (quantity * price) * this.low_quantity_percentage / 100;
									}
									else if(quantity > this.quantity_threshold[other_products_size])
									{
										var difference = quantity - this.quantity_threshold[other_products_size];
										var calc_price = (this.quantity_threshold[other_products_size] * price) + (difference * price) + (difference * price) * this.low_quantity_percentage / 100;
									}
									else
									{
										var calc_price = (this.quantity_threshold[other_products_size] * price);
									}
									// if(calc_price < size.min_price)
									// {
									// 	calc_price = size.min_price;
									// }
									$("#price").html(Math.round(calc_price));
                                    $("#input_price").val(Math.round(calc_price));
                                    $("#add_to_cart").css("display", "block");
									return false;
								}
							});
						}
					});
				}
				else
				{
					$(data.categories.other_products[page].sizes).each(function(index){
						var size = this;
						var calc_price = size.prices[other_products_size];

                        // if(calc_price < size.min_price)
						// {
						// 	calc_price = size.min_price;
						// }
						$("#price").html(Math.round(calc_price)*quantity);
                        $("#input_price").val(Math.round(calc_price)*quantity);
                        $("#add_to_cart").css("display", "block");
					});
				}
			}
		});
	}
	else if((typeof thickness != 'undefined' && thickness !='') && (typeof other_products_size == 'undefined' || other_products_size == ''))
	{
		var other_product_width = $("#other_products_width").val();
		var other_product_height = $("#other_products_height").val();
		$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
		if(typeof data.categories.other_products[page] != 'undefined')
		{
			if(data.categories.other_products[page].sizes.length == 1)
			{
				var size = data.categories.other_products[page].sizes[0];
				if((typeof other_product_width != 'undefined' && other_product_width > 0) && (typeof other_product_height != 'undefined' && other_product_height > 0))
				{
					var max_dimensions = Math.max.apply(null, data.categories.other_products[page].manual_dimensions.max_dimensions);
					var min_dimensions = Math.min.apply(null, data.categories.other_products[page].manual_dimensions.max_dimensions);
					if(other_product_width > max_dimensions || other_product_height > max_dimensions)
					{
                        $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    						$("#other_products_size_error").html(data['size_can_not_be_more_than'] + " "+data.categories.other_products[page].manual_dimensions.max_dimensions[0]+"X"+data.categories.other_products[page].manual_dimensions.max_dimensions[1]);
                        });
						$("#price").html("");
                        $("#input_price").val(0);
                        $("#add_to_cart").css("display", "none");
						return false;
					}
					else if(((other_product_height <= max_dimensions && other_product_height > min_dimensions ) && other_product_width > min_dimensions) || ((other_product_width <= max_dimensions && other_product_width > min_dimensions) && other_product_height > min_dimensions))
					{
                        $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    						$("#other_products_size_error").html(data['size_can_not_be_more_than'] + " "+data.categories.other_products[page].manual_dimensions.max_dimensions[0]+"X"+data.categories.other_products[page].manual_dimensions.max_dimensions[1]);
                        });
						$("#price").html("");
                        $("#input_price").val(0);
                        $("#add_to_cart").css("display", "none");
						return false;
					}
					else{
						$("#other_products_size_error").html("");
					}
					var calc_price = size.prices[0] * thickness * other_product_width/100 * other_product_height/100;
					// if(calc_price < size.min_price)
					// {
					// 	calc_price = size.min_price;
					// }
					$("#price").html(Math.round(calc_price)*quantity);
                    $("#input_price").val(Math.round(calc_price)*quantity);
                    $("#add_to_cart").css("display", "block");
				}
			}
		}
	});
	}
}

function onthicknesschange(){
	$("#price").html("");
    $("#input_price").val(0);
    $("#add_to_cart").css("display", "none");
	var val = $("#other_products_type").val();
	var thickness = $("#other_products_thickness").val();
	$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
		if(typeof val != 'undefined')
		{
			var content = data.categories.other_products[val];
			if(typeof content.sizes != "undefined")
			{
				if(content.sizes.length > 1)
				{
					var sizes = "";
					$(content.sizes).each(function(index){
						if(typeof this.thickness_by_mm != "undefined" && (this.thickness_by_mm == thickness))
						{
							var dimension = "";
							$(this.dimensions).each(function(index){
								dimension += '<option value="'+index+'">'+this[0]+'x'+this[1]+'</option>';
							});
							$(".other_products_size").removeClass("other_products_size_enabled");
							$(".other_products_size:eq(0)").addClass("other_products_size_enabled");
							$(".other_products_size:eq(0) option").not($(".other_products_size:eq(0) option:eq(0)")).remove();
							$(".other_products_size:eq(0)").append(dimension);
							$(".dimension_dropdown").css("display", "block");
						}
					});

				}
                calc();
			}
		}
	});
}

function onsizechange(){
	calc();
}

function checkGlue()
{
	calc();
}

$(document).ready(function(){
	$("#other_products_type").on("change", function(){
		var val = $(this).val();
        $(".info").text("");
		if(val != '')
		{
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				var content = data.categories.other_products[val];
				if(typeof content.sizes != "undefined")
				{
					if(content.sizes.length > 1)
					{
                        $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                            var thickness = "";
    						$(content.sizes).each(function(index){
                                if(typeof this.thickness_by_mm != "undefined"){
    								thickness += "<option value="+this.thickness_by_mm+">"+this.thickness_by_mm+data['mm']+"</option>";
    							}
    						});
                            $(".thickness_range_dropdown, .thickness_line").css("display", "none");
    						$(".thickness_dropdown, .glue_checkbox, .dimension_dropdown").css("display", "block");
    						$(".other_products_thickness_range").removeClass("other_products_thickness_enabled");
    						$(".other_products_thickness_range").val("");
    						$(".other_products_thickness").removeClass("other_products_thickness_enabled");
    						$(".other_products_thickness:eq(0)").addClass("other_products_thickness_enabled");
    						$(".other_products_thickness:eq(0) option").not($(".other_products_thickness:eq(0) option:eq(0)")).remove();
    						$(".other_products_thickness:eq(0)").append(thickness);
    						$(".other_products_size:eq(0) option").not($(".other_products_size:eq(0) option:eq(0)")).remove();
                        });

						if(typeof content.dimensions_enabled != "undefined" && content.dimensions_enabled == false)
						{
							$(".dimension_dropdown, .field_width, .field_height, .field_color, field_dynamic_color").css("display", "none");
                            $(".other_products_colors option, .other_products_dynamic_colors option").remove();
							$("#other_products_width, #other_products_height").val("");
						}
					}
					else{
                        if(typeof content.sizes[0].dynamic_thickness == "undefined"){
                            $(".other_products_colors option, .other_products_dynamic_colors option").remove();
                            $(".field_color").css("display", "none");
                            $(".field_dynamic_color").css("display", "none");
    						if(typeof content.sizes[0].thickness_by_mm != "undefined"){
                                $(".thickness_range_dropdown, .thickness_dropdown").css("display", "none");
    							$(".thickness_line").css("display", "block");
    							$(".other_products_thickness_range").removeClass("other_products_thickness_range_enabled");
    							$("#thickness_content").html(content.sizes[0].thickness_by_mm+"mm");
    							$(".other_products_thickness:eq(0)").val("");
    							$(".other_products_thickness_range").val("");
    							$(".other_products_thickness:eq(1)").val(content.sizes[0].thickness_by_mm);
    						}
    						else if(typeof content.sizes[0].thickness_range_by_mm != "undefined")
    						{
                                $(".info").text("<?php echo $helper->getHebrewText('plexiglass_is_a_transparent_glass'); ?>.");
    							var max_thickness = Math.max.apply(null, content.sizes[0].thickness_range_by_mm);
    							var min_thickness = Math.min.apply(null, content.sizes[0].thickness_range_by_mm);
    							$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                                    var thickness = "";
                                    console.log(3, data);
        							for(var i = Math.min(min_thickness); i <= max_thickness; i++)
        							{
        								thickness += "<option value="+i+">"+ i + data['mm'] + "</option>";
        							}
        							$(".other_products_thickness").removeClass("other_products_thickness_enabled");
        							$(".thickness_dropdown, .glue_checkbox").css("display", "none");
        							$(".thickness_range_dropdown").css("display", "block");
        							$(".other_products_thickness_range_div").remove();
        							$(".thickness_range_dropdown").append("<span class='form-group other_products_thickness_range_div'><input type='number' id='other_products_thickness_range' min='"+min_thickness+"'  max='"+max_thickness+"' class='other_products_thickness_range' onkeyup='javascript:calc();' onchange='javascript:calc();' name='other_products_thickness_range'></span>");
        							$(".other_products_thickness_range").removeClass("other_products_thickness_range_enabled");
        							$(".other_products_thickness_range:eq(0)").addClass("other_products_thickness_range_enabled");
        							$(".other_products_thickness_range:eq(0) option").not($(".other_products_thickness_range:eq(0) option:eq(0)")).remove();
        							$(".other_products_thickness_range:eq(0)").append(thickness);
                                });
    						}
    						else
    						{
    							$(".other_products_thickness").removeClass("other_products_thickness_enabled");
    							$(".other_products_thickness_range").removeClass("other_products_thickness_range_enabled");
    							$(".thickness_range_dropdown, .thickness_dropdown, .glue_checkbox").css("display", "none");
    							$(".other_products_thickness:eq(0)").val("");
    							$(".other_products_thickness_range").val("");
    						}
                        }
                        else{
                            $(".other_products_colors option, .other_products_dynamic_colors option").remove();
                            $(".other_products_size").removeClass("other_products_size_enabled");
                            $(".field_color").css("display", "none");
                            $(".field_dynamic_color").css("display", "block");
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                                var colors = '';
                                $(Object.keys(content.sizes[0].colors)).each(function(index){
                                    colors += '<option value="'+this.toString()+'">'+data[this]+'</option>';
                                });
                                $(".other_products_dynamic_colors").append(colors);
                            });
                            $(".field_dynamic_color").css("display", "block");
                            $(".other_products_thickness").removeClass("other_products_thickness_enabled");
                            $(".other_products_thickness_range").removeClass("other_products_thickness_range_enabled");
                            $(".thickness_range_dropdown, .thickness_dropdown, .glue_checkbox").css("display", "none");
                            $(".other_products_thickness:eq(0)").val("");
                            $(".other_products_thickness_range").val("");
                            $(".other_products_size:eq(0) option").not($(".other_products_size:eq(0) option:eq(0)")).remove();
                            onColorschange();
                        }
						if(typeof content.dimensions_enabled != "undefined" && content.dimensions_enabled == true)
						{
							if(typeof content.manual_dimensions != "undefined" && typeof content.manual_dimensions.max_dimensions != "undefined" && content.manual_dimensions.max_dimensions.length > 0)
							{
								$(".dimension_dropdown").css("display", "none");
								$(".field_width, .field_height").css("display", "block");
							}
							else
							{
								$(".field_width, .field_height").css("display", "none");
								$("#other_products_width, #other_products_height").val("");
								var dimension = "";
								$(content.sizes[0].dimensions).each(function(index){
									dimension += '<option value="'+index+'">'+this[0]+'x'+this[1]+'</option>';
								});
								$(".other_products_size").removeClass("other_products_size_enabled");
								$(".other_products_size:eq(0)").addClass("other_products_size_enabled");
                                $(".info").text("<?php echo $helper->getHebrewText('this_is_an_acid-free_passparto.')?>");
								$(".other_products_size:eq(0) option").not($(".other_products_size:eq(0) option:eq(0)")).remove();
								$(".other_products_size:eq(0)").append(dimension);
								$(".dimension_dropdown").css("display", "block");
							}
						}
						else{
							$(".dimension_dropdown, .field_width, .field_height").css("display", "none");
							$("#other_products_width, #other_products_height").val("");
							$("#other_products_size").val("");
							$(".other_products_size:eq(0)").removeClass("other_products_size_enabled");
						}
					}

				calc();
				}

			});
			$("#price").html("");
            $("#input_price").val(0);
            $("#add_to_cart").css("display", "none");
		}
		else{
			$("#price").html("");
            $("#input_price").val(0);
            $("#add_to_cart").css("display", "none");
			$(".fieldsdisplay").css("display", "none");
		}
	});

	$(".other_products_size").on("change", function(){
		$("#price").html("");
        $("#input_price").val(0);
        $("#add_to_cart").css("display", "none");
        var page = $("#other_products_type").val();
        if(typeof page != 'undefined' && typeof $(".other_products_size.other_products_size_enabled").val() != 'undefined')
    	{
    		var other_products_size = $(".other_products_size.other_products_size_enabled").val();
            $.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
                if(data.categories.other_products[page].sizes.length == 1){
                    var size = data.categories.other_products[page].sizes[0];
                    if(typeof size.colors != "undefined")
                    {
                        $(".other_products_colors option").remove();
                        $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                            var colors = "";
                            $(size.colors[other_products_size]).each(function(index){
                                colors += '<option value="'+this+'">'+data[this]+'</option>';
                            });
                            $(".other_products_colors").append(colors);
                        });
                        $(".field_color").css("display", "block");
                    }
                    else{
                        $(".field_color").css("display", "none");
                        $(".other_products_colors option").not($(".other_products_colors option")).remove();
                    }
                }

            });

        }
        calc();
	});

	$("#other_products_width, #other_products_height").on("keyup blur", function(){
        var id = $(this).attr("id");
        var splittedVal = $("#"+id).val().split(".");
        if(splittedVal.length > 1){
            $("#"+id).val(splittedVal[0]);
        }
        calc();
    });
});
$("#add_to_cart").on("click", function(){
    var formdata = $("#other_products_form").serializeArray();
    if(typeof $(".other_products_size.other_products_size_enabled").val() != 'undefined')
	{
		var other_products_size = $(".other_products_size.other_products_size_enabled option:selected").html();
        formdata.push({name: 'other_products_size_enabled', value: other_products_size});
	}
    if(typeof $(".other_products_colors").val() != 'undefined')
	{
		var other_products_colors = $(".other_products_colors option:selected").html();
        formdata.push({name: 'other_products_colors', value: other_products_colors});
	}
    if(typeof $(".other_products_dynamic_colors").val() != 'undefined')
	{
		var other_products_colors = $(".other_products_dynamic_colors option:selected").html();
        formdata.push({name: 'other_products_dynamic_colors', value: other_products_colors});
	}
	if(typeof $(".other_products_thickness.other_products_thickness_enabled").val() != 'undefined')
	{
		var thickness =  $(".other_products_thickness.other_products_thickness_enabled").val();
        formdata.push({name: 'other_products_thickness_enabled', value: thickness});
	}
	else if(typeof $(".other_products_thickness_range.other_products_thickness_range_enabled").val() != 'undefined')
	{
		var thickness = $(".other_products_thickness_range.other_products_thickness_range_enabled").val();
        formdata.push({name: 'other_products_thickness_range_enabled', value: thickness});
	}

    if($("#other_products_type").val() == 'capa' && $("#glue_enabled").is(":checked")){
        formdata.push({name: 'glue_enabled', value: 'With Adhesive'});
    }
    else{
        formdata.push({name: 'glue_enabled', value: undefined});
    }

    $.ajax({
        url: "<?php echo plugin_dir_url(__FILE__).'add_to_woocommerce.php';?>",
        data: formdata,
        type: 'POST',
        success: function(result){
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
                    $(".woocommerce-message .message").html('"'+result.name + '" has been added to your cart.');
                    $('html, body').animate({
                        scrollTop: $(".woocommerce-message").offset().top
                    }, 1000)
            }
            else {
                $(".woocommerce-message").css("display", "block");
                $(".woocommerce-message .message").html('"'+result.name + '" has been updated to your cart.');
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

.other_products_error{
    display: block;
    color: red;
}
.thickness_line, .thickness_range_dropdown, .glue_checkbox, .field_color, .field_dynamic_color
{
	display: none;
}
</style>
</div>
