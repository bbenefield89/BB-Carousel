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
  
/************************
** NAMESPACE & REQUIRE **
************************/
use Inc\Classes\admin as Admin;

require_once 'inc/class/admin.php';

/**********
** BEGIN **
**********/  
// Security check
if (!defined('ABSPATH')) {
    exit();
}

// Create WP admin dashboard sidebar section for BB-Carousel
add_action('admin_menu', 'Inc\Classes\Admin::admin_menu');

// Load plugin assets
add_action('admin_enqueue_scripts', 'Inc\Classes\Admin::load_assets');
