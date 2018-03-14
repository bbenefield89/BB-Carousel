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
    'stop_on_hover'     => 'tinytext',
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
            ss.id,
            ss.transition_time,
            ss.stop_on_hover,
            ss.navigation_arrows,
            ss.show_pagination,
            si.image_id,
            si.carousel_id,
            si.image_url
          FROM
            wp_bb_slidersettings ss
          JOIN
            wp_bb_sliderimages si
          ON
            ss.id = si.carousel_id;";
            
  $results = $wpdb->get_results($sql);
  
  if (count($results) > 0) :
    // var_dump($results);
  ?>
  
    <link rel="stylesheet" href="<?php echo plugins_url('assets/css/bb-carousel.css', __FILE__); ?>">
  
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="image-carousel">
            <div class="inner">
  
  <?php foreach ($results as $key => $val) : ?>
    <img class="carousel one" src="<?php echo $val->image_url; ?>">
  <?php endforeach; ?>
    
            </div>
            
            <?php if ($results[0]->show_pagination) : ?>
              <div class="bubbles"></div>
            <?php endif; ?>
            
            <?php if ($results[0]->navigation_arrows) : ?>
              <div class="previous"></div>
              <div class="next"></div>
            <?php endif; ?>
            
          </div>
        </div>
      </div>
    </div>
    
    <?php
    
      $transition_time = $results[0]->transition_time;
      
      $bb_carousel_js = "<script>\n";
      $bb_carousel_js .= "
        (function bb_carousel() {
          const carousels = document.querySelectorAll('.image-carousel');
          
          // forEach
          [].forEach.call(carousels, c => {
            console.log('asdad')
            let next = document.querySelector('.next');
            let prev = document.querySelector('.previous');
            let bubblesContainer = document.querySelector('.bubbles');
            let inner = document.querySelector('.inner');
            let imgs = document.querySelectorAll('.inner img');
            let currentImageIndex = 0;
            let width = 100;
            let bubbles = [];
            let interval = start();\n";
            
      if ($results[0]->show_pagination) {
        $bb_carousel_js .= "
          // for
          for (let i = 0; i < imgs.length; i++) {
            let b = document.createElement('span');
            b.classList.add('bubble');
            bubblesContainer.append(b);
            bubbles.push(b);
            
            b.addEventListener('click', () => {
              currentImageIndex = i;
              switchImg();
            });
          } // endfor\n";
      }
      
      $bb_carousel_js .= "
        // switchImg()
        function switchImg() {
          inner.style.left = -width * currentImageIndex + '%';
          
          bubbles.forEach(function (b, i) {
            if (i === currentImageIndex) {
              b.classList.add('active');
            } else {
                b.classList.remove('active');
            }
          });
        } // switchImg()
        
        // start()
        function start() {
          return setInterval(() => {
            currentImageIndex++;
            
            if (currentImageIndex >= imgs.length) {
              currentImageIndex = 0;
            }
            
            switchImg();\n";
        
        $bb_carousel_js .= "
            }, ".$transition_time."000);
          }\n";
        
      if ($results[0]->stop_on_hover) {
        $bb_carousel_js .= "
          // inner mouseenter
          inner.addEventListener('mouseenter', () => {
            clearInterval(interval);
          });
          
          // inner mouseleave
          inner.addEventListener('mouseleave', () => {
            interval = start();
          });\n";
      }
      
      if ($results[0]->navigation_arrows) {
        $bb_carousel_js .= "
          // `next` button click
          next.addEventListener('click', () => {
            currentImageIndex++;
            
            if (currentImageIndex >= imgs.length) {
              currentImageIndex = 0;
            }
            
            switchImg();
          });
          
          // `prev` button click
          prev.addEventListener('click', () => {
            currentImageIndex--;
            
            if (currentImageIndex < 0) {
              currentImageIndex = imgs.length - 1;
            }
            
            switchImg();
          });\n";
      }
      
      $bb_carousel_js .= "
            switchImg();
          });
        }());\n";
      
      $bb_carousel_js .= "</script>";
      
      echo $bb_carousel_js;
    
  endif;
}

// Plugin shortcode
add_shortcode('bb_carousel', 'shortcode_func');
