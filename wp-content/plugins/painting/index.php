<?php
/**
* Plugin Name: Painting Plugin
* Plugin URI: https://www.shaked-g.com/
* Description: This plugin is used for selling prints, mirrors, frames, glass and other products.
* Version: 1.0
* Author: Himanshu Bhatia
* Author URI: http://skyzoneinfotech.co.in/
**/

function painting_app_load_scripts($hook)
{

    // create my own version codes
    $jquery_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/js/jquery.js'));
    //$highchart_more_js_ver = date("ymd-Gis", filemtime(plugin_dir_path(__FILE__) . 'assets/js/highcharts/highcharts-more.js'));
    wp_enqueue_style('painting-style', plugins_url('assets/css/painting_style.css', __FILE__), $jquery_js_ver);
    wp_register_script('jQuery_js', plugins_url('assets/js/jquery.js', __FILE__), array(), $jquery_js_ver);
    wp_enqueue_script('jQuery_js');
    //wp_register_script('highchart_more_js', plugins_url('assets/js/highcharts/highcharts-more.js', __FILE__), array(), $highchart_more_js_ver);
    //wp_enqueue_script('highchart_more_js');

    //wp_enqueue_style('myfxbook_chart_css', plugins_url('assets/css/myfxbook-chart.css', __FILE__));
}

function painting_app_shortcode($attr)
{
    extract(shortcode_atts(array(
      'pagename' => "",
      'product_id' => ""
   ), $atts));
    if (is_page()) {
        //$slug = get_queried_object()->post_name;
        $slug = $attr['pagename'];
        $productID = $attr['product_id'];
        include_once(dirname(__FILE__)."/config.php");
        include_once(dirname(__FILE__)."/".$slug."/index.php");
    }
}


add_action('wp_enqueue_scripts', 'painting_app_load_scripts');
add_shortcode('painting_app', 'painting_app_shortcode');
