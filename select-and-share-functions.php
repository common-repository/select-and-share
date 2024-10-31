<?php

  function select_and_share_tables() {
    
      global $wpdb;
      
      require_once(ABSPATH . "wp-admin/includes/upgrade.php");

      $table_name = SS_STATS_TABLE;
      $sql = "CREATE TABLE $table_name (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                url VARCHAR(255) NOT NULL,
                networks VARCHAR(255) NOT NULL,
                contents TEXT NOT NULL,
                count INT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
              );";

      dbDelta($sql);
      
      $table_name = SS_STATS_NETWORKS_TABLE;
      $sql = "CREATE TABLE $table_name (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                network VARCHAR(255) NOT NULL,
                count INT NOT NULL,
                created_at DATETIME NOT NULL,
                updated_at DATETIME NOT NULL
              );";

      dbDelta($sql);
              
  }
  
  function create_or_update_stats() {
    
    global $wpdb;
  
    $table_name = SS_STATS_TABLE;
    $selected_text = $_POST["selected_text"];
    
    $content = $wpdb->get_row("SELECT * FROM $table_name WHERE contents LIKE '$selected_text'");
    
    if(empty($content)) {    
      $wpdb->insert($table_name, array("title" => $_POST["title_page"], "url" => $_POST["url_site"], "contents" => $_POST["selected_text"], "networks" => serialize(array($_POST["network"])), "count" => 1, "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")));
    } else {
      $networks = unserialize($content->networks);
      array_unshift($networks, $_POST["network"]);
      $networks = array_unique($networks);
      $wpdb->update($table_name, array("count" => $content->count + 1, "updated_at" => date("Y-m-d H:i:s"), "networks" => serialize($networks)), array("id" => $content->id ));
    }
    
  }
  
  function update_stats_networks() {
    
    global $wpdb;
    
    $network = $_POST["network"];
    $table_name = SS_STATS_NETWORKS_TABLE;
    $stats = $wpdb->get_row("SELECT * FROM $table_name WHERE network LIKE '$network'");
    
    if(empty($stats)) {    
      $wpdb->insert($table_name, array("network" => $network, "count" => 1, "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y-m-d H:i:s")));
    } else {
      $wpdb->update($table_name, array("count" => $stats->count + 1, "updated_at" => date("Y-m-d H:i:s")), array("id" => $stats->id ));
    }
    
  }
  
  function select_and_share_truncate($string, $length='' ,$k=''){
    if( $length == '') $length = 50;
    if( $k == '') $k = '...';
    settype($string, 'string');
    settype($length, 'integer');
    for($a = 0; $a < $length AND $a < strlen($string); $a++){
        $output .= $string[$a];
    }
    if( strlen($string) > $length)
      $output .= $k;
    return($output);
  }
  
  function select_and_share_netwotk_count($items, $network) {
    foreach($items as $item) {
      if($item->network == $network) {
        return (int)$item->count; 
      }
    }
    return 0;
  }
  
?>