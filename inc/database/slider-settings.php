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
    $sql = "SELECT
              id
            FROM
              $table_name;";
    $result = $wpdb->get_results($sql);
    
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
    $table_name    = $wpdb->prefix.'bb_slidersettings';
    $sql           = "SELECT
                      id
                      FROM
                      $table_name;";
    $result        = $wpdb->get_results($sql);
    
    if (count($result) === 0) {
      $sql = "INSERT INTO $table_name (
              id, transition_time, loop_carousel, stop_on_hover, reverse_order, navigation_arrows, show_pagination)
              VALUES (
              NULL, $transition_time, '$loop_carousel', '$stop_on_hover', '$reverse_order', '$navigation_arrows', '$show_pagination');";
              
      $wpdb->query($sql);
    } else {
        $field_values = [];
        
        foreach ($fields[0] as $field => $value) {
          if ($field === 'transition_time') {
            $field_values[] = "$field = $value";
          } else {
              $field_values[] = "$field = '$value'";
          }
        }
        
        $field_values = join(",\n", $field_values);
      
        $sql = "UPDATE $table_name
                SET
                $field_values;";
                
        $wpdb->query($sql);
    }
  }
}
