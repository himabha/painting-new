<?php
// session_start();
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once WC_ABSPATH . 'includes/class-wc-cart.php';
function change_price_by_type($product_id, $multiply_price_by, $price_type)
{
    $the_price = get_post_meta($product_id, '_' . $price_type, true);
    $the_price = $multiply_price_by;
    update_post_meta($product_id, '_' . $price_type, $the_price);
}

function change_price_all_types($product_id, $multiply_price_by)
{
    change_price_by_type($product_id, $multiply_price_by, 'price');
    change_price_by_type($product_id, $multiply_price_by, 'sale_price');
    change_price_by_type($product_id, $multiply_price_by, 'regular_price');
}

/*
 * `change_product_price` is main function you should call to change product's price
 */
function change_product_price($product_id, $multiply_price_by)
{
    change_price_all_types($product_id, $multiply_price_by);
    $product = wc_get_product($product_id); // Handling variable products
    if ($product->is_type('variable')) {
        $variations = $product->get_available_variations();
        foreach ($variations as $variation) {
            change_price_all_types($variation['variation_id'], $multiply_price_by);
        }
    }
}
    if (isset($_POST) && !empty($_POST)) {
        if (isset($productID)) {
            global $woocommerce;
            if (empty($woocommerce->cart->cart_contents)) {
                //$testcart = new WC_Cart;
                //$testcart->add_to_cart($productID);

                //echo "<script>window.open('https://www.shaked-g.com/bengoor/shop?add-to-cart=".$productID."')</script>";


            // create curl resource
                /*$ch = curl_init();
                echo "https://www.shaked-g.com/bengoor/shop?add-to-cart=".$productID;

                // set url
                curl_setopt($ch, CURLOPT_URL, "https://www.shaked-g.com/bengoor/shop?add-to-cart=".$productID);

                //return the transfer as a string
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                // $output contains the output string
                if (curl_exec($ch) === false) {
                    echo 'Curl error: ' . curl_error($ch);
                } else {
                    echo 'Operation completed without any errors';
                }

                // close curl resource to free up system resources
                curl_close($ch);*/
            } else {
                $woocommerce->cart->add_to_cart($productID);
            }
        }
        //change_product_price($productID, (int)$_POST['input_price']);
        //$testcart = new WC_Cart;
        //$testcart->add_to_cart($productID, 1, 0, array(), $woocommerce->cart->cart_contents);


        /*$cart = $woocommerce->cart->cart_contents;
        foreach ($cart as $cart_item_id=>$cart_item) {
            $cart_item['data']->set_price((int)$_POST['input_price']);
            $woocommerce->cart->cart_contents[$cart_item_id] = $cart_item;
        }
        echo "<pre>";
        $product = wc_get_product(5124); // Handling variable products
        print_r($product);
        print_r($woocommerce->cart->cart_contents);
        $woocommerce->cart->set_session();*/
        /*
        $woocommerce->cart->add_to_cart->add_to_cart(5124);
        $cart = $woocommerce->cart->cart_contents;
        foreach ($cart as $cart_item_id=>$cart_item) {
            $cart_item['data']->set_price(50);
            $woocommerce->cart->cart_contents[$cart_item_id] = $cart_item;
        }
        echo "<pre>test";
        print_r($woocommerce->cart->cart_contents);
        $woocommerce->cart->set_session();
        global $woocommerce;
        $testcart->add_to_cart(5124);

        $option = array("test"=>"test");
        $new_value = array('wdm_user_custom_data_value' => $option);
        if (empty($option)) {
            return $cart_item_data;
        } else {
            if (empty($cart_item_data)) {
                return $new_value;
            } else {
                return array_merge($cart_item_data, $new_value);
            }
        }
        unset($_SESSION['wdm_user_custom_data']);*/
    }
?>
<form action="" id="frame_form" method="POST">
   <div class="woocommerce-message" role="alert">
      <a href="cart" tabindex="1" class="button wc-forward">View cart</a><span class="message"></span>
   </div>
   <div class="painting-container two_sections">
   <!-- LEFT SECTION START -->
   <div class="left_section">
      <input type="hidden" name="product_id" id="product_id" value="<?php echo $productID;?>">
      <input type="hidden" name="frame_selected" id="frame_selected" value="">
      <div class="form-group" style="display: none;">
         <label for="frame_source"><?php echo $helper->getHebrewText('select_frame_source');?>: </label>
         <select name="frame_source" class="frame_source" id="frame_source">
            <option value=""><?php echo $helper->getHebrewText('choose_one');?></option>
            <option value="from_grid"><?php echo $helper->getHebrewText('from_grid');?></option>
            <option value="from_catalogue"><?php echo $helper->getHebrewText('from_catalogue');?></option>
         </select>
      </div>
      <div class="tab-label"><?php echo $helper->getHebrewText('select_frame_source');?>:</div>
      <div class="tab-selector">
         <!-- A mask to trigger #frame_source for styling purposes -->
         <div id="tab_grid" class="tab-name active" onclick='$("#frame_source").val("from_grid").change()'><?php echo $helper->getHebrewText('from_grid');?></div>
         <div id="tab_cat" class="tab-name" onclick='$("#frame_source").val("from_catalogue").change()'><?php echo $helper->getHebrewText('from_catalogue');?></div>
      </div>
      <hr/>
      <div class="form-title">
         <span><?php echo $helper->getHebrewText('enter_frame_details');?></span>
      </div>
      <div class="form-group field_width">
         <input name="frame_width" placeholder="<?php echo $helper->getHebrewText('width');?>" class="frame_size" id="frame_width" type="number" min="0" max="120" pattern="[0-9]"/>
         <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
      </div>
      <div class="form-group field_height">
         <input name="frame_height" placeholder="<?php echo $helper->getHebrewText('height');?>" class="frame_size" id="frame_height" type="number" min="0" max="140" pattern="[0-9]"/>
         <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
      </div>
      <div class="form-group">
         <span class="frame_error" id="frame_size_error"></span>
      </div>
      <div class="form-group field_size_dropdown">
         <label for="frame_size"><?php echo $helper->getHebrewText('select_size');?>:</label>
         <select id="frame_size" onchange="onsizechange(this);">
            <option value=""><?php echo $helper->getHebrewText('choose_one');?></option>
         </select>
      </div>
      <div class="form-group field_cover">
         <label for="frame_cover"><?php echo $helper->getHebrewText('select_cover_type');?>: </label>
         <select name="frame_cover" class="frame_cover" id="frame_cover">
         <?php
            $options = "";
            $covers = $jsondata['categories']['frames']['sizes'][0]['covers'];
            if (isset($covers) && !empty($covers)) {
                foreach ($covers as $key_cover => $cover) {
                    $cover = $helper->getHebrewText($key_cover);
                    $options .= '<option value="'.$key_cover.'">'.$cover.'</option>';
                }
            }
            echo $options;
            ?>
         </select>
      </div>
      <div class="form-group field_extra">
         <label for="frame_extra"><?php echo $helper->getHebrewText('select_extras');?>: </label>
         <select name="frame_extra" class="frame_extra" id="frame_extra" onchange="onextrachange();">
         <?php
            $options = "";
            $extras = $jsondata['categories']['frames']['sizes'][0]['extras'];
            if (isset($extras) && !empty($extras)) {
                foreach ($extras as $key_extra => $extra) {
                    $extra= $helper->getHebrewText($key_extra);
                    $options .= '<option value="'.$key_extra.'">'.$extra.'</option>';
                }
            }
            echo $options;
            ?>
         </select>
      </div>
      <div class="form-group field_extra_thickness">
         <label for="frame_extra_thickness"><?php echo $helper->getHebrewText('select_width');?>: </label>
            <input id="frame_extra_thickness" min="2" max="10" value="2" type="number" name="frame_extra_thickness" onchange="onquantitychange(this)" onkeyup="onextrathicknesschange()" pattern="[0-9]"/>
            <span class="input-label"><?php echo $helper->getHebrewText('cm');?></span>
      </div>
      <div class="form-group field_color">
         <label for="frame_colors"><?php echo $helper->getHebrewText('select_color');?>: </label>
         <select name="frame_colors" class="frame_colors" id="frame_colors">
         <?php
            $options = "<option selected='selected'>". $helper->getHebrewText('choose_one') ."</option>";
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
   <!-- LEFT SECTION END -->
   <!-- MID SECTION START -->
   <div class="mid_section frame_display">
      <!-- <img alt="frame preview" src="<?php echo plugin_dir_url( __DIR__ ) . 'images/default.png'; ?>"/> -->
   </div>
   <!-- MID SECTION END -->
   <!-- RIGHT SECTION START -->
   <div class="right_section">
      <div class="frame_info">
         <?php include(plugin_dir_path(__FILE__).'frame-grid.php'); ?>
         <div class="notice"><?php echo $helper->getHebrewText('shipment_cost_will_be_priced_seperately');?></div>
      </div>
      <div class="frame_bot">
         <div class="form-group field_quantity item">
            <label for="frame_quantity"><?php echo $helper->getHebrewText('quantity');?>:</label>
            <input class="style2 value" id="frame_quantity" min="1" value="1" type="number" name="frame_quantity" onchange="onquantitychange(this)" onkeyup="onquantitychange(this)" pattern="[0-9]"/>
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
   <!-- RIGHT SECTION END -->
</form>
    <script>
    var lang_json = "";
    function getMul(total, num)
    {
        return total*num;
    }

    function onextrathicknesschange()
    {
        calc();
    }
    function onextrachange()
    {
        calc();
    }
    function onsizechange(elem)
    {
        var sizeindex = $(elem).val();
        var quantity = $("#frame_quantity").val();
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
                $("#price").html(Math.round(quantity*price));
                $("#input_price").val(Math.round(quantity*price));
                $("#card_amount").val(Math.round(quantity*price));
                $("#add_to_cart").css("display", "block");
            }
            else{
                $("#add_to_cart").css("display", "none");
                $("#price").html("");
                $("#input_price").val(0);
                $("#card_amount").val("");
            }
        });
    }
    function onquantitychange(elem)
    {
        var frame_source = $("#frame_source").val();
        if(frame_source == "from_catalogue")
        {
            var sizeindex = $("#frame_size").val();
            var quantity = $(elem).val();
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
                    $("#price").html(Math.round(quantity*price));
                    $("#input_price").val(Math.round(quantity*price));
                    $("#card_amount").val(Math.round(quantity*price));
                    $("#add_to_cart").css("display", "block");
                }
                else{
                    $("#price").html("");
                    $("#input_price").val(0);
                    $("#card_amount").val("");
                    $("#add_to_cart").css("display", "none");
                }
            });
        }
        else
        {
            calc();
        }
    }

    function calc(){
        var frame_source = $("#frame_source").val();
        if(frame_source != "from_catalogue")
        {
            var frame_width = parseInt($("#frame_width").val());
            var frame_height = parseInt($("#frame_height").val());
            var frame_extra = $("#frame_extra").val();
            var frame_cover = $("#frame_cover").val();
            var frame_colors = $("#frame_colors").val();
            var quantity = $("#frame_quantity").val();
            var frame_selected = $("#frame_selected").val();
            var frame_extra_thickness = parseInt((typeof $("#frame_extra_thickness.field_extra_thickness_enabled").val() != "undefined") ? $("#frame_extra_thickness.field_extra_thickness_enabled").val() : 0);
            if(typeof frame_selected != "undefined" && frame_selected != "" )
            {
            if(typeof frame_width != 'undefined' && frame_width > 0 && typeof frame_height != 'undefined' && frame_height > 0)
            {
                var frame_selected = JSON.parse(frame_selected);
                var price = frame_selected.price;
                price = parseInt((frame_width + frame_height + (frame_extra_thickness * 2)) * 2 / 100 * price);
                $.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__));?>', function(data){
                    if(typeof data.categories.frames != 'undefined')
                    {
                        $("#frame_size_error").html("");
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
                        var maxdimension = Math.max.apply(null, [frame_width, frame_height]);
                        var mindimension = Math.min.apply(null, [frame_width, frame_height]);
                        $(data.categories.frames.sizes[0].extras).each(function(index){
                            if(this[frame_extra].length > 0 && maxdimension <= Math.max.apply(null, [this[frame_extra][0].max_dimensions[0], this[frame_extra][0].max_dimensions[1]]) && mindimension <= Math.min.apply(null, [this[frame_extra][0].max_dimensions[0], this[frame_extra][0].max_dimensions[1]]))
                            {
                                if(typeof this[frame_extra][0].price != 'undefined')
                                {
                                    price += parseInt(this[frame_extra][0].price);
                                    return false;
                                }
                            }
                            else if(this[frame_extra].length > 0){
                                price = 0;
                                var self = this;
                                $.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__));?>', function(data){
                                    $("#frame_size_error").html(data['size_can_not_be_more_than'] + " " + self[frame_extra][0].max_dimensions[0]+"x"+self[frame_extra][0].max_dimensions[1]);
                                });
                                return false;
                            }
                        });
                        $("#price").html(Math.round(price)*quantity);
                        $("#input_price").val(Math.round(price)*quantity);
                        $("#card_amount").val(Math.round(price)*quantity);
                        $("#add_to_cart").css("display", "block");
                    }

                });
            }
        }
        else{
            $("#price").html("");
            $("#input_price").html("");
            $("#card_amount").val("");
            $("#add_to_cart").css("display", "none");
        }
        }
    }
    $(document).ready(function(){
        var globalCartCount = $(".cart-contents-count").text();
        $("#card_form form").on("submit", function(event){
            var formdata = $(this).serializeArray();
            $.ajax({
                url: '<?php echo plugins_url('cardcom.php', dirname(__FILE__));?>',
                type: 'POST',
                data: formdata,
                success: function(result){
                    var result = JSON.parse(result);
                    if(result.success){
                        $(".payment_message").addClass("success");
                        $(".payment_message").html(result.message);
                    }
                    else{
                        $(".payment_message").addClass("error");
                        $(".payment_message").html(result.message);
                    }
                }
            })
        })

        $(".frame_size").on("blur keyup", function(){
            var id = $(this).attr("id");
            var splittedVal = $("#"+id).val().split(".");
            if(splittedVal.length > 1){
                $("#"+id).val(splittedVal[0]);
            }
            calc();
        });
        $("#frame_source").on("change", function(){
            var frame_source = $(this).val();
            $("#frame_size").val("");
            if(frame_source == "from_catalogue")
            {
                $("#tab_grid").removeClass("active");
                $("#tab_cat").addClass("active");
                $("#frame_selected").val("");
                $("#price").html("");
                $("#input_price").html("");
                $("#card_amount").val("");
                $("#add_to_cart").css("display", "none");
                $(".frame_grid, .field_extra, .field_cover, .field_color, .frame_error, .field_width, .field_height, .field_extra_thickness, .hide_on_cat").css("display", "none");
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
                $("#tab_cat").removeClass("active");
                $("#tab_grid").addClass("active");
                $(".frame_grid, .field_extra, .field_cover, .field_color, .frame_error, .field_width, .field_height, .hide_on_cat").css("display", "block");
                $(".field_size_dropdown").css("display", "none");
            }
            calc();
        });

        $("#frame_cover").on("change", function(){
            calc();
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
        $("#add_to_cart").on("click", function(){
            var formdata = $("#frame_form").serializeArray();
            if($("#frame_source").val() != "from_catalogue")
            {
                var frame_extra_thickness = parseInt((typeof $("#frame_extra_thickness.field_extra_thickness_enabled").val() != "undefined") ? $("#frame_extra_thickness.field_extra_thickness_enabled").val() : 0);
                formdata.push({name: 'field_extra_thickness_enabled', value: frame_extra_thickness});
            }
            else{
                var frame_size = $("#frame_size option:selected").html();
                formdata.push({name: 'frame_size', value: frame_size});
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
        /*
        $("#add_to_cart").on("click", function(){
            $.ajax({
                url: "<?php echo plugins_url('add_to_woocommerce.php', dirname(__FILE__)."/frames");?>",
                data: {input_price:$("#input_price").val()},
                type: 'POST',
                success: function(result){
                    $.ajax({
                        url: '?wc-ajax=add_to_cart',
                        data: {product_sku: '', product_id: result, quantity: 1},
                        type: 'POST',
                        success: function(result){
                            console.log(result);
                        }
                    });
                }
            });
        });*/
    });

    </script>
    <style>
    .field_size_dropdown, .field_extra_thickness
    {
        display:none;
    }
    .frame_error{
        display: block;
        color: red;
    }
    </style>

</div>
