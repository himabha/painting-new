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

function addProductToCart($woocommerce, $print_data, $isEdit)
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
    $custom_price = (int)$print_data['input_price'];
    $cart_item_data = array('custom_price' => $custom_price, 'product_info' => "<ul>"
        .((isset($print_data['print_type']) && !empty($print_data['print_type'])) ? "<li>Type: ".$print_data['print_type']."</li>" : "")

        .((isset($print_data['print_width']) && !empty($print_data['print_width'])) ? "<li>Width: ".$print_data['print_width']."</li>" : "")

        .((isset($print_data['print_height']) && !empty($print_data['print_height'])) ? "<li>Height: ".$print_data['print_height']."</li>" : "")

        .((isset($print_data['print_thickness_line']) && !empty($print_data['print_thickness_line'])) ? "<li>Print Thickness Line: ".$print_data['print_thickness_line']."mm</li>" : "")

        .((isset($print_data['print_finishing_select']) && !empty($print_data['print_finishing_select'])) ? "<li>Finishing: ".$print_data['print_finishing_select']."</li>" : "")

        .((isset($print_data['print_laminate_hanging_select']) && !empty($print_data['print_laminate_hanging_select'])) ? "<li>Laminate: ".$print_data['print_laminate_hanging_select']."</li>" : "")

        .((isset($print_data['print_hanging_select']) && !empty($print_data['print_hanging_select'])) ? "<li>Hanging type: ".$print_data['print_hanging_select']."</li>" : "")

        .((isset($print_data['image_width']) && !empty($print_data['image_width'])) ? "<li>Image Width: ".$print_data['image_width']."</li>" : "")

        .((isset($print_data['image_height']) && !empty($print_data['image_height'])) ? "<li>Image Height: ".$print_data['image_height']."</li>" : "")

        .((isset($print_data['frame_required']) && $print_data['frame_required'] == true) && (isset($print_data['frame_extra']) && !empty($print_data['frame_extra'])) ? "<li>Frame Extra: ".$print_data['frame_extra']."</li>" : "")

        .((isset($print_data['frame_required']) && $print_data['frame_required'] == true) && (isset($print_data['field_extra_thickness_enabled']) && !empty($print_data['field_extra_thickness_enabled'])) ? "<li>Frame Extra Thickness: ".$print_data['field_extra_thickness_enabled']."mm</li>" : "")

        ."</ul>", 'custom_product_thumbnail' => "<img style='width:80px;height:80px' src='".$print_data['print_image']."'/>");
    // woocommerce function to add product into cart check its documentation also
    // what we need here is only $product_id & $cart_item_data other can be default.
    $woocommerce->cart->add_to_cart($print_data['product_id'], 1, 0, array(), $cart_item_data);
    // Calculate totals
    $woocommerce->cart->calculate_totals();
    // Save cart to session
    $woocommerce->cart->set_session();
    // Maybe set cart cookies
    $woocommerce->cart->maybe_set_cart_cookies();
    $product = wc_get_product($print_data['product_id']);
    echo json_encode(array("success"=> "true", "status" => $itemEdited, "product_id"=>$print_data['product_id'], "name"=>$product->get_name(), 'count'=> count($woocommerce->cart->cart_contents)));
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
