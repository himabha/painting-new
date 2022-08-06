<?php
function painting_child_theme_enqueue_styles()
{
    $parent_style = 'parent-style';
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'painting_child_theme_enqueue_styles');
// CUSTOM
add_action('before_main_container', function () {
    if (is_front_page()) {
        if (isset(get_option('theme_mods_painting')['painting_gallery_shortcode']) && !empty(get_option('theme_mods_painting')['painting_gallery_shortcode'])) {
            echo do_shortcode(get_option('theme_mods_painting')['painting_gallery_shortcode']);
        }
    }
}, 10);

add_filter('wp_nav_menu_items', 'painting_custom_menu_item', 10, 2);
function painting_custom_menu_item($items, $args)
{
    if ($args->theme_location == 'main-menu') {
        $items = str_replace('<a title="facebook" href="#" class="nav-link">facebook</a>', '<a class="nav-link active" aria-current="page" href="#"><i class="fab fa-facebook-f"></i></a>', $items);
        $items = str_replace('<a title="instagram" href="#" class="nav-link">instagram</a>', '<a class="nav-link" href="#"><i class="fab fa-instagram"></i></a>', $items);
        $items = str_replace('<a title="heart" href="#" class="nav-link">heart</a>', '<a class="nav-link" href="#" ><i class="far fa-heart"></i></a>', $items);
        $items = str_replace('<a title="cart" href="#" class="nav-link">cart</a>', '<a class="nav-link" href="../cart" ><i class="fas fa-shopping-cart"></i></a>', $items);
    }
    return $items;
}
