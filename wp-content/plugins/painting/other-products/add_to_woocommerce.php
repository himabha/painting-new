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

function addProductToCart($woocommerce, $other_products_data, $isEdit)
{
    $itemEdited = false;
    $count = 0;
    $quantity = $other_products_data['frame_quantity'];
    if($isEdit){
        foreach ($woocommerce->cart->get_cart() as $cart_item_key => $values) {
            $count += $values['quantity'];
            if ($other_products_data['product_id'] == $values['product_id']) {
                if ($other_products_data['input_price'] != $values['custom_price']) {
                    $woocommerce->cart->remove_cart_item($cart_item_key);
                    $count -= $values['quantity'];
                    $itemEdited = true;
                }
                break;
            }
        }
    }
    $custom_price = (int)$other_products_data['input_price'];
    $cart_item_data = array('custom_price' => $custom_price, 'product_info' => "<ul>"
        .((isset($other_products_data['other_products_type']) && !empty($other_products_data['other_products_type'])) ? "<li>Type: ".$other_products_data['other_products_type']."</li>" : "")

        .((isset($other_products_data['other_products_thickness_enabled']) && !empty($other_products_data['other_products_thickness_enabled'])) ? "<li>Thickness: ".$other_products_data['other_products_thickness_enabled']."mm</li>" : "")

        .((isset($other_products_data['other_products_size_enabled']) && !empty($other_products_data['other_products_size_enabled'])) ? "<li>Size: ".$other_products_data['other_products_size_enabled']."</li>" : "")

        .((isset($other_products_data['other_products_thickness_range_enabled']) && !empty($other_products_data['other_products_thickness_range_enabled'])) ? "<li>Thickness Range: ".$other_products_data['other_products_thickness_range_enabledother_products_colors']."mm</li>" : "")

        .((isset($other_products_data['other_products_width']) && !empty($other_products_data['other_products_width'])) ? "<li>Width: ".$other_products_data['other_products_width']."</li>" : "")

        .((isset($other_products_data['other_products_height']) && !empty($other_products_data['other_products_height'])) ? "<li>Height: ".$other_products_data['other_products_height']."</li>" : "")

        .((isset($other_products_data['other_products_colors']) && !empty($other_products_data['other_products_colors'])) ? "<li>Color: ".$other_products_data['other_products_colors']."</li>" : "")

        .((isset($other_products_data['glue_enabled']) && !empty($other_products_data['glue_enabled'])) ? "<li>With Adhesive: Yes</li>" : "")
        ."</ul>");
    // woocommerce function to add product into cart check its documentation also
    // what we need here is only $product_id & $cart_item_data other can be default.
    $woocommerce->cart->add_to_cart($other_products_data['product_id'], 1, 0, array(), $cart_item_data);
    // Calculate totals
    $woocommerce->cart->calculate_totals();
    // Save cart to session
    $woocommerce->cart->set_session();
    // Maybe set cart cookies
    $woocommerce->cart->maybe_set_cart_cookies();
    $product = wc_get_product($other_products_data['product_id']);
    echo json_encode(array("success"=> "true", "status" => $itemEdited, "product_id"=>$other_products_data['product_id'], "name"=>$product->get_name(), 'count'=> count($woocommerce->cart->cart_contents)));
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
