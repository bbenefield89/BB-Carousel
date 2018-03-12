<?php

namespace Inc\Database;

class SliderImages extends Database {
  public function __construct() {}
  
  public function create_db(string $table, array $fields) {
    $this->create_db_table($table, $fields);
  }
}
