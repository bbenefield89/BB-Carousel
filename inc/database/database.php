<?php

namespace Inc\Database;

class Database {
  public function __construct() {}
  
  // Plugin Activation
  public static function activate() {
    self::create_db_table();
  }
  
  // Create the Database Table
  protected function create_db_table(string $table, array $fields) {
    global $wpdb;
    $table_name = $wpdb->prefix."$table";
    $table_charset = $wpdb->get_charset_collate();
    $field_names = [];
    $check_id = array_key_exists('id', $fields)  ? 'id' : 'image_id';
    
    foreach ($fields as $field => $type) {
      if ( in_array($field, [ 'id', 'image_id' ], true) ) {
        $field_names[] = "$field $type UNSIGNED NOT NULL AUTO_INCREMENT";
      } else {
          $field_names[] = "$field $type";
      }
    }
    
    $field_names = join(",\n", $field_names);
    
    // echo "<h1>$field_names</h1>";
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            $field_names,
            PRIMARY KEY  ($check_id)
            ) $table_charset;";
            
    require_once ABSPATH.'wp-admin/includes/upgrade.php';
    
    dbDelta($sql);
  }
}
