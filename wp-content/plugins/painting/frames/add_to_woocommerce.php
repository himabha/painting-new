<?php
require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/wp-config.php');
global $woocommerce;

if (empty($woocommerce->cart->cart_contents)) {
    // Cart item data to send & save in order
    addProductToCart($woocommerce, $_POST, false);
} else {
    // Cart item data to send & save in order
    addProductToCart($woocommerce, $_POST, true);
}

function addProductToCart($woocommerce, $frame_data, $isEdit)
{
    $itemEdited = false;
    $count = 0;
    $quantity = $frame_data['frame_quantity'];
    if($isEdit){
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
            $count += $values['quantity'];
            if ($frame_data['product_id'] == $values['product_id']) {
                if ($frame_data['input_price'] != $values['custom_price']) {
                    $woocommerce->cart->remove_cart_item($cart_item_key);
                    $count -= $values['quantity'];
                    $itemEdited = true;
                }
                break;
            }
        }
    }
    $custom_price = (int)$frame_data['input_price'];
    $cart_item_data = array('custom_price' => $custom_price, 'product_info' => ((isset($frame_data['frame_source']) && !empty($frame_data['frame_source']) && $frame_data['frame_source'] == 'from_catalogue') ? "<ul>"
        .((isset($frame_data['frame_source']) && !empty($frame_data['frame_source'])) ? "<li>Frame Source: ".$frame_data['frame_source']."</li>" : "")

        .((isset($frame_data['frame_size']) && !empty($frame_data['frame_size'])) ? "<li>Frame Size: ".$frame_data['frame_size']."</li>" : "")

        ."</ul>" :  "<ul>"
        .((isset($frame_data['frame_width']) && !empty($frame_data['frame_width'])) ? "<li>Frame Width: ".$frame_data['frame_width']."</li>" : "")

        .((isset($frame_data['frame_height']) && !empty($frame_data['frame_height'])) ? "<li>Frame Height: ".$frame_data['frame_height']."</li>" : "")

        .((isset($frame_data['frame_cover']) && !empty($frame_data['frame_cover'])) ? "<li>Frame Cover: ".$frame_data['frame_cover']."</li>" : "")

        .((isset($frame_data['frame_extra']) && !empty($frame_data['frame_extra'])) ? "<li>Frame Extra: ".$frame_data['frame_extra']."</li>" : "")

        .((isset($frame_data['field_extra_thickness_enabled']) && !empty($frame_data['field_extra_thickness_enabled'])) ? "<li>Frame Extra Thickness: ".$frame_data['field_extra_thickness_enabled']."mm</li>" : "")

        .((isset($frame_data['frame_colors']) && !empty($frame_data['frame_colors']) && $frame_data['frame_colors'] != 'Choose one') ? "<li>Frame Color: ".$frame_data['frame_colors']."</li>" : "")
        ."</ul>"));
    if ((isset($frame_data['frame_source']) && !empty($frame_data['frame_source']) && $frame_data['frame_source'] != 'from_catalogue') && isset($frame_data['frame_selected']) && !empty($frame_data['frame_selected'])) {
        $selectedFrameData = json_decode(stripslashes($frame_data['frame_selected']));
        $cart_item_data['custom_product_thumbnail'] = "<img style='width:80px;height:80px' src='".plugins_url($selectedFrameData->img_path, dirname(__FILE__))."'/>";
    }
    // woocommerce function to add product into cart check its documentation also
    // what we need here is only $product_id & $cart_item_data other can be default.
    $woocommerce->cart->add_to_cart($frame_data['product_id'], $quantity, 0, array(), $cart_item_data);
    $count += $quantity;
    // Calculate totals
    $woocommerce->cart->calculate_totals();
    // Save cart to session
    $woocommerce->cart->set_session();
    // Maybe set cart cookies
    $woocommerce->cart->maybe_set_cart_cookies();
    $product = wc_get_product($frame_data['product_id']);
    echo json_encode(array("success"=> "true", "status" => $itemEdited, "product_id"=>$frame_data['product_id'], "name"=>$product->get_name(), 'count'=> $count));
}
/*
This is also important code where you can create your product on the fly and add to cart use wc-cart.php ajax calls
submit product id

if (empty($woocommerce->cart->cart_contents)) {
    $user_id = get_current_user();
    $productID = wp_insert_post(array(
        'post_author' => $user_id,
        'post_title' => 'Frames',
        'post_content' => 'Here is content of the post, so this is our great new products description',
        'post_status' => 'publish',
        'post_type' => "product",
    ));
    wp_set_object_terms($productID, 'simple', 'product_type');
    update_post_meta($productID, '_visibility', 'visible');
    update_post_meta($productID, '_stock_status', 'instock');
    update_post_meta($productID, 'total_sales', '0');
    update_post_meta($productID, '_downloadable', 'no');
    update_post_meta($productID, '_virtual', 'yes');
    update_post_meta($productID, '_regular_price', (int)$_POST['input_price']);
    update_post_meta($productID, '_sale_price', (int)$_POST['input_price']);
    update_post_meta($productID, '_purchase_note', '');
    update_post_meta($productID, '_featured', 'no');
    update_post_meta($productID, '_weight', '');
    update_post_meta($productID, '_length', '');
    update_post_meta($productID, '_width', '');
    update_post_meta($productID, '_height', '');
    update_post_meta($productID, '_sku', '');
    update_post_meta($productID, '_product_attributes', array());
    update_post_meta($productID, '_sale_price_dates_from', '');
    update_post_meta($productID, '_sale_price_dates_to', '');
    update_post_meta($productID, '_price', (int)$_POST['input_price']);
    update_post_meta($productID, '_sold_individually', '');
    update_post_meta($productID, '_manage_stock', 'no');
    update_post_meta($productID, '_backorders', 'no');
    update_post_meta($productID, '_stock', 1);
    echo $productID;
}
else{

}*/
