<?php

namespace Inc\Classes;

final class Admin {
  public function __construct() {}
  
  public static function html() {  
?>
    
    <main>
      <h1>Edit Slider</h1>
      
      <button class="btn btn-lg">Add New</button>
      
      <input name="slider_name" type="text" value="">
      
      
    </main>
    
    <aside>  
    </aside>
    
<?php
  }
  
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
