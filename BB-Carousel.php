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
use Inc\Admin\admin as Admin;
use Inc\Database\SliderSettings as SliderSettings;
use Inc\Database\SliderImages as SliderImages;

require_once 'inc/admin/admin.php';
require_once 'inc/database/database.php';
require_once 'inc/database/slider-settings.php';
require_once 'inc/database/slider-images.php';

/**********
** BEGIN **
**********/
// Grab activation methods
function activation_methods() {
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
// add_action('admin_menu', 'Inc\Admin\Admin::ajax');

// Load plugin assets
add_action('admin_enqueue_scripts', 'Inc\Admin\Admin::load_assets');
