<?php

namespace Inc\Functions;

use Inc\Database\SliderSettings as SliderSettings;
use Inc\Database\SliderImages as SliderImages;

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
