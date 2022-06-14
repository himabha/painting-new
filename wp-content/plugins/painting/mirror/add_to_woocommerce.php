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

function addProductToCart($woocommerce, $mirror_data, $isEdit)
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
    $custom_price = (int)$mirror_data['input_price'];
    $cart_item_data = array('custom_price' => $custom_price, 'product_info' => (isset($mirror_data['mirror_type']) && !empty($mirror_data['mirror_type']) && $mirror_data['mirror_type'] != 'from_catalogue') ? "<ul>"
        .((isset($mirror_data['mirror_type']) && !empty($mirror_data['mirror_type'])) ? "<li>Type: ".$mirror_data['mirror_type']."</li>" : "")

        .((isset($mirror_data['mirror_thickness_enabled']) && !empty($mirror_data['mirror_thickness_enabled'])) ? "<li>Thickness: ".$mirror_data['mirror_thickness_enabled']."mm</li>" : "")

        .((isset($mirror_data['mirror_width']) && !empty($mirror_data['mirror_width'])) ? "<li>Mirror Width: ".$mirror_data['mirror_width']."</li>" : "")

        .((isset($mirror_data['mirror_height']) && !empty($mirror_data['mirror_height'])) ? "<li>Mirror Height: ".$mirror_data['mirror_height']."</li>" : "")

        .((isset($mirror_data['mirror_finishing_enabled']) && !empty($mirror_data['mirror_finishing_enabled'])) ? "<li>Finishing: ".$mirror_data['mirror_finishing_enabled']."</li>" : "")

        .((isset($mirror_data['mirror_hanging_type']) && !empty($mirror_data['mirror_hanging_type'])) ? "<li>Hanging Type: ".$mirror_data['mirror_hanging_type']."</li>" : "")

        ."</ul>" : "<ul>"
            .((isset($mirror_data['mirror_type']) && !empty($mirror_data['mirror_type'])) ? "<li>Type: ".$mirror_data['mirror_type']."</li>" : "")

            .((isset($mirror_data['customizable']) && !empty($mirror_data['customizable']) && $mirror_data['customizable'] == true) ? "<li>Customizable: Yes</li>" : "<li>Customizable: No</li>")

            .((isset($mirror_data['customizable']) && !empty($mirror_data['customizable']) && $mirror_data['customizable'] == false) ? "<li>Mirror Size: ".$mirror_data['mirror_size']."</li>" : "")

            .((isset($mirror_data['customizable']) && !empty($mirror_data['customizable']) && $mirror_data['customizable'] == true) ? "<li>Mirror Width: ".$mirror_data['mirror_width']."</li>" : "")

            .((isset($mirror_data['customizable']) && !empty($mirror_data['customizable']) && $mirror_data['customizable'] == true) ? "<li>Mirror Height: ".$mirror_data['mirror_height']."</li>" : "")

            .((isset($mirror_data['for_institutions']) && !empty($mirror_data['for_institutions']) && $mirror_data['for_institutions'] == true) ? "<li>For institutions: Yes</li>" : "<li>For institutions: No</li>")

            .((isset($mirror_data['mirror_size']) && !empty($mirror_data['mirror_size'])) ? "<li>Size: ".$mirror_data['mirror_size']."</li>" : "")

            .((isset($mirror_data['mirror_thickness']) && !empty($mirror_data['mirror_thickness'])) ? "<li>Thickness: ".$mirror_data['mirror_thickness']."mm</li>" : "")

        ."</ul>");
    // woocommerce function to add product into cart check its documentation also
    // what we need here is only $product_id & $cart_item_data other can be default.
    $woocommerce->cart->add_to_cart($mirror_data['product_id'], 1, 0, array(), $cart_item_data);
    // Calculate totals
    $woocommerce->cart->calculate_totals();
    // Save cart to session
    $woocommerce->cart->set_session();
    // Maybe set cart cookies
    $woocommerce->cart->maybe_set_cart_cookies();
    $product = wc_get_product($mirror_data['product_id']);
    echo json_encode(array("success"=> "true", "status" => $itemEdited, "product_id"=>$mirror_data['product_id'], "name"=>$product->get_name(), 'count'=> count($woocommerce->cart->cart_contents)));
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
