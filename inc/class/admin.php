<?php

namespace Inc\Classes;

final class Admin {
  public function __construct() {}
  
  // Loads assets
  public static function load_assets($hook) {
    if ($hook != 'toplevel_page_bb_carousel') {
      return;
    }
    
    // CSS
    wp_enqueue_style(
      'bb-carousel-css',
      plugins_url('../../assets/css/style.css', __FILE__)
    );
    
    // JS
    wp_enqueue_script(
      'bb-carousel-js',
      plugins_url('../../assets/js/main.js', __FILE__),
      [],
      '',
      true
    );
  }
  
  // Outputs HTML to main admin page
  public static function html() {  
    
  ?>
    
    <!-- BEGIN HTML -->
    <main id="bb-carousel-main">
      
      <!-- HEADER -->
      <header>
        <h1>Edit Slider</h1>
        
        <button class="btn btn-lg">Add New</button>
      </header><!-- header -->
      
      <form action="" method="POST">
        <input name="slider_name" type="text" value="">
        
        <!-- SLIDER SETTINGS -->
        <article class="slider-settings-container">
          <header class="slider-settings-header">
            <h2 class="tab-open">Slider Settings</h2>
          </header><!-- header -->
          
          <section class="slider-settings-content">
            <div class="transition-time-container">
              <div class="advanced-options-p">
                <p>Transition Time:</p>
              </div>
              <input name="transition_time" type="number">
            </div>
            
            <div class="advanced-options-container">
              <div class="loop-carousel-container">
                <div class="advanced-options-p">
                  <p>Loop Carousel</p>
                </div>
                <input name="loop_carousel" type="checkbox">
              </div>
            
              <div class="stop-on-hover-container">
                <div class="advanced-options-p">
                  <p>Stop on Hover</p>
                </div>
                <input name="stop_on_hover" type="checkbox">
              </div>
              
              <div class="reverse-order-container">
                <div class="advanced-options-p">
                  <p>Reverse Order</p>
                </div>
                <input name="reverse_order" type="checkbox">
              </div>
              
              <div class="navigation-arrows-container">
                <div class="advanced-options-p">
                  <p>Navigation Arrows</p>
                </div>
                <input name="navigation_arrows" type="checkbox">
              </div>
              
              <div class="show-pagination-container">
                <div class="advanced-options-p">
                  <p>Show Pagination</p>
                </div>
                <input name="show_pagination" type="checkbox">
              </div>
            </div>
          </section><!-- section -->
        </article><!-- article -->
      </form>
      
    </main><!-- main -->
    
    <aside id="bb-carousel-aside">
    </aside><!-- aside -->
    <!-- END HTML -->
    
  <?php
  
  }
  
  // Creates admin menu for plugin
  public static function admin_menu() {
    add_menu_page(
      'BB Carousel',
      'BB Carousel',
      'manage_options',
      'bb_carousel',
      'Inc\Classes\Admin::html'
    );
  }
}
