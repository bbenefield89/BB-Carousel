<?php

/*

  Plugin Name: BB Carousel
  Plugin URI: https://www.bbenefield.com
  Description: Easy to use image slide show plugin
  Version: 1.0.0
  Author: Brandon Benefield
  Author URI: https://www.bbenefield.com
  License: GPLv2 or later
  Text Domain: BB-Carousel

*/
  
// Security check
if (!defined('ABSPATH')) {
  exit();
}
  
/************************
** NAMESPACE & REQUIRE **
************************/
/**************
** USE CLASS **
**************/
use Inc\Admin\admin as Admin;
use Inc\Database\SliderSettings as SliderSettings;
use Inc\Database\SliderImages as SliderImages;

/******************
** USE FUNCTIONS **
******************/
// use function Inc\Functions\activation_methods;

/*************
** REQUIRES **
*************/
require_once 'inc/admin/admin.php';
require_once 'inc/database/database.php';
require_once 'inc/database/slider-settings.php';
require_once 'inc/database/slider-images.php';
// require_once 'inc/functions/activation-methods.php';

/**********
** BEGIN **
**********/
// Grab activation methods
function activation_methods() {
  // object and params for `create_db`
  $slider_settings = new SliderSettings;
  $slider_settings_fields = [
    'id'                => 'int(9)',
    'transition_time'   => 'int(9)',
    'loop_carousel'     => 'tinytext',
    'stop_on_hover'     => 'tinytext',
    'reverse_order'     => 'tinytext',
    'navigation_arrows' => 'tinytext',
    'show_pagination'   => 'tinytext'
  ];
  
  // object and params for `create_db`
  $slider_images = new SliderImages;
  $slider_images_fields = [
    'image_id'    => 'int(9)',
    'carousel_id' => 'int(9)',
    'image_url'   => 'text'
  ];
  
  $slider_settings->create_db('bb_slidersettings', $slider_settings_fields);
  $slider_images->create_db('bb_sliderimages', $slider_images_fields);
}



// Plugin activation
register_activation_hook(__FILE__, 'activation_methods');

// Plugin deactivation
register_deactivation_hook(__FILE__, 'Inc\Database\Database::delete_db_table');

// Create WP admin dashboard sidebar section for BB-Carousel
add_action('admin_menu', 'Inc\Admin\Admin::admin_menu');

// Load plugin assets
add_action('admin_enqueue_scripts', 'Inc\Admin\Admin::load_assets');

function shortcode_func() {
  global $wpdb;
  $table_name = $wpdb->prefix.'bb_sliderimages';
  $sql = "SELECT
            image_id,
            carousel_id,
            image_url
          FROM
            $table_name;";
            
  $results = $wpdb->get_results($sql);
  
  if (count($results) > 0) :
  ?>
  
    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/bb-carousel.css', __FILE__); ?>">
  
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="image-carousel">
            <div class="inner">
  
  <?php
    foreach ($results as $key => $val) :
    ?>
    
      <img class="carousel one" src="<?php echo $val->image_url; ?>">
      
    <?php
    endforeach;
    ?>
    
            </div>
            <div class="bubbles"></div>
            <div class="previous"></div>
            <div class="next"></div>
          </div>
        </div>
      </div>
    </div>
    
    <script src="<?php echo plugins_url('assets/js/bb-carousel.js', __FILE__); ?>"></script>
    
  <?php
  endif;
}

// Plugin shortcode
add_shortcode('bb_carousel', 'shortcode_func');
