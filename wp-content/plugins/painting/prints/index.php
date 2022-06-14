<form action="<?php echo $_SERVER['PHP_SELF']; ?>?page=prints" name="print_form" id="print_form" method="POST" enctype="multipart/form-data">
<div class="woocommerce-message" role="alert">
    <a href="cart" tabindex="1" class="button wc-forward">View cart</a>
    <span class="message"></span>
</div>
<div class="painting-container three_sections">
<div class="left_section">
			<input type="hidden" name="product_id" id="product_id" value="<?php echo $productID;?>">
			<input type="hidden" name="frame_selected" id="frame_selected" value="">
	<div class="form-group">
		<label for='upload_print'><?php echo $helper->getHebrewText('upload_image');?>: </label>
		<input class="form-inline" type="file" id="upload_print" name="upload_print" required />
	</div>
<div class="print_form2" id="print_form2" name="print_form2">
<div class="form-group">
	<?php
    $options = "<option value=''>".$helper->getHebrewText('choose_one')."</option>";
    $prints = $jsondata['categories']['prints'];
    if (isset($prints) && !empty($prints)) {
        ?>
			<label for="print_type"><?php echo $helper->getHebrewText('choose_one'); ?></label>
			<select name="print_type" class="print_type" id="print_type">
		<?php
            foreach ($prints as $index => $print) {
                $print_lang = $helper->getHebrewText($index);
                $options .= '<option value="'.$index.'">'.$print_lang.'</option>';
            }
        echo $options; ?>
			</select>
	<?php
    }
    ?>
  </div>
  	<div class="form-group papertype_dropdown">
		<label for='print_papertype_select'><?php echo $helper->getHebrewText('choose_paper_type');?></label>
		<select id='print_papertype_select' class='print_papertype' onchange='calc()' name='print_papertype_select'>
		</select>
	</div>

	<div class="form-group thickness_dropdown">
		<label for='print_thickness_dropdown'><?php echo $helper->getHebrewText('choose_thickness');?></label>
		<select id='print_thickness_dropdown' class="print_thickness" onchange='javascript:onthicknesschange()' name='print_thickness_dropdown'>
		</select>
	</div>

	<div class="form-group thickness_line">
		<label for="print_thickness_line"><?php echo $helper->getHebrewText('thickness');?>: <span id="thickness_content"></span></label>
		<input type="hidden" name="print_thickness_line" class="print_thickness" id="print_thickness_line" value=""/>
	</div>

	<div class="form-group thickness_width">
		<label for="print_width"><?php echo $helper->getHebrewText('width');?>: </label>
		<input name="print_width" class="print_size" id="print_width" type="number" min="0" max="120" onchange="onsizekeyup(this, 0);" onkeyup="onsizekeyup(this, 0);" pattern="[0-9]"/>
		<span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
		<input name="image_width" id="image_width" type="hidden" value=""/>
	</div>
	<div class="form-group thickness_height">
		<label for="print_height"><?php echo $helper->getHebrewText('height');?>:</label>
		<input name="print_height" class="print_size" id="print_height" type="number" min="0" max="140" onchange="onsizekeyup(this, 1);" onkeyup="onsizekeyup(this, 1);" pattern="[0-9]"/>
		<span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
		<input name="image_height" id="image_height" type="hidden" value=""/>
	</div>
	<div class="form-group">
		<span class="print_error" id="print_size_error"></span>
	</div>

	<div class="form-group finishing_dropdown">
		<label for='print_finishing_select'><?php echo $helper->getHebrewText('choose_finishing');?></label>
		<select id='print_finishing_select' class="print_finishing" onchange='onfinishingchange(this);calc();' name='print_finishing_select'>
		</select>
	</div>

	<div class="form-group finishing_line">
		<label for="print_finishing_check"><input type="checkbox" name="print_finishing_check" class="print_finishing" id="print_finishing_check" value="" onclick="onfinishingclick(this);"/> <span id="finishing_content"></span></label>
	</div>

	<div class="form-group laminate_dropdown">
		<label for='print_laminate_hanging_select'><?php echo $helper->getHebrewText('choose_lamination');?></label>
		<select id='print_laminate_hanging_select' class='print_laminate_hanging' onchange='calc()' name='print_laminate_hanging_select'>
		</select>
	</div>

	<div class="form-group laminate_line">
		<label for="print_laminate_check"><input type="checkbox" name="print_laminate_check" class="print_laminate" id="print_laminate_check" value=""/><?php echo $helper->getHebrewText('laminate');?> <span id="laminate_content"></span></label>
	</div>

	<div class="form-group frame_required">
		<label>
            <input type="checkbox" name="frame_required" id="frame_required" value="" onclick="onframerequired(this)"; />
            <?php echo $helper->getHebrewText('with_frame');?>
        </label>
	</div>

		<!-- Frame starts here -->
		<!--<div class="form-group choose_frame_field">
			<div class="form-group">
			<label for="frame_type">Select Frame Type: </label>
			<select name="frame_type" class="frame_type" id="frame_type">
			<?php
            $options = "<option value=''>".$helper->getHebrewText('choose_one')."</option>";
            $frames = $jsondata['categories']['frames']['types'];
            if (isset($frames) && !empty($frames)) {
                foreach ($frames as $key_frame => $frame) {
                    if ($frame != 'from_catalogue') {
                        $newframe= ucfirst(str_replace("_", " ", $frame));
                        $options .= '<option value="'.$frame.'">'.$newframe.'</option>';
                    }
                }
            }
            echo $options;
            ?>
			</select>
		</div>

		<div class="form-group field_size_dropdown">
			<label for="frame_size">Select Size:</label>
			<select id="frame_size" onchange="onframesizechange(this);">
				<option value="">Choose one</option>
			</select>
		</div>

	<div class="form-group field_cover">
		<label for="frame_cover">Select Cover Type: </label>
		<select name="frame_cover" class="frame_cover" id="frame_cover">
		<?php
        $options = "";
        $covers = $jsondata['categories']['frames']['sizes'][0]['covers'];
        if (isset($covers) && !empty($covers)) {
            foreach ($covers as $key_cover => $cover) {
                $cover= ucfirst(str_replace("_", " ", $key_cover));
                $options .= '<option value="'.$key_cover.'">'.$cover.'</option>';
            }
        }
        echo $options;
        ?>
		</select>
	  </div>-->

	  <div class="form-group field_extra">
		<label for="frame_extra"><?php echo $helper->getHebrewText('select_extras');?>: </label>
		<select name="frame_extra" class="frame_extra" id="frame_extra" onchange="onextrachange();">
		<?php
        /* $options = "";
        $extras = $jsondata['categories']['frames']['sizes'][0]['extras'];
        if(isset($extras) && !empty($extras))
        {
            foreach($extras as $key_extra => $extra){
                $extra= ucfirst(str_replace("_", " ", $key_extra));
                $options .= '<option value="'.$key_extra.'">'.$extra.'</option>';
            }
        }
        echo $options; */
        ?>
		</select>
	  </div>

	  <div class="form-group field_extra_thickness">
		<label for="frame_colors"><?php echo $helper->getHebrewText('select_width');?>: </label>
			<input id="frame_extra_thickness" min="2" max="10" value="2" type="number" name="frame_extra_thickness" onchange="onquantitychange(this)" onkeyup="onextrathicknesschange()" pattern="[0-9]"/>
            <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
		</label>
	  </div>
		<!--
		<div class="form-group field_color">
		<label for="frame_colors">Select Color: </label>
		<select name="frame_colors" class="frame_colors" id="frame_colors">
		<?php
        $options = "<option selected='selected'>".$helper->getHebrewText('choose_one')."</option>";
        $colors = $jsondata['categories']['frames']['sizes'][0]['colors'];
        if (isset($colors) && !empty($colors)) {
            foreach ($colors as $key_color => $color) {
                $color= ucfirst(str_replace("_", " ", $color));
                $options .= '<option value="'.$key_color.'">'.$color.'</option>';
            }
        }
        echo $options;
        ?>
		</select>
	  </div>
    </div>-->
	<!-- Frame ends here -->


</div>

</div>
<!---->
<div class="mid_section">

	<div class="form-group image_display" >
		<p id="img_text"><?php echo $helper->getHebrewText('your_have_uploaded_below_image_with_size');?> <span id="image_size"></span></p>
		<span id="image_content"></span>
        <input type="hidden" name="print_image" id="print_image" value=""/>
	</div>
	<span class="img_loader"><img src="<?php echo plugins_url("ajax-loader.gif", dirname(__FILE__));?>"></span>
</div>
<!---->
<div class="right_section">

	<div class="frame_info">
        <?php include(plugin_dir_path(dirname(__FILE__)).'frames/frame-grid.php'); ?>
        <div class="info"></div>
        <div class="notice"><?php echo $helper->getHebrewText('shipment_cost_will_be_priced_seperately');?></div>
		</div>
		<div class="frame_bot">
			<div class="form-group field_quantity item">
				<label for="print_quantity"><?php echo $helper->getHebrewText('quantity'); ?>:</label>
				<input class="style2 value" id="print_quantity" min="1" value="1" type="number" name="print_quantity" onchange="onquantitychange(this)" onkeyup="onquantitychange(this)" pattern="[0-9]"/>
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
function onextrathicknesschange()
{
	calc();
}
function onextrachange()
{
	calc();
}

function getMul(total, num)
{
	return total*num;
}

function onquantitychange(elem)
{
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

function calc(){
	var page = $("#print_type").val();
	var print_width = parseInt($("#print_width").val());
	var print_height = parseInt($("#print_height").val());
	var image_width = $("#image_width").val();
	var image_height = $("#image_height").val();
	var thickness = $(".print_thickness.print_enabled").val();
	var print_finishing_select = $("#print_finishing_select.finishing_enabled").val();
	var print_finishing_check = $("#print_finishing_check.finishing_enabled").is(":checked");
	var print_laminate_hanging_select = $("#print_laminate_hanging_select.laminate_enabled").val();
	var print_laminate_check = $("#print_laminate_check.laminate_enabled").is(":checked");
	var quantity = $("#print_quantity").val();
	var frame_extra = $("#frame_extra").val();
	if((typeof print_width != 'undefined' && print_width > 0) && (typeof print_height != 'undefined' && print_height > 0))
	{
		var r1 = gcd (print_width, print_height);
		var r2 = gcd (image_width, image_height);
		//if(print_width/r1 == image_width/r2 && print_height/r1 == image_height/r2)
		{
			$("#print_size_error").html("");
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				if(typeof data.categories.prints[page] != 'undefined')
				{
					var max_dimensions = [];
					if(typeof data.categories.prints[page].sizes.max_size_one_side == "number")
					{
						max_dimensions =  data.categories.prints[page].sizes.max_size_one_side;
                        if(print_width > max_dimensions || print_height > max_dimensions)
    					{
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
        						$("#print_size_error").html(data['width_or_height_can_not_be_more_than'] + " " +max_dimensions);
                            });
    						$("#price").html("");
                            $("#input_price").val(0);
                            $("#add_to_cart").css("display", "none");
                            return false;
    					}
					}
					else
					{
						max_dimensions[0] =  data.categories.prints[page].sizes.max_size_one_side[0];
						max_dimensions[1] =  data.categories.prints[page].sizes.max_size_one_side[1];
                        if(print_width > max_dimensions[0] || print_height > max_dimensions[1])
    					{
    						$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
        						$("#print_size_error").html(data['size_can_not_be_more_than'] + " " +max_dimensions[0]+"x"+max_dimensions[1]);
                            });
    						$("#price").html("");
                            $("#input_price").val(0);
                            $("#add_to_cart").css("display", "none");
                            return false;
    					}
					}
					var calc_price = 0;
					$("#print_size_error").html("");
					$(data.categories.prints[page].sizes.ranges).each(function(index){
						var size = this;
						var dimension = (print_width*print_height)/100;
						if(size[1] != 0 && (dimension >= size[0] && dimension <= size[1]))
						{
							calc_price += dimension * data.categories.prints[page].prices[index]/100;
							return false;
						}
						else if(size[1] == 0 && dimension >= size[0])
						{
							calc_price += dimension * data.categories.prints[page].prices[index]/100;
							return false;
						}
					});
					if(typeof print_finishing_select != 'undefined' && print_finishing_select !="")
					{
						//var find = "_";
						//var re = new RegExp(find, 'g');
						//var print_finishing = print_finishing_select.replace(re, " ");
						$(data.categories.prints[page].finishing).each(function(index){
							var finishing = this;
							if(print_finishing_select == finishing.type)
							{
								//if(page == 'paper')
								{
									calc_price += parseInt(finishing.price) * (print_width + print_height) * 2 / 100;
								}
								/* else
								{
									calc_price += finishing.price;
								} */
								return false;
							}
						});
					}
					else if(print_finishing_check == true)
					{
						$(data.categories.prints[page].finishing).each(function(index){
							var finishing = this;
							//meter run
							//if(page == 'paper')
							{
								calc_price += parseInt(finishing.price) * (print_width + print_height) * 2 / 100;
							}
							/* else
							{
								calc_price += finishing.price;
							} */
							return false;
						});
					}

					if(typeof print_laminate_hanging_select != 'undefined' && print_laminate_hanging_select !="")
					{
						//var find = "_";
						//var re = new RegExp(find, 'g');
						//var print_laminate = print_laminate_hanging_select.replace(re, " ");
						$(data.categories.prints[page].laminate).each(function(index){
							var laminate = this;
							if(print_laminate_hanging_select == laminate.type)
							{
                                if(this.price_type == 'meter_run'){
		                            calc_price += parseInt(laminate.price)*(print_width + print_height)*2/100;
                                }
                                else if(this.price_type == 'fixed'){
                                    calc_price += parseInt(laminate.price);
                                }
                                else{
                                    calc_price += parseInt(laminate.price);
                                }
								return false;
							}
						});
					}
					else if(print_laminate_check == true)
					{
						$(data.categories.prints[page].laminate).each(function(index){
							var laminate = this;
							//calc_price += laminate.price;
							calc_price += parseInt(laminate.price)*(print_width + print_height)*2/100;
							return false;
						});
					}


					if(typeof data.categories.prints[page].min_price != "undefined" && calc_price < data.categories.prints[page].min_price)
					{
						calc_price = data.categories.prints[page].min_price;
					}

					$("#price").html(Math.round(calc_price)*quantity);
                    $("#input_price").val(Math.round(calc_price)*quantity);
                    $("#add_to_cart").css("display", "block");
					window.print_price = Math.round(calc_price)*quantity;
					if(['sticker', 'rug_PVC'].indexOf(page) === -1 && $("#frame_required").is(":checked") == true)
					{
						frame_calc();
					}

				}
			});
		}
		/*else
		{
			var r = gcd (image_width, image_height);
			$("#print_size_error").html("Width or height should have proportion of "+image_width/r+ ":"+ image_height/r);
			//$("#price").html("");
		}*/
	}
	else
	{
		$("#price").html("");
        $("#input_price").val(0);
        $("#add_to_cart").css("display", "none");
	}
}


function frame_calc(){
	var frame_type = $("#frame_type").val();
	if(frame_type != "from_catalogue")
	{
		var page = $("#print_type").val();
		var frame_width = parseInt($("#print_width").val());
		var frame_height = parseInt($("#print_height").val());
		var frame_extra = $("#frame_extra").val();
		var frame_cover = $("#frame_cover").val();
		var frame_colors = $("#frame_colors").val();
		var quantity = $("#print_quantity").val();
		var frame_selected = $("#frame_selected").val();
		var frame_extra_thickness = parseInt((typeof $("#frame_extra_thickness.field_extra_thickness_enabled").val() != "undefined") ? $("#frame_extra_thickness.field_extra_thickness_enabled").val() : 0);
		if(typeof frame_selected != "undefined" && frame_selected != "" )
		{
		if(typeof frame_width != 'undefined' && frame_width > 0 && typeof frame_height != 'undefined' && frame_height > 0)
		{
			var frame_selected = JSON.parse(frame_selected);

			var price = frame_selected.price;
			price = parseInt((frame_width + frame_height) * 2 / 100 * price);

			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				if(typeof data.categories.frames != 'undefined')
				{
					//$("#frame_size_error").html("");
					/*if(typeof frame_cover != 'undefined')
					{
						$(data.categories.frames.sizes[0].covers).each(function(index){
							if(typeof this[frame_cover].price != 'undefined')
							{
								price += parseInt(this[frame_cover].price)*(frame_width * frame_height)/10000;
								return false;
							}
						});
					}*/
					//if(price < data.categories.frames.sizes[0].min_price)
					/*{
						price = data.categories.frames.sizes[0].min_price;
					}*/
					/*$(data.categories.frames.sizes[0].extras).each(function(index){
						if(this[frame_extra].length > 0 && (frame_width * frame_height) <= this[frame_extra][0].max_dimensions.reduce(getMul))
						{
							if(typeof this[frame_extra][0].price != 'undefined')
							{
								price += parseInt(this[frame_extra][0].price);
								return false;
							}
						}
						else if(this[frame_extra].length > 0 && (frame_width * frame_height) >= this[frame_extra][0].max_dimensions.reduce(getMul) && (frame_width * frame_height) <= this[frame_extra][1].max_dimensions.reduce(getMul))
						{
							if(typeof this[frame_extra][1].price != 'undefined')
							{
								price += parseInt(this[frame_extra][1].price);
							return false;
							}
						}
						else if(this[frame_extra].length > 0){
							price = 0;
							$("#frame_size_error").html("Size should be maximum "+this[frame_extra][1].max_dimensions[0]+"x"+this[frame_extra][1].max_dimensions[1]);
							return false;
						}
					});*/
					if(typeof frame_extra != 'undefined' && frame_extra !="")
					{
						var maxdimension = Math.max.apply(null, [frame_width, frame_height]);
						var mindimension = Math.min.apply(null, [frame_width, frame_height]);
						$(data.categories.prints[page].extras).each(function(index){
							if(frame_extra != "Without" && (typeof this.max_dimensions != "undefined" && maxdimension <= Math.max.apply(null, [this.max_dimensions[0], this.max_dimensions[1]]) && mindimension <= Math.min.apply(null, [this.max_dimensions[0], this.max_dimensions[1]])))
							{
								price += parseInt((frame_extra_thickness * 2) * 2 / 100 * price);
								if(typeof this.price != 'undefined')
								{
									price += parseInt(this.price);
									return false;
								}
							}
							else if(frame_extra != "Without"){
								if(typeof this.max_dimensions != "undefined")
								{
									price = 0;
                                    var self = this;
                                    $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
		                               $("#frame_size_error").html(data['size_can_not_be_more_than'] + " "+self.max_dimensions[0]+"x"+self.max_dimensions[1]);
                                   });
									return false;
								}

							}
						});
					}
					$("#price").html(parseInt(window.print_price)+Math.round(quantity*price));
                    $("#input_price").val(parseInt(window.print_price)+Math.round(quantity*price));
                    $("#add_to_cart").css("display", "block");
				}

			});
		}
	}
	}
}
/*

function gcd (a, b) {
	return (b == 0) ? a : gcd (b, a%b);
}*/


function gcd($num1, $num2){
    for($i = $num2; $i > 1; $i--) {
        if(($num1 % $i) == 0 && ($num2 % $i) == 0) {
            $num1 = $num1 / $i;
            $num2 = $num2 / $i;
        }
    }
    return [$num1, $num2];
}

function onthicknesschange(){
	$("#price").html("");
    $("#input_price").val(0);
    $("#add_to_cart").css("display", "none");
	calc();
}


function onframesizechange(elem)
{
	var sizeindex = $(elem).val();
	var quantity = $("#print_quantity").val();
	$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
		if(sizeindex != "")
		{
			var discount = 0;
			var maxArray = [];
			$(data.categories.frames.sizes[1].discount_list).each(function(index){
				maxArray = $.merge(maxArray, this.quantity);
			});
			maxQuantity = Math.max.apply(null, maxArray);
			if(quantity >= maxQuantity)
			{
				$(data.categories.frames.sizes[1].discount_list).each(function(){
					if($.inArray(maxQuantity, this.quantity) != -1)
					{
						discount = this.discount;
						return false;
					}
				});
			}
			else
			{
				$(data.categories.frames.sizes[1].discount_list).each(function(){
					var max_quantity = Math.max.apply(null, this.quantity);
					var min_quantity = Math.min.apply(null, this.quantity);
					if(min_quantity != 0 && quantity >= min_quantity && quantity <= max_quantity)
					{
						discount = this.discount;
						return false;
					}
				});
			}
			var price = data.categories.frames.sizes[1].price_list[sizeindex] - data.categories.frames.sizes[1].price_list[sizeindex]*discount/100;
			$("#price").html(parseInt(window.print_price)+Math.round(quantity*price));
            $("#input_price").val(parseInt(window.print_price)+Math.round(quantity*price));
            $("#add_to_cart").css("display", "block");
		}
		else{
			$("#price").html("");
            $("#input_price").val(0);
            $("#add_to_cart").css("display", "none");
		}
	});
}

function onsizekeyup(elem, index)
{
	var image_width = $("#image_width").val();
	var image_height = $("#image_height").val();
	var elemVal = $(elem).val();

	var r = gcd(image_width, image_height);
    if(index === 0){
        var width = elemVal;
        var height = width * r[1] / r[0];
        $("#print_width").val(Math.round(width));
        $("#print_height").val(Math.round(height));
    }
    else{
        var height = elemVal;
        var width = height * r[0] / r[1];

        $("#print_width").val(Math.round(width));
        $("#print_height").val(Math.round(height));
    }
	calc();
}

function onfinishingchange(elem){
/* 	var page = $("#print_type").val();
	var value = $(elem).val();
	var find = "_";
	var re = new RegExp(find, 'g');
	value = value.replace(re, " ");
	$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
		if(typeof data.categories.prints[page] != 'undefined')
		{
			$(data.categories.prints[page].finishing).each(function(index){
				var finishing = this;
				if(finishing.type == value)
				{

					return false;
				}
				else
				{

				}
			});
		}
	}); */
}


function onfinishingclick(elem){
	var page = $("#print_type").val();
	var value = $(elem).is(':checked');
	if(value == true)
	{
		$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
			var content = data.categories.prints[page];
			if(typeof content.laminate != "undefined")
			{
                $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    				if(content.laminate.length > 1)
    				{
    					$(".laminate_dropdown").css("display", "block");
    					$(".laminate_line").css("display", "none");
	                    var laminate = "";
                        $(content.laminate).each(function(index){
    						//var find = " ";
    						//var re = new RegExp(find, 'g');
    						//var type = this.type.replace(re, "_");
                            laminate += "<option value="+this.type+">"+data[this.type]+"</option>";
    					});
                        $(".print_laminate_hanging").append(laminate);
                        $(".print_laminate_hanging").removeClass("laminate_enabled");
    					$(".print_laminate_hanging:eq(0)").addClass("laminate_enabled");
    					//$(".print_laminate_hanging:eq(0) option").not($(".print_laminate_hanging:eq(0) option:eq(0)")).remove();
    					$(".print_laminate_hanging:eq(0) option").remove();
    					$(".print_laminate_hanging:eq(0) option:eq(0)").attr("selected", true);
    				}
    				else{
    					$(".laminate_line").css("display", "block");
    					$(".laminate_dropdown").css("display", "none");
    					$(".print_laminate_hanging").removeClass("laminate_enabled");
    					$(".print_laminate_hanging:eq(1)").addClass("laminate_enabled");
    					$(".print_laminate_hanging:eq(1)").val(content.laminate[0].type);
    					$("#laminate_content").html(data[content.laminate[0].type]);
    				}
                });
			}
			else{
				$(".print_laminate_hanging").removeClass("laminate_enabled");
				$(".laminate_dropdown, .laminate_line").css("display", "none");
				$("#print_laminate_check").val("");
				$("#print_laminate_hanging_select").val("");
			}
		});
		calc();
	}
	else
	{
		$(".print_laminate_hanging").removeClass("laminate_enabled");
		$(".laminate_dropdown, .laminate_line").css("display", "none");
		$("#print_laminate_hanging_select").val("");
		calc();
	}

}

$(document).ready(function(){
	$("#upload_print").on("change", function(){
		if($(this).val() != "")
		{
			var formData = new FormData();
			formData.append('upload_print', $('#upload_print')[0].files[0]);
			$.ajax({
				url: '<?php echo plugins_url('upload.php', __FILE__);?>',
				data: formData,
				type: 'POST',
				enctype: 'multipart/form-data',
				processData: false,  // tell jQuery not to process the data
				contentType: false,   // tell jQuery not to set contentType
				beforeSend: function(){
					$(".img_loader img").css("display", "block");
				},
				success: function(res)
				{
					$(".img_loader img").css("display", "none");
					res = JSON.parse(res);
					if(typeof res.error == "undefined")
					{
						$(".print_size").val();
						$(".image_display").css("display", "block");
						$("#image_size").html(res.width+"x"+res.height);
						$("#image_content").html("<img id='print_image' src='"+res.url+"' alt='"+res.filename+"'>");
                        $("#print_image").val(res.url);
						$("#image_width").val(res.width);
						$("#image_height").val(res.height);
						$("#print_form2").css("display", "block");
					}

				},
				error:function(err)
				{
					console.log(err);
				}
			})
		}
	})

	$("#print_type").on("change", function(){
		var val = $(this).val();
		if(val != '')
		{
			if(['sticker', 'rug_PVC'].indexOf(val) === -1)
			{
				$(".frame_required").css("display", "block");
			}
			else{
				$(".frame_required").css("display", "none");
			}

			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				var content = data.categories.prints[val];
				if(val == 'paper')
				{
					$(".papertype_dropdown, .field_extra").css("display", "block");
                    $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
	                    var papertypes = "";
		                $(content.types).each(function(index){
    						//var find = " ";
    						//var re = new RegExp(find, 'g');
    						//var type = this.type.replace(re, "_");
    						papertypes += "<option value="+this.type+">"+data[this.type]+"</option>";
    					});
                        $("#print_papertype_select").append(papertypes);
                        var extras = "";
    					$(content.extras).each(function(index){
    						//var find = " ";
    						//var re = new RegExp(find, 'g');
    						//var type = this.type.replace(re, "_");
    						extras += "<option value="+this.type+">"+data[this.type]+"</option>";
    					});
    					$("#frame_extra").append(extras);
                    });
                    $(".print_papertype option").remove();
                    $(".field_extra option").remove();
				}
				else
				{
					$(".papertype_dropdown, .field_extra, .field_extra_thickness").css("display", "none");
					$("#print_papertype_select option, #print_extra option").remove();
					$(".frame_extra_thickness").val("");
				}
				if(typeof content.thickness_by_mm != "undefined")
				{
					if(content.thickness_by_mm.length > 1)
					{
                        $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    						$(".thickness_dropdown").css("display", "block");
    						$(".thickness_line").css("display", "none");
    						$(".print_thickness:eq(1)").val("");
    						var thickness_by_mm = "";
    						$(content.thickness_by_mm).each(function(index, thickness){
    							thickness_by_mm += "<option value="+index+">"+thickness+data['mm']+"</option>";
    						});
    						$(".print_thickness").removeClass("thickness_enabled");
    						$(".print_thickness:eq(0)").addClass("thickness_enabled");
    						//$(".print_thickness:eq(0) option").not($(".print_thickness:eq(0) option:eq(0)")).remove();
    						$(".print_thickness:eq(0) option").remove();
    						$(".print_thickness").append(thickness_by_mm);
                        });
					}
					else{
						$(".thickness_line").css("display", "block");
						$(".thickness_dropdown").css("display", "none");
						$(".print_thickness:eq(0)").val("");
						$(".print_thickness").removeClass("thickness_enabled");
						$(".print_thickness:eq(1)").addClass("thickness_enabled");
						$(".print_thickness:eq(1)").val(content.thickness_by_mm[0]);
						$("#thickness_content").html(content.thickness_by_mm[0]+"mm");
					}
				}
				else{
					$(".thickness_dropdown, .thickness_line").css("display", "none");
				}

				if(typeof content.finishing != "undefined")
				{
                    $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    					if(content.finishing.length > 1)
    					{
    						$(".finishing_dropdown").css("display", "block");
    						$(".finishing_line").css("display", "none");
	                        var finishing = "";
	                        $(content.finishing).each(function(index){
    							//var find = " ";
    							//var re = new RegExp(find, 'g');
    							//var type = this.type.replace(re, "_");
		                        finishing += "<option value='"+this.type+"'>"+data[this.type]+"</option>";
	                        });
    						$(".print_finishing").removeClass("finishing_enabled");
    						$(".print_finishing:eq(0)").addClass("finishing_enabled");
    						//$(".print_finishing:eq(0) option").not($(".print_finishing:eq(0) option:eq(0)")).remove();
    						$(".print_finishing:eq(0) option").remove();
    						$(".print_finishing:eq(0) option:eq(0)").attr("selected", true);
                            $(".print_finishing:eq(0)").append(finishing);
    					}
    					else{
    						$(".finishing_line").css("display", "block");
    						$(".finishing_dropdown").css("display", "none");
    						$(".print_finishing").removeClass("finishing_enabled");
    						$(".print_finishing:eq(1)").addClass("finishing_enabled");
    						$("#finishing_content").html(data[content.finishing[0].type]);
    					}
                    });
				}
				else{
					$(".finishing_dropdown, .finishing_line").css("display", "none");
					$("#choose_frame").css("display", "none");
				}
				//laminate
				if(typeof content.laminate != "undefined")
				{
                    $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    					if(content.laminate.length > 1)
    					{
    						$(".laminate_dropdown").css("display", "block");
    						$(".laminate_line").css("display", "none");
    						var laminate = "";
    						$(content.laminate).each(function(index){
    							//var find = " ";
    							//var re = new RegExp(find, 'g');
    							//var type = this.type.replace(re, "_");
    							laminate += "<option value='"+this.type+"'>"+data[this.type]+"</option>";
    						});
                            $("label[for='print_laminate_hanging_select']").html(data['choose_lamination']);
    						$(".print_laminate_hanging").removeClass("laminate_enabled");
    						$(".print_laminate_hanging:eq(0)").addClass("laminate_enabled");
    						//$(".print_laminate_hanging:eq(0) option").not($(".print_laminate_hanging:eq(0) option:eq(0)")).remove();
    						$(".print_laminate_hanging:eq(0) option").remove();
    						$(".print_laminate_hanging:eq(0) option:eq(0)").attr("selected", true);
                            $(".print_laminate_hanging").append(laminate);
    					}
    					else{

    						$(".laminate_line").css("display", "block");
    						$(".laminate_dropdown").css("display", "none");
    						$(".print_laminate_hanging").removeClass("laminate_enabled");
    						$(".print_laminate_hanging:eq(1)").addClass("laminate_enabled");
	                        $(".print_laminate_hanging:eq(1)").val(content.laminate[0].type);
                            $("#laminate_content").html(data[content.laminate[0].type]);
    					}
                    });
				}
                else if(typeof content.hanging_type != "undefined")
				{
                    $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    					if(content.hanging_type.length > 1)
    					{
    						$(".laminate_dropdown").css("display", "block");
    						$(".laminate_line").css("display", "none");
    						var laminate = "";
    						$(content.hanging_type).each(function(index){
    							//var find = " ";
    							//var re = new RegExp(find, 'g');
    							//var type = this.type.replace(re, "_");
    							laminate += "<option value='"+this.type+"'>"+data[this.type]+"</option>";
    						});
                            $("label[for='print_laminate_hanging_select']").html(data['choose_hanging_type']);
    						$(".print_laminate_hanging").removeClass("laminate_enabled");
    						$(".print_laminate_hanging:eq(0)").addClass("laminate_enabled");
    						//$(".print_laminate_hanging:eq(0) option").not($(".print_laminate_hanging:eq(0) option:eq(0)")).remove();
    						$(".print_laminate_hanging:eq(0) option").remove();
    						$(".print_laminate_hanging:eq(0) option:eq(0)").attr("selected", true);
                            $(".print_laminate_hanging").append(laminate);
    					}
    					else{
    						$(".laminate_line").css("display", "block");
    						$(".laminate_dropdown").css("display", "none");
    						$(".print_laminate_hanging").removeClass("laminate_enabled");
    						$(".print_laminate_hanging:eq(1)").addClass("laminate_enabled");
	                        $(".print_laminate_hanging:eq(1)").val(content.hanging_type[0].type);
                            $("#laminate_content").html(data[content.hanging_type[0].type]);
    					}
                    });
				}
				else if(typeof content.laminate != "undefined" && typeof content.hanging_type != "undefined"){
					$(".print_laminate_hanging").removeClass("laminate_enabled");
					$(".laminate_dropdown, .laminate_line").css("display", "none");
					$("#print_laminate_check").val("");
					$("#print_laminate_hanging_select").val("");
				}
				calc();
			});
			$(".laminate_dropdown, .laminate_line").css("display", "none");
			$("#print_laminate_hanging_select").val("");
			$("#print_finishing_check").removeAttr("checked");
			$("#print_laminate_check").removeAttr("checked");
			$(".print_laminate_hanging").removeClass("laminate_enabled");
			$("#price").html("");
            $("#input_price").val(0);
            $("#add_to_cart").css("display", "none");
		}
		else{
			$("#print_width").val("");
			$("#print_height").val("");
			$("#price").html("");
            $("#input_price").val(0);
			$(".fieldsdisplay").css("display", "none");
            $("#add_to_cart").css("display", "none");
		}
	});
	$(".print_size").on("focus", function(){
		$("#price").html("");
        $("#input_price").val(0);
        $("#add_to_cart").css("display", "none");
	});

	$(".print_size").on("keyup blur", function(){
        var id = $(this).attr("id");
        var splittedVal = $("#"+id).val().split(".");
        if(splittedVal.length > 1){
            $("#"+id).val(splittedVal[0]);
        }
        calc()
    });
	$("#frame_extra").on("change", function(){
		if($(this).val() != "without")
		{
			$(".field_extra_thickness").css("display", "block");
			$("#frame_extra_thickness").addClass("field_extra_thickness_enabled");
		}
		else{
			$(".field_extra_thickness").css("display", "none");
			$("#frame_extra_thickness").removeClass("field_extra_thickness_enabled");
		}
		calc();
	});
});

$(document).ready(function(){
	$(".frame_size").on("blur keyup", function(){
        var id = $(this).attr("id");
        var splittedVal = $("#"+id).val().split(".");
        if(splittedVal.length > 1){
            $("#"+id).val(splittedVal[0]);
        }
		calc();
	});
	$("#frame_type").on("change", function(){
		var frame_type = $(this).val();
		$("#frame_size").val("");
		if(frame_type == "from_catalogue")
		{
			$(".field_extra, .field_cover, .field_color, .frame_error, .field_extra_thickness ").css("display", "none");
			$(".field_size_dropdown").css("display", "block");
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				var dimension = "";
				$(data.categories.frames.sizes[1].dimensions).each(function(index){
					dimension += '<option value="'+index+'">'+this[0]+'x'+this[1]+'</option>';
				});
				$("#frame_size option").not($("#frame_size option:eq(0)")).remove();
				$("#frame_size").append(dimension);
			});
		}
		else
		{
			$(".field_extra, .field_cover, .field_color, .frame_error").css("display", "block");
			$(".field_size_dropdown").css("display", "none");
		}
		calc();
	});

	$("#frame_cover").on("change", function(){
		calc();
	});
	$("#frame_extra").on("change", function(){
		calc();
	});
});
$("#add_to_cart").on("click", function(){
        var formdata = $("#print_form").serializeArray();
        return false;
        formdata.push({name: 'print_image', value: $("#print_image").val()});
        formdata.map(function(result){
            if(result.name == 'frame_required'){
                result.value = $("#frame_required").is(":checked");
            }
            return result;
        })
        //formdata.push({name: 'frame_required', value: $("#frame_required").is(":checked")});
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
.print_error{
    display: block;
    color: red;
}
.print_form2, .image_display
{
	display:none;
}

.thickness_dropdown, .thickness_line, .frame_required, .finishing_line, .laminate_line, .finishing_dropdown, .laminate_dropdown, .papertype_dropdown, .field_extra_thickness
{
	display:none;
}
.image_display #img_text{
    float: <?php echo $helper->getLang() === 'en' ? 'left' : 'right' ?>;
}
</style>
