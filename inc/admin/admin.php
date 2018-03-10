<?php

namespace Inc\Admin;
use Inc\Database\database as Database;
use Inc\Database\SliderSettings as SliderSettings;

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
  } // load_assets()
  
  // grabs values from DB and fills inputs on ADMIN PAGE
  protected static function fill_default_values() {
    global $wpdb;
    $table_name = $wpdb->prefix.'bb_slidersettings';
    $result = $wpdb->get_results(
      "SELECT
        s.id,
        s.transition_time,
        s.loop_carousel,
        s.stop_on_hover,
        s.reverse_order,
        s.navigation_arrows,
        s.show_pagination,
        i.image_id,
        i.carousel_id,
        i.image_url
      FROM
        wp_bb_slidersettings s
      JOIN
        wp_bb_sliderimages i on s.id = i.carousel_id"
    );
    
    if (!$result) {
      $result = $wpdb->get_results(
          "SELECT
             id,
             transition_time,
             loop_carousel,
             stop_on_hover,
             reverse_order,
             navigation_arrows,
             show_pagination
           FROM
             $table_name
           ORDER BY id ASC
           LIMIT 1;"
        );
    } // endif
    
    return $result;
  }// fill_default_values()
  
  protected static function loop_images() {
    global $wpdb;
    $table_name = $wpdb->prefix.'bb_sliderimages';
    $sql = "SELECT
              id,
              carousel_id,
              image_url
            FROM
              $table_name;";
  }
  
  // Outputs HTML to main admin page
  public static function html() {
    $result = self::fill_default_values();
    
    var_dump($result);
    
    // Check if `update_slider` Button has been Clicked
    if (isset($_POST['update_slider'])) {
      $transition_time   = sanitize_text_field($_POST['transition_time']);
      $loop_carousel     = isset($_POST['loop_carousel'])
                           ?
                             sanitize_text_field($_POST['loop_carousel'])
                           :
                             $_POST['loop_carousel'] = '';
      $stop_on_hover     = isset($_POST['stop_on_hover'])
                           ?
                             sanitize_text_field($_POST['stop_on_hover'])
                           :
                             $_POST['stop_on_hover'] = '';
      $reverse_order     = isset($_POST['reverse_order'])
                           ?
                             sanitize_text_field($_POST['reverse_order'])
                           :
                             $_POST['reverse_order'] = '';
      $navigation_arrows = isset($_POST['navigation_arrows'])
                           ?
                             sanitize_text_field($_POST['navigation_arrows'])
                           :
                             $_POST['navigation_arrows'] = '';
      $show_pagination   = isset($_POST['show_pagination'])
                           ?
                             sanitize_text_field($_POST['show_pagination'])
                           :
                             $_POST['show_pagination'] = '';
      
      $slider_settings = [
          'transition_time'   => $transition_time,
          'loop_carousel'     => $loop_carousel,
          'stop_on_hover'     => $stop_on_hover,
          'reverse_order'     => $reverse_order,
          'navigation_arrows' => $navigation_arrows,
          'show_pagination'   => $show_pagination,
        ];
      
      $slider_settings_obj = new SliderSettings;
      
      // Run Database Query
      $slider_settings_obj->update_db($slider_settings);
    }
    
    if (isset($_POST['image_input_hidden'])) {
      global $wpdb;
      $table_name  = $wpdb->prefix.'bb_sliderimages';
      $carousel_id = $_POST['carousel_id'];
      $image_url   = $_POST['image_input_hidden'];
      $sql         = "INSERT INTO $table_name (
                        id, carousel_id, image_url)
                      VALUES (
                        NULL, $carousel_id, '$image_url');";
              
      $wpdb->query($sql);
    }
        
    ?>
    
    <!-- BEGIN HTML -->
    <form id="bb-carousel-form" action="" method="POST">
      <input name="carousel_id" type="hidden" value="<?php echo $result[0]->id; ?>">
      
      <main id="bb-carousel-main">
        
        <!-- HEADER -->
        <header>
          <h1>Edit Slider</h1>
        </header>
        
        <!-- SLIDER SETTINGS -->
        <article class="slider-settings-container">
          <header class="slider-settings-header">
            <h2 class="tab-open">Slider Settings</h2>
          </header>
          
          <!-- SLIDER SETTINGS CONTENT -->
          <section class="slider-settings-content">
            <!-- TRANSITION TIME -->
            <div class="transition-time-container">
              <div class="advanced-options-p">
                <p>Transition Time:</p>
              </div>
              <input name="transition_time" min="1" max="10" pattern="\w" type="number" value="<?php echo $result[0]->transition_time; ?>">
            </div>
            
            <hr>
            
            <!-- LOOP CAROUSEL OPTION -->
            <div class="advanced-options-container">
              <div class="loop-carousel-container">
                <div class="advanced-options-p">
                  <p>Loop Carousel</p>
                </div>
                <input <?php if ($result[0]->loop_carousel) { echo 'checked="checked"'; } ?> name="loop_carousel" type="checkbox">
              </div>
            
              <!-- STOP ON HOVER OPTION -->
              <div class="stop-on-hover-container">
                <div class="advanced-options-p">
                  <p>Stop on Hover</p>
                </div>
                <input name="stop_on_hover" type="checkbox">
              </div>
              
              <!-- REVERSE ORDER OPTION -->
              <div class="reverse-order-container">
                <div class="advanced-options-p">
                  <p>Reverse Order</p>
                </div>
                <input name="reverse_order" type="checkbox">
              </div>
              
              <!-- NAVIGATION ARROWS OPTION -->
              <div class="navigation-arrows-container">
                <div class="advanced-options-p">
                  <p>Navigation Arrows</p>
                </div>
                <input name="navigation_arrows" type="checkbox">
              </div>
              
              <!-- SHOW PAGINATION OPTION -->
              <div class="show-pagination-container">
                <div class="advanced-options-p">
                  <p>Show Pagination</p>
                </div>
                <input name="show_pagination" type="checkbox">
              </div>
            </div><!-- advanced-options-container -->
          </section><!-- slider-settings-content -->
        </article><!-- slider-settings-container -->
        
        <article class="slider-images-container">
          <header class="slider-images-header">
            <h2>Slider Images</h2>
          </header>
          <section class="slider-images-content">
            <button class="add-new-image button button-secondary" name="add_new_image" type="submit">Add New Image</button>
            <input class="image-url" type="text" placeholder="Image URL">
            <small class="text-red" id="new-image-error" hidden>Please enter a valid image url ending with: .jpg, .jpeg, .png, or .gif</small>
            <hr>
            <div class="slider-images">
              <!-- IMAGES DYNAMICALLY FILLED WITH JS -->
              
            <?php
              if (isset($result[0]->image_url)) :
                foreach ($result as $key) :    
            ?>
                  <div class="slider-image">
                    <img src="<?php echo $key->image_url; ?>">
                    <span class="remove-image">×</span>
                    <input name="image_input_hidden" type="hidden" value="<?php echo $key->id; ?>">
                    <input name="image_id" type="hidden" value="<?php echo $key->image_id; ?>">
                  </div>    
            <?php
                endforeach;
              endif;
            ?>
                            
            </div>
          </section><!-- slider-images-content -->
        </article><!-- slider-images-container -->
      </main><!-- main -->
      
      <!-- ASIDE -->
      <aside id="bb-carousel-aside">
        <button class="button button-primary button-large" name="update_slider" type="submit">Update</button>
      </aside>
    </form><!-- form -->
    <!-- END HTML -->
    
  <?php
  
  } // html()
  
  public static function ajax_page() {
    global $wpdb;
    $table_name = $wpdb->prefix.'bb_sliderimages';
    
    if (isset($_POST['image_input_hidden'])) {
      $image_url  = $_POST['image_input_hidden'];
      $sql        = "INSERT INTO $table_name (
                       image_id, carousel_id, image_url)
                     VALUES (
                       NULL, 1, '$image_url');";
              
      $wpdb->query($sql);
    }
    
    if (isset($_POST['image_id'])) {
      $image_id = $_POST['image_id'];
      $sql = "DELETE
              FROM
                $table_name
              WHERE
                image_id = $image_id;";
                
      $wpdb->query($sql);
    }
  }
  
  // Creates admin menu for plugin
  public static function admin_menu() {
    add_menu_page(
      'BB Carousel',
      'BB Carousel',
      'manage_options',
      'bb_carousel',
      'Inc\Admin\Admin::html'
    );
    
    add_submenu_page(
      'bb_carousel',
      'BB AJAX',
      '',
      'manage_options',
      'bb_ajax',
      'Inc\Admin\Admin::ajax_page'
    );
  } // admin_menu()
}
