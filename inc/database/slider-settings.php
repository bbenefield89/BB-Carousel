<?php

namespace Inc\Database;

class SliderSettings extends Database {
  public function __construct() {}
  
  // creates initial DB table
  public function create_db(string $table, array $fields) {
    $this->create_db_table($table, $fields);
    $this->initial_insert();
  }
  
  // Create barebones row in DB for initial setup
  protected function initial_insert() {
    global $wpdb;
    $table_name = $wpdb->prefix.'bb_slidersettings';
    $sql        = "SELECT
                     id
                   FROM
                     $table_name;";
    $result     = $wpdb->get_results($sql);
    
    if (count($result) === 0) {
      $sql = "INSERT INTO $table_name(
                id)
              VALUES (
                NULL);";
              
      $wpdb->query($sql);
    }
  }
    
  // UPDATE or INSERT into DB
  public function update_db(...$fields) {
    global $wpdb;
    $table_name        = $wpdb->prefix.'bb_slidersettings';
    $table_id          = ($fields[0]['carousel_id']) ? sanitize_text_field($fields[0]['carousel_id']) : 0;
    $transition_time   = sanitize_text_field($fields[0]['transition_time']);
    $stop_on_hover     = sanitize_text_field($fields[0]['stop_on_hover']);
    $navigation_arrows = sanitize_text_field($fields[0]['navigation_arrows']);
    $show_pagination   = sanitize_text_field($fields[0]['show_pagination']);
    $sql               = "SELECT
                            id
                          FROM
                            $table_name
                          WHERE
                            id = $table_id;";
    $result            = $wpdb->get_results($sql);
    
    if (!$result) { 
      $wpdb->insert($table_name, [
        'id' => NULL,
        'transition_time'   => $transition_time,
        'stop_on_hover'     => $stop_on_hover,
        'navigation_arrows' => $navigation_arrows,
        'show_pagination'   => $show_pagination
      ]);
      
      $table_name = $wpdb->prefix.'bb_slidersettings';
      $sql = "SELECT
                id
              FROM
                $table_name
              ORDER BY
                id
              DESC LIMIT 1;";
      $result = $wpdb->get_results($sql);
      $new_carousel_id = sanitize_text_field($result[0]->id);
      
      
      $table_name = $wpdb->prefix.'bb_sliderimages';
      $sql        = "UPDATE
                       {$table_name}
                     SET
                       carousel_id = %d
                     WHERE
                       carousel_id <> %d;";
                       
      $sql = $wpdb->prepare($sql, [ $new_carousel_id, $new_carousel_id ]);
      $wpdb->query($sql);
    } else {
        $wpdb->update($table_name, [
          'transition_time'   => $transition_time,
          'stop_on_hover'     => $stop_on_hover,
          'navigation_arrows' => $navigation_arrows,
          'show_pagination'   => $show_pagination
        ],
        [
          'id' => $table_id
        ]);
    }
  }
}
