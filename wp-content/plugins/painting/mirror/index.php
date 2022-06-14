<form action="/action_page.php" id="mirror_form">
<div class="woocommerce-message" role="alert">
	<a href="cart" tabindex="1" class="button wc-forward">View cart</a>
	<span class="message"></span>
</div>
<div class="painting-container two_sections">
	<div class="left_section">
			<input type="hidden" name="product_id" id="product_id" value="<?php echo $productID;?>">
			<input type="hidden" name="frame_selected" id="frame_selected" value="">
			<div class="form-group">
				<?php
				$options = "<option value=''>".$helper->getHebrewText('choose_one')."</option>";
				$mirrors = $jsondata['categories']['mirrors'];
				if (isset($mirrors) && !empty($mirrors)) {
					?>
						<label for="mirror_type"><?php echo $helper->getHebrewText('select_mirror'); ?>: </label>
						<select name="mirror_type" class="mirror_type" id="mirror_type">
					<?php
						foreach ($mirrors as $index => $mirror) {
							$mirror = $helper->getHebrewText($index);
							$options .= '<option value="'.$index.'">'.$mirror.'</option>';
						}
	                    echo $options;
                    ?>
						</select>
				<?php
				}
				?>
			</div>

			<div class="form-group for_institutions_field">
				<label><input type="checkbox" onclick="enableInstitutions(this);" class="for_institutions" id="for_institutions" name="for_institutions"/> <?php echo $helper->getHebrewText('mirror_to_institutions(including_aluminum_frame)'); ?></label>
			</div>

			<div class="form-group customizable_field">
				<label><input type="checkbox" onclick="enableCustomization(this);" class="customizable" id="customizable" name="customizable"/> <?php echo $helper->getHebrewText('customize_sizes'); ?></label>
			</div>

			<div class="form-group" id="mirror_size_div"></div>


			<div class="form-group thickness_dropdown">
				<label for='mirror_thickness'><?php echo $helper->getHebrewText('select_thickness'); ?>: </label>
				<select id='mirror_thickness' class="mirror_thickness" onchange='javascript:onthicknesschange(this);' name='mirror_thickness'><option value=''><?php echo $helper->getHebrewText('choose_one'); ?></option>
				</select>
			</div>

			<div class="form-group thickness_line">
				<label for="mirror_thickness"><?php echo $helper->getHebrewText('thickness'); ?>: <span id="thickness_content"></span></label><input type="hidden" name="mirror_thickness" class="mirror_thickness" id="mirror_thickness" value=""/>
			</div>


			<div class="form-group thickness_width">
				<input name="mirror_width" placeholder="<?php echo $helper->getHebrewText('width'); ?>" class="mirror_size" id="mirror_width" type="number" min="1" max="240" pattern="[0-9]"/>
                <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
			</div>
			<div class="form-group thickness_height">
				<input name="mirror_height" placeholder="<?php echo $helper->getHebrewText('height'); ?>" class="mirror_size" id="mirror_height" type="number" min="0" max="140" pattern="[0-9]"/>
                <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
			</div>
			<div class="form-group">
				<span class="mirror_error" id="mirror_size_error"></span>
			</div>

			<div class="form-group finishing_dropdown">
				<label for='mirror_finishing'><?php echo $helper->getHebrewText('select_finishing'); ?>: </label>
				<select id='mirror_finishing' class="mirror_finishing" onchange='onfinishingchange(this);calc();' name='mirror_finishing'><option value=''><?php echo $helper->getHebrewText('choose_one'); ?></option>
				</select>
			</div>

			<div class="form-group finishing_line">
				<label><input type="checkbox" name="mirror_finishing" class="mirror_finishing" id="mirror_finishing" onclick= "onfinishingclick(this)" value=""/> <?php echo $helper->getHebrewText('add'); ?> <span id="finishing_content"></span></label>
			</div>



			<div class="form-group hanging_type_dropdown">
				<label for='mirror_hanging_type'><?php echo $helper->getHebrewText('select_hanging_type'); ?>: </label>
				<select id='mirror_hanging_type' class='mirror_hanging_type' onchange='calc()' name='mirror_hanging_type'><option value=''><?php echo $helper->getHebrewText('choose_one'); ?></option>
				</select>
			</div>

			<div class="form-group hanging_type_line">
				<label for="mirror_hanging_type"><input type="checkbox" name="mirror_hanging_type" class="mirror_hanging_type" id="mirror_hanging_type" value=""/> <?php echo $helper->getHebrewText('hanging_type'); ?>: <span id="hanging_type_content"></label>
			</div>

			</div>
<div class="mid_section"></div>

<div class="right_section">
		<div class="frame_info">
			<?php include(plugin_dir_path(dirname(__FILE__)).'frames/frame-grid.php'); ?>
			<div class="notice"><?php echo $helper->getHebrewText('shipment_cost_will_be_priced_seperately');?></div>
		</div>
		<div class="frame_bot">
			<div class="form-group field_quantity item">
				<label for="mirror_quantity"><?php echo $helper->getHebrewText('quantity'); ?>:</label>
				<input class="style2 value" id="mirror_quantity" min="1" value="1" type="number" name="mirror_quantity" onchange="onquantitychange(this)" onkeyup="onquantitychange(this)" pattern="[0-9]"/>
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

	function getMul(total, num)
	{
		return total*num;
	}

	function onframesizechange(elem)
	{
		var sizeindex = $(elem).val();
		var quantity = $("#mirror_quantity").val();
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
				$("#price").html(parseInt(window.mirror_price)+Math.round(quantity*price));
				$("#input_price").val(parseInt(window.mirror_price)+Math.round(quantity*price));
				$("#add_to_cart").css("display", "block");
			}
			else{
				$("#price").html("");
				$("#input_price").val(0);
				$("#add_to_cart").css("display", "none");
			}
		});
	}



	function onquantitychange(elem)
	{
		calc();
	}

	function calc(){
		var page = $("#mirror_type").val();
		var mirror_width = parseInt($("#mirror_width").val());
		var mirror_height = parseInt($("#mirror_height").val());
		var thickness = $(".mirror_thickness.mirror_enabled").val();
		var mirror_finishing = $(".mirror_finishing.finishing_enabled").val();
		if($(".finishing_line").css("display") == "block")
		{
			var mirror_finishing = $(".mirror_finishing.finishing_enabled:checked").val();
		}

		var mirror_view = $(".mirror_hanging_type.hanging_type_enabled").val();
		var quantity = $("#mirror_quantity").val();
		if((typeof mirror_width != 'undefined' && mirror_width > 0) && (typeof mirror_height != 'undefined' && mirror_height > 0))
		{
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				var content = data.categories.mirrors[page];
			if(typeof content != 'undefined' && content.is_catalogue == false)
			{
				$(content.sizes).each(function(index){
					var size = this;
					if(size.thickness_by_mm == thickness)
					{
						var max_max_dimensions = Math.max.apply(null, size.max_dimensions);
						var min_max_dimensions = Math.min.apply(null, size.max_dimensions);
						var max_min_dimensions = Math.max.apply(null, size.min_dimensions);
						var min_min_dimensions = Math.min.apply(null, size.min_dimensions);

						//Checking for min width or height -> start here
						if(mirror_width < min_min_dimensions || mirror_height < min_min_dimensions || mirror_width > max_max_dimensions || mirror_height > max_max_dimensions)
						{
							$("#mirror_size_error").css("display", "block");
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    							$("#mirror_size_error").html(data['size_can_not_be_less_than'] + " "+size.min_dimensions[0]+"X"+size.min_dimensions[1]+ " " + data['and_can_not_be_more_than'] + size.max_dimensions[0]+"X"+size.max_dimensions[1]);
                            });
							$("#price").html("");
							$("#input_price").val(0);
							$("#add_to_cart").css("display", "none");
							return false;
						}
						else if(((mirror_width >= min_min_dimensions && mirror_width < max_min_dimensions) && mirror_height < max_min_dimensions) || ((mirror_height >= min_min_dimensions && mirror_height < max_min_dimensions) && mirror_width < max_min_dimensions))
						{
							$("#mirror_size_error").css("display", "block");
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    							$("#mirror_size_error").html(data['size_can_not_be_less_than'] + " " + size.min_dimensions[0]+"X" + size.min_dimensions[1]+ " " + data['and_can_not_be_more_than'] + size.max_dimensions[0]+"X"+size.max_dimensions[1]);
                            });
							$("#price").html("");
							$("#input_price").val(0);
							$("#add_to_cart").css("display", "none");
							return false;
						}
						else
						{
							$("#mirror_size_error").html("");
						}

						var calc_price = size.price/10000*(mirror_width*mirror_height);
						if(calc_price < size.min_price)
						{
							calc_price = size.min_price;
						}
						if(typeof mirror_finishing != 'undefined')
						{
							//var find = "_";
							//var re = new RegExp(find, 'g');
							//mirror_finishing = mirror_finishing.replace(re, " ");
							$(size.finishing).each(function(index){
								var finishing = this;
								if(mirror_finishing == finishing.type)
								{
									calc_price += (mirror_width + mirror_height) * 2 / 100 * finishing.price_by_meter;
									return false;
								}
							});
						}
						if(typeof mirror_view != 'undefined')
						{
							//var find = "_";
							//var re = new RegExp(find, 'g');
							//mirror_view = mirror_view.replace(re, " ");
							$(data.categories.mirrors[page].hanging_type).each(function(index){
								var types = this;
								if(mirror_view == types.name)
								{
									calc_price += types.price;
									return false;
								}
							});
						}
						$("#price").html(Math.round(calc_price)*quantity);
						$("#input_price").val(Math.round(calc_price)*quantity);
						$("#add_to_cart").css("display", "block");
						window.mirror_price = Math.round(calc_price)*quantity;
						if(typeof mirror_finishing != 'undefined')
						{
							var checked = $(".mirror_finishing.finishing_enabled").is(":checked");
							$(size.finishing).each(function(index){
								var finishing = this;
								if(checked == true && mirror_finishing == finishing.type && finishing.choose_frame == true)
								{
									frame_calc();
								}
							});
						}
					}
				});
			}
			else if(typeof data.categories.mirrors[page] != 'undefined' && data.categories.mirrors[page].is_catalogue == true)
			{
				$(data.categories.mirrors[page].catalogues[2].sizes).each(function(index){
					var size = this;
					var max_max_dimensions = Math.max.apply(null, size.max_dimensions);
					var min_max_dimensions = Math.min.apply(null, size.max_dimensions);
					var max_min_dimensions = Math.max.apply(null, size.min_dimensions);
					var min_min_dimensions = Math.min.apply(null, size.min_dimensions);

					//Checking for min width or height -> start here
					if(mirror_width < min_min_dimensions || mirror_height < min_min_dimensions || mirror_width > max_max_dimensions || mirror_height > max_max_dimensions)
					{
						$("#mirror_size_error").css("display", "block");
						$("#mirror_size_error").html("Size can not be less than "+size.min_dimensions[0]+"X"+size.min_dimensions[1]+" or more than " +size.max_dimensions[0]+"X"+size.max_dimensions[1]);
						$("#price").html("");
						$("#input_price").val(0);
						$("#add_to_cart").css("display", "none");
						return false;
					}
					else if(((mirror_width >= min_min_dimensions && mirror_width < max_min_dimensions) && mirror_height < max_min_dimensions) || ((mirror_height >= min_min_dimensions && mirror_height < max_min_dimensions) && mirror_width < max_min_dimensions))
					{
						$("#mirror_size_error").css("display", "block");
						$("#mirror_size_error").html("Size can not be less than "+size.min_dimensions[0]+"X"+size.min_dimensions[1]+" or more than " +size.max_dimensions[0]+"X"+size.max_dimensions[1]);
						$("#price").html("");
						$("#input_price").val(0);
						$("#add_to_cart").css("display", "none");
						return false;
					}
					else
					{
						$("#mirror_size_error").html("");
					}
					var calc_price = size.price/10000*(mirror_width*mirror_height);
					if(calc_price < size.min_price)
					{
						calc_price = size.min_price;
					}

					$("#price").html(Math.round(calc_price)*quantity);
					$("#input_price").val(Math.round(calc_price)*quantity);
					$("#add_to_cart").css("display", "block");
				});


			}
		});
		}
	}


	function frame_calc(){
		var frame_type = $("#frame_type").val();
		if(frame_type != "from_catalogue")
		{
			var frame_width = parseInt($("#mirror_width").val());
			var frame_height = parseInt($("#mirror_height").val());
			var frame_extra = $("#frame_extra").val();
			var frame_cover = $("#frame_cover").val();
			var frame_colors = $("#frame_colors").val();
			var quantity = $("#mirror_quantity").val();
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
							//$("#frame_size_error").html("");
							if(typeof frame_cover != 'undefined')
							{
								$(data.categories.frames.sizes[0].covers).each(function(index){
									if(typeof this[frame_cover].price != 'undefined')
									{
										price += parseInt(this[frame_cover].price)*(frame_width * frame_height)/10000;
										return false;
									}
								});
							}
							if(price < data.categories.frames.sizes[0].min_price)
							{
								price = data.categories.frames.sizes[0].min_price;
							}
							$(data.categories.frames.sizes[0].extras).each(function(index){
								if(typeof frame_extra != "undefined")
								{
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
								}
							});
							$("#price").html(parseInt(window.mirror_price)+Math.round(quantity*price));
							$("#input_price").val(parseInt(window.mirror_price)+Math.round(quantity*price));
							$("#add_to_cart").css("display", "block");
						}

					});
				}
			}
			else{
				$("#price").html(parseInt(window.mirror_price));
				$("#input_price").val(parseInt(window.mirror_price));
				$("#add_to_cart").css("display", "block");
			}
		}
	}

	function onthicknesschange(elem){
		//$("#mirror_width").val("");
		//$("#mirror_height").val("");
		$("#price").html("");
		$("#input_price").val(0);
		$("#add_to_cart").css("display", "none");
		//$(".fieldsdisplay").css("display", "block");
		//$("#mirror_size").val("");
		//$("#mirror_size_div").css("display", "none");
		var thickness = $(elem).val();
		var val = $("#mirror_type").val();
		$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
			var content = data.categories.mirrors[val];
			if(content.sizes.length > 1)
			{
				$(content.sizes).each(function(){
					if(thickness == this.thickness_by_mm)
					{
						if(typeof this.finishing != "undefined")
						{
                            var self = this;
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    							if(self.finishing.length > 1)
    							{
    								$(".finishing_dropdown").css("display", "block");
    								var finishing = "";
    								$(self.finishing).each(function(index){
    									//var find = " ";
    									//var re = new RegExp(find, 'g');
    									//var type = this.type.replace(re, "_");
    									finishing += "<option value="+this.type+">"+data[this.type]+"</option>";
    								});
    								$(".finishing_line").css("display", "none");
    								$(".mirror_finishing").removeClass("finishing_enabled");
    								$(".mirror_finishing:eq(0)").addClass("finishing_enabled");
    								$(".mirror_finishing:eq(0) option").not($(".mirror_finishing:eq(0) option:eq(0)")).remove();
    								$(".mirror_finishing").append(finishing);
    							}
    							else{
    								$(".finishing_line").css("display", "block");
    								$(".finishing_dropdown").css("display", "none");
    								$(".mirror_finishing").removeClass("finishing_enabled");
    								$(".mirror_finishing:eq(1)").addClass("finishing_enabled");
    								$(".mirror_finishing:eq(1)").val(self.finishing[0].type);
    								$("#finishing_content").html(data[self.finishing[0].type]);
    							}
                            });
						}
						else{
							$(".finishing_dropdown, .finishing_line").css("display", "none");
							//$("#mirror_finishing_div").html("");
							$("#choose_frame").val("");
						}
						return false;
					}
				})
			}

		});

		$("#choose_frame").val("");
		//$(".choose_frame_field, #choose_frame").css("display", "none");
		calc();
	}

	function onsizechange(elem, catalogue_index){
		var val =$(elem).val();
		var page = $("#mirror_type").val();
		if(typeof val != 'undefined' && val != '')
		{
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				if(typeof data.categories.mirrors[page] != 'undefined')
				{
					$("#price").html(data.categories.mirrors[page].catalogues[catalogue_index].prices[val]);
					$("#input_price").val(data.categories.mirrors[page].catalogues[catalogue_index].prices[val]);
					$("#add_to_cart").css("display", "block");
				}
				$("#thickness_content").html(data.categories.mirrors[page].catalogues[catalogue_index].thickness[val]+"mm");
				$(".mirror_thickness").removeClass("mirror_enabled");
				$(".mirror_thickness:eq(1)").addClass("mirror_enabled");
				$(".mirror_thickness:eq(1)").val(data.categories.mirrors[page].catalogues[catalogue_index].thickness[val]);
				$(".thickness_line").css("display", "block");
				$(".thickness_dropdown").css("display", "none");
				$(".mirror_thickness:eq(0)").val("");
				//$("#mirror_thickness_div").html('<label for="mirror_width">Thickness: '+data.categories.mirrors[page].catalogues[catalogue_index].thickness[val]+'mm</label>');
			});
			//$("#mirror_thickness_div").css("display", "block");
			$("#price").html("");
			$("#input_price").val(0);
			$("#add_to_cart").css("display", "none");
		}
	}

	function onfinishingchange(elem){
		var page = $("#mirror_type").val();
		var value = $(elem).val();
		//var find = "_";
		//var re = new RegExp(find, 'g');
		//value = value.replace(re, " ");
		var thickness = $(".mirror_thickness.mirror_enabled").val();

		$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
			var content = data.categories.mirrors[page];
			/* if(content.sizes.length > 1)
			{ */
				$(content.sizes).each(function(){
					if(thickness == this.thickness_by_mm)
					{
						if(typeof this.finishing != "undefined")
						{
							$(this.finishing).each(function(index){
								var finishing = this;
								if(finishing.type == value && finishing.choose_frame == true)
								{
									$(".frame_grid").css("display", "block");
									onframechose($(".frame_img:eq(0)"));
									//$(".choose_frame_field, #choose_frame").css("display", "block");
									return false;
								}
								else
								{
									$(".frame_grid").css("display", "none");
									$("#frame_selected").val("");
									//$(".choose_frame_field #choose_frame").css("display", "none");
									$("#choose_frame").val("");
								}
							});
						}
						return false;
					}
				});
			/* } */
		});
		calc();
	}

	function onfinishingclick(elem)
	{
		var page = $("#mirror_type").val();
		var value = $(elem).val();
		var checked = $(elem).is(":checked");
		//var find = "_";
		//var re = new RegExp(find, 'g');
		//value = value.replace(re, " ");

		var thickness = $(".mirror_thickness.mirror_enabled").val();

		$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
			var content = data.categories.mirrors[page];
			/* if(content.sizes.length > 1)
			{ */
				$(content.sizes).each(function(){
					if(thickness == this.thickness_by_mm)
					{
						if(typeof this.finishing != "undefined")
						{
							$(this.finishing).each(function(index){
								var finishing = this;
								if(checked == true && finishing.type == value && finishing.choose_frame == true)
								{
									$(".frame_grid").css("display", "block");
									//$(".choose_frame_field, #choose_frame").css("display", "block");
									return false;
								}
								else
								{
                                    $("#frame_selected").val("");
                                    $(".frame_img").removeClass("selected");
									$(".frame_grid, .selected_frame_detail").css("display", "none");
                                    $("#selected_frame_name, #selected_frame_description, #selected_frame_color, #selected_frame_type, #selected_frame_price").html("");
									//$(".choose_frame_field, #choose_frame").css("display", "none");
									//$("#choose_frame").val("");
								}
							});
						}
						return false;
					}
				});
			/* } */
		});
		calc();

	}

	function enableInstitutions(elem)
	{
		var page = $("#mirror_type").val();
		$(".thickness_width, .thickness_height").css("display", "none");
		$("#price").html("");
		$("#input_price").val(0);
		$("#add_to_cart").css("display", "none");
		$("#mirror_width, #mirror_height").val("");
		if($(elem).is(":checked") == false)
		{
			$(".customizable_field").css("display", "none");
			$("#customizable").val("");
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
					var content = data.categories.mirrors[page];
					if(typeof content.is_catalogue != "undefined" && content.is_catalogue == true)
					{
						$(content.catalogues).each(function(){
							if(this.is_for_institutions == false)
							{
                                $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    								var size = "<label for='mirror_size'>"+data['select_catalogue_size']+" </label><select id='mirror_size' onchange='javascript:onsizechange(this, 0)' name='mirror_size'><option value=''>"+data['select_size']+"</option>";
                                });
								$(this.dimensions).each(function(index){
									size += "<option value='"+index+"'>"+this[0]+"x"+this[1]+"</option>";
								});

								size += "</select>";
								$("#mirror_size_div").html(size);
								//$(".fieldsdisplay").css("display", "none");
								$("#mirror_size_div").css("display", "block");
								return false;
							}
						});
					}
				});
		}
		else{
			$(".customizable_field").css("display", "block");
			if($("#customizable").is(":checked") == false)
			{
				$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
					var content = data.categories.mirrors[page];
					if(typeof content.is_catalogue != "undefined" && content.is_catalogue == true)
					{
						$(content.catalogues).each(function(){
							if(this.is_for_institutions == true && this.customizable == false)
							{
                                $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    								var size = "<label for='mirror_size'>"+data['select_catalogue_size']+" </label><select id='mirror_size' onchange='javascript:onsizechange(this, 1)' name='mirror_size'><option value=''>"+data['select_size']+"</option>";
                                });
								$(this.dimensions).each(function(index){
									size += "<option value='"+index+"'>"+this[0]+"x"+this[1]+"</option>";
								});

								size += "</select>";
								$("#mirror_size_div").html(size);
								//$(".fieldsdisplay").css("display", "none");
								$("#mirror_size_div").css("display", "block");
								return false;
							}
						});
					}
				});
			}
			else{
				$("#mirror_size_div").html("");
				$("#mirror_size_div").css("display", "none");
				$(".mirror_thickness").removeClass("mirror_enabled");
				$(".mirror_thickness:eq(1)").val("");
				$(".thickness_line").css("display", "none");
				$(".thickness_width, .thickness_height").css("display", "block");
				calc();
			}
		}

	}

	function enableCustomization(elem)
	{
		var page = $("#mirror_type").val();
		$(".thickness_width, .thickness_height").css("display", "none");
		$("#price").html("");
		$("#input_price").val(0);
		$("#add_to_cart").css("display", "none");
		$("#mirror_width, #mirror_height").val("");
		if($("#for_institutions").is(":checked") == true && $("#customizable").is(":checked") == false)
		{
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
				var content = data.categories.mirrors[page];
				if(typeof content.is_catalogue != "undefined" && content.is_catalogue == true)
				{
					$(content.catalogues).each(function(){
						if(this.is_for_institutions == true && this.customizable == false)
						{
                            var self = this;
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
	                            var size = "<label for='mirror_size'>"+data['select_catalogue_size:']+ " </label><select id='mirror_size' onchange='javascript:onsizechange(this, 1)' name='mirror_size'><option value=''>"+data['select_size']+"</option>";
                            });
							$(this.dimensions).each(function(index){
								size += "<option value='"+index+"'>"+this[0]+"x"+this[1]+"</option>";
							});

							size += "</select>";
							$("#mirror_size_div").html(size);
							//$(".fieldsdisplay").css("display", "none");
							$("#mirror_size_div").css("display", "block");
							return false;
						}
					});
				}
			});
		}else if($("#for_institutions").is(":checked") == true && $("#customizable").is(":checked") == true)
		{
			$("#mirror_size_div").html("");
			$("#mirror_size_div").css("display", "none");
			$(".mirror_thickness").removeClass("mirror_enabled");
			$(".mirror_thickness:eq(1)").val("");
			$(".thickness_line").css("display", "none");
			$(".thickness_width, .thickness_height").css("display", "block");
			calc();
		}
	}


	$(document).ready(function(){
		$("#mirror_type").on("change", function(){
			var val = $(this).val();
			if(val != '')
			{
				$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
					var content = data.categories.mirrors[val];
					if(typeof content.is_catalogue != "undefined" && content.is_catalogue == false)
					{
						//$(".thickness_dropdown, .thickness_line").css("display", "none");
						$("#mirror_size_div").html("");
						$("#mirror_size_div").css("display", "none");
						$(".thickness_width, .thickness_height").css("display", "block");
						$("#for_institutions, #customizable").val("");
						$("#for_institutions, #customizable").removeAttr("checked");
						$(".for_institutions_field, .customizable_field").css("display", "none");
						$(".mirror_finishing").attr("checked", false);
						if(typeof content.sizes != "undefined")
						{
							if(content.sizes.length > 1)
							{
                                $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
								    var thickness = "";
    								$(content.sizes).each(function(index){
    									thickness += "<option value="+this.thickness_by_mm+">"+this.thickness_by_mm + data['mm'] + "</option>";
    								});
    								thickness += "</select>";
    								$(".mirror_thickness").removeClass("mirror_enabled");
    								$(".mirror_thickness:eq(0)").addClass("mirror_enabled");
    								$(".mirror_thickness:eq(0) option").not($(".mirror_thickness:eq(0) option:eq(0)")).remove();
    								$(".mirror_thickness:eq(0)").append(thickness);
    								$(".mirror_thickness:eq(1)").val("");
    								$(".thickness_line").css("display", "none");
    								$(".thickness_dropdown").css("display", "block");
    								$("#choose_frame").val("");
    								//$(".choose_frame_field, #choose_frame").css("display", "none");
    								//$(".fieldsdisplay").css("display", "none");
    								//$("#mirror_thickness_div").css("display", "block");
    								$(".finishing_line, .finishing_dropdown").css("display","none");
    								$(".mirror_finishing").removeClass("finishing_enabled");
    								$(".mirror_finishing").html("");
                                });
							}
							else{
								$("#thickness_content").html(content.sizes[0].thickness_by_mm+"mm");
								$(".mirror_thickness").removeClass("mirror_enabled");
								$(".mirror_thickness:eq(1)").addClass("mirror_enabled");
								$(".mirror_thickness:eq(1)").val(content.sizes[0].thickness_by_mm);
								$(".thickness_line").css("display", "block");
								$(".thickness_dropdown").css("display", "none");
								$(".mirror_thickness:eq(0)").val("");
								/* $(".fieldsdisplay").css("display", "block");
								$("#mirror_finishing").val("");
								$("#choose_frame").val("");
								$("#choose_frame").css("display", "none");
								$("#mirror_size").val("");
								$("#mirror_size_div").css("display", "none"); */

								if(typeof content.sizes[0].finishing != "undefined")
								{
                                    $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    									if(content.sizes[0].finishing.length > 1)
    									{
    										$(".finishing_dropdown").css("display", "block");
    										var finishing = "";
    										$(content.sizes[0].finishing).each(function(index){
    											//var find = " ";
    											//var re = new RegExp(find, 'g');
    											//var type = this.type.replace(re, "_");
    											finishing += "<option value="+this.type+">"+data[this.type]+"</option>";
    										});
    										$(".finishing_line").css("display", "none");
    										$(".mirror_finishing").removeClass("finishing_enabled");
    										$(".mirror_finishing:eq(0)").addClass("finishing_enabled");
    										$(".mirror_finishing:eq(0) option").not($(".mirror_finishing:eq(0) option:eq(0)")).remove();
    										$(".mirror_finishing").append(finishing);
    									}
    									else{
    										$(".finishing_line").css("display", "block");
    										$(".finishing_dropdown").css("display", "none");
    										$(".mirror_finishing").removeClass("finishing_enabled");
    										$(".mirror_finishing:eq(1)").addClass("finishing_enabled");
    										$(".mirror_finishing:eq(1)").val(content.sizes[0].finishing[0].type);
    										$("#finishing_content").html(data[content.sizes[0].finishing[0].type]);
    									}
                                    });
								}
								else{
									$(".finishing_dropdown, .finishing_line").css("display", "none");
									//$("#mirror_finishing_div").html("");
									$("#choose_frame").val("");
								}


							}
						}



						/*
						Can be used later on for making finishing global
						if(typeof content.finishing != "undefined")
						{
							if(content.finishing.length > 1)
							{
								$(".finishing_dropdown").css("display", "block");
								var finishing = "";
								$(content.finishing).each(function(index){
									var find = " ";
									var re = new RegExp(find, 'g');
									var type = this.type.replace(re, "_");
									finishing += "<option value="+type+">"+this.type+"</option>";
								});
								$(".finishing_line").css("display", "none");
								$(".mirror_finishing").removeClass("finishing_enabled");
								$(".mirror_finishing:eq(0)").addClass("finishing_enabled");
								$(".mirror_finishing:eq(0) option").not($(".mirror_finishing:eq(0) option:eq(0)")).remove();
								$(".mirror_finishing").append(finishing);
							}
							else{
								$(".finishing_line").css("display", "block");
								$(".finishing_dropdown").css("display", "none");
								$(".mirror_finishing").removeClass("finishing_enabled");
								$(".mirror_finishing:eq(1)").addClass("finishing_enabled");
								$(".mirror_finishing:eq(1)").val(content.finishing[0].type);
								$("#finishing_content").html(content.finishing[0].type);
							}
						}
						else{
							$(".finishing_dropdown, .finishing_line, .choose_frame_field, #choose_frame").css("display", "none");
							//$("#mirror_finishing_div").html("");
							$("#choose_frame").val("");
						}*/


						if(typeof content.hanging_type != "undefined")
						{
                            $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
    							if(content.hanging_type.length > 1)
    							{
    								$(".hanging_type_dropdown").css("display", "block");
    								var hanging_type = "";
    								$(content.hanging_type).each(function(index){
    									//var find = " ";
    									//var re = new RegExp(find, 'g');
    									//var name = this.name.replace(re, "_");
    									hanging_type += "<option value="+this.name+">"+data[this.name]+"</option>";
    								});
    								$(".mirror_hanging_type").removeClass("hanging_type_enabled");
    								$(".mirror_hanging_type:eq(0)").addClass("hanging_type_enabled");
    								$(".mirror_hanging_type:eq(0) option").not($(".mirror_hanging_type:eq(0) option:eq(0)")).remove();
    								$(".mirror_hanging_type").append(hanging_type);
    							}
    							else{
    								$(".hanging_type_line").css("display", "block");
    								$(".mirror_hanging_type").removeClass("hanging_type_enabled");
    								$(".mirror_hanging_type:eq(1)").addClass("hanging_type_enabled");
    								$(".mirror_hanging_type:eq(1)").val(content.hanging_type[0].name);
    								$("#hanging_type_content").html(data[content.hanging_type[0].name]);
    							}
                            });
						}
						else{
							$(".hanging_type_dropdown, .hanging_type_line").css("display", "none");
						}
						calc();
					}
					else{
						$(".thickness_dropdown, .thickness_width, .thickness_height, .finishing_dropdown, .finishing_line, .hanging_type_dropdown, .hanging_type_line, .mirror_error, .choose_frame_field, .thickness_line").css("display", "none");
						$(".mirror_finishing").removeClass("finishing_enabled");
						$(".mirror_finishing").html("");
						$("#mirror_thickness").val("");
						$(".for_institutions_field").css("display", "block");
						$(".choose_frame").val("");

						if($("#for_institutions").is(":checked") == false)
						{
							$(content.catalogues).each(function(){
								if(this.is_for_institutions == false)
								{
                                    $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
									    var size = "<label for='mirror_size'>"+data['select_catalogue_size']+" </label><select id='mirror_size' onchange='javascript:onsizechange(this, 0)' name='mirror_size'><option value=''>"+data['select_size']+"</option>";
                                    });
									$(this.dimensions).each(function(index){
										size += "<option value='"+index+"'>"+this[0]+"x"+this[1]+"</option>";
									});

									size += "</select>";
									$("#mirror_size_div").html(size);
									//$(".fieldsdisplay").css("display", "none");
									$("#mirror_size_div").css("display", "block");
									return false;
								}
							})

						}
						//calc();
					}
				});
				//$("#mirror_width").val("");
				//$("#mirror_height").val("");
			$("#price").html("");
			$("#input_price").val(0);
			$("#add_to_cart").css("display", "none");
			//calc();
		}
		else{
			$("#mirror_width").val("");
			$("#mirror_height").val("");
			$("#price").html("");
			$("#input_price").val(0);
			$("#add_to_cart").css("display", "none");
			$(".fieldsdisplay").css("display", "none");
		}

		});

		$(".mirror_size").on("focus", function(){
			$("#price").html("");
			$("#input_price").val(0);
			$("#add_to_cart").css("display", "none");
		});

		$(".mirror_size").on("keyup blur", function(){
			var id = $(this).attr("id");
			var splittedVal = $("#"+id).val().split(".");
			if(splittedVal.length > 1){
				$("#"+id).val(splittedVal[0]);
			}
			calc();
		});
	});

	$(document).ready(function(){
		$(".frame_size").on("blur keyup", function(){
			calc();
		});
		$("#frame_type").on("change", function(){
			var frame_type = $(this).val();
			$("#frame_size").val("");
			if(frame_type == "from_catalogue")
			{
				$(".field_extra, .field_cover, .frame_error").css("display", "none");
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
				$(".field_extra, .field_cover, .frame_error").css("display", "block");
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
			var formdata = $("#mirror_form").serializeArray();
			formdata.push({name: 'mirror_thickness_enabled', value: $(".mirror_thickness.mirror_enabled").val()});

			var mirror_finishing = $(".mirror_finishing.finishing_enabled").val();
			if($(".finishing_line").css("display") == "block")
			{
				var mirror_finishing = $(".mirror_finishing.finishing_enabled:checked").val();
			}
			formdata.push({name: 'mirror_finishing_enabled', value: mirror_finishing});
			if($("#mirror_type").val() == 'from_catalogue'){
				formdata.map(function(result){
					if(result.name == 'for_institutions'){
						result.value = $("#for_institutions").is(":checked");
					}
					if(result.name == 'customizable'){
						result.value = $("#customizable").is(":checked");
					}
					return result;
				});
				if(!$("#customizable").is(":checked")){
					var mirror_size = $("#mirror_size option:selected").html();
					formdata.push({name: 'mirror_size', value: mirror_size});
				}
				formdata.push({name: 'mirror_thickness', value: $("#mirror_thickness").val()});
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
	.mirror_error{
		display: block;
		color: red;
	}

	.thickness_line, .finishing_line, .hanging_type_line, .for_institutions_field, .customizable_field, .mirror_size_div
	{
		display:none;
	}

	.field_size_dropdown
	{
		display:none;
	}
	.frame_error{
		display: block;
		color: red;
	}
	img.frame_img.selected {
		border: 2px solid #ccc;
	}
    .frame_grid{
        display:none;
    }
	</style>
</div>
