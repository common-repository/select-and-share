<?php

/**
Plugin Name: Select and Share
Plugin URI:  http://www.varaldebits.com.br/select-and-share/
Description: This plugins makes possible for your readers to share pieces of content from your site in the following social networks: Facebook, Twitter, Orkut, Delicious, Hotmail, LinkedIn, Yahoo!, MySpace, and also on WordPress and on e-mail. You also will have the option to follow stats on the texts that are being shared and the reach in each social network.
Author: Renato Ferraz
Version: 2.0
Author URI: http://www.varaldebits.com.br
*/

global $table_prefix;

load_plugin_textdomain("select-and-share", false, dirname( plugin_basename( __FILE__ ) ) . "/lang/" );

/*
Get the necessary files
*/
require(dirname(__FILE__) . "/select-and-share-functions.php");
require(dirname(__FILE__) . "/select-and-share-config.php");
require(dirname(__FILE__) . "/select-and-share-stats.php");
require(dirname(__FILE__) . "/select-and-share-stats-chart.php");

define("SS_STATS_TABLE", $table_prefix."ss_stats");
define("SS_STATS_NETWORKS_TABLE", $table_prefix."ss_stats_networks");
define("SS_NETWORKS", "facebook,twitter,orkut,delicious,google,hotmail,linkedin,yahoo,email,wordpress");
define("SS_TITLE_NETWORKS", __("Share this content.", "select-and-share"));
define("SS_WORDPRESS_TEXT", __("Enter your WordPress URL.", "select-and-share"));
define("SS_EMAIL_TEXT", __("Which e-mail do you want to send this content to?", "select-and-share"));

$ss_networks = explode(",", SS_NETWORKS);

add_action("init", "select_and_share_init");
add_action("admin_menu", "select_and_share_option");
add_action("wp_head", "select_and_share_head");
add_action("wp_ajax_select_and_share_stats", "select_and_share_stats");

register_activation_hook(__FILE__, "select_and_share_activate");

function select_and_share_stats() {
	create_or_update_stats();
  update_stats_networks();
}

function select_and_share_activate() {
  add_option("ss_stats", "true");
  add_option("ss_selected_networks", SS_NETWORKS);
  add_option("ss_title_networks", SS_TITLE_NETWORKS);
  add_option("ss_wordpress_text", SS_WORDPRESS_TEXT);
  add_option("ss_email_text", SS_EMAIL_TEXT);
  select_and_share_tables();
}

function select_and_share_option() {  
  add_menu_page("Select and Share", "Select and Share", 8, "select-and-share-ui-config", "select_and_share_ui_config", plugin_dir_url(__FILE__)."icons/icon_16x16.png");
	add_submenu_page("select-and-share-ui-config", __("Stats", "select-and-share"), __("Stats", "select-and-share"), 8, "select-and-share-ui-stats", "select_and_share_ui_stats");
	add_submenu_page("select-and-share-ui-config", __("Stats Chart", "select-and-share"), __("Stats Chart", "select-and-share"), 8, "select-and-share-ui-stats-chart", "select_and_share_ui_stats_chart" );
}

function select_and_share_init() {
  if( is_admin() ) {
    wp_enqueue_script("select-and-share-js-admin", plugins_url("js/select-and-share-admin.js", __FILE__), array("jquery", "thickbox"));
    wp_enqueue_style("select-and-share-style", plugins_url("css/select-and-share-admin.css", __FILE__), array("thickbox"));
  } else {
    wp_enqueue_script("select-and-share-js", plugins_url("js/select-and-share.js", __FILE__), array("jquery"));
    wp_enqueue_style("select-and-share-style", plugins_url("css/select-and-share.css", __FILE__));
  }
}

function select_and_share_head() {
  
  $title_networks = get_option("ss_title_networks");
  $title_networks = empty($title_networks) ? __("Enter your WordPress URL.", "select-and-share") : $title_networks;

  $wordpress_text = get_option("ss_wordpress_text");
  $wordpress_text = empty($wordpress_text) ? __("Enter your WordPress URL.", "select-and-share") : $wordpress_text;
  
  $email_text = get_option("ss_email_text");
  $email_text = empty($email_text) ? __("Which e-mail do you want to send this content to?", "select-and-share") : $email_text;
  
  $stats = get_option("ss_stats");
    
?>

  <script type="text/javascript">
  
    var _ss_networks = Array('<?php echo str_replace(",", "','", get_option("ss_selected_networks")) ?>');   
    var _ss_title_networks = '<?php echo $title_networks; ?>';
    var _ss_wordpress_text = '<?php echo $wordpress_text ?>';
    var _ss_email_text = '<?php echo $email_text ?>';
    var _ss_stats_enable = <?php echo $stats ? 'true' : 'false' ?>;
    
    _ss_email_is_invalid = '<?php echo __("Email is invalid.", "select-and-share") ?>';
    _ss_url_is_invalid = '<?php echo __("URL is invalid.", "select-and-share") ?>';
    
    _ss_data_stats_url = '<?php bloginfo("wpurl"); ?>/wp-admin/admin-ajax.php';
    _ss_data_stats.action = 'select_and_share_stats';
    
  </script>
  
<?php
}

?>
