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
  
    <style>
      .image-carousel {
        width: 100%;
        height: 50vh;
        overflow: hidden;
        position: relative;
      }
      .image-carousel .inner {
        display: flex;
        position: absolute;
        left: 0;
        transition: left 0.5s;
        width: 100%;
        height: 100%;
      }
      
      .inner img {
        min-width: 100%;
      }
      
      .image-carousel .bubbles {
        display: flex;
        justify-content: center;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        margin-bottom: 5px;
      }
      .image-carousel .bubbles .bubble {
        margin: 0 1rem 0.5rem;
        background: white;
        border-radius: 100%;
        width: 10px;
        height: 10px;
        display: inline-block;
        opacity: 0.25;
        transition: 0.1s;
        cursor: pointer;
      }
      .image-carousel .bubbles .bubble:hover {
        opacity: 0.65;
      }
      .image-carousel .bubbles .bubble.active {
        opacity: 1;
      }
      .image-carousel .next::after, .image-carousel .previous::after {
        content: '>';
        position: absolute;
        top: 50%;
        right: 0;
        background: white;
        width: 1rem;
        height: 3rem;
        font-weight: bold;
        transform: translatey(-50%);
        line-height: 3rem;
        box-sizing: border-box;
        padding: 0 0.2rem;
        cursor: pointer;
      }
      .image-carousel .previous::after {
        left: 0;
        content: '<';
      }
    </style>
  
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
    
    <script>
      (function bb_carousel() {
        const carousels = document.querySelectorAll('.image-carousel');

        // forEach
        [].forEach.call(carousels, c => {
          let next = document.querySelector('.next');
          let prev = document.querySelector('.previous');
          let bubblesContainer = document.querySelector('.bubbles');
          let inner = document.querySelector('.inner');
          let imgs = document.querySelectorAll('.inner img');
          let currentImageIndex = 0;
          let width = 100;
          let bubbles = [];
          let interval = start();
          
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
          } // endfor
          
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
              
              switchImg();
            }, 3000);
          }
          
          // inner mouseenter
          inner.addEventListener('mouseenter', () => {
            clearInterval(interval);
          });
          
          // inner mouseleave
          inner.addEventListener('mouseleave', () => {
            interval = start();
          });
          
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
          });
          
          switchImg();
        });
      }());
    </script>
    
  <?php
  endif;
}

// Plugin shortcode
add_shortcode('bb_carousel', 'shortcode_func');
