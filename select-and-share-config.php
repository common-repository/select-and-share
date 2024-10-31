<?php

function select_and_share_ui_config() {
  
  if(isset($_POST["networks"])) {    
    update_option("ss_selected_networks", join(",", $_POST["networks"]));
    update_option("ss_title_networks", $_POST["title_networks"]);
    update_option("ss_wordpress_text", $_POST["wordpress_text"]);
    update_option("ss_email_text", $_POST["email_text"]);
    update_option("ss_stats", $_POST["ss_stats"]);
    
    if($_POST["ss_stats"]) { select_and_share_tables(); }
    
    $message = __("Options successfully updated.", "select-and-share");
  }

  $selected_networks = explode(",", get_option("ss_selected_networks"));
  
  $title_networks = get_option("ss_title_networks");
  $title_networks = empty($title_networks) ? __("Share this content.", "select-and-share") : $title_networks;
  
  $wordpress_text = get_option("ss_wordpress_text");
  $wordpress_text = empty($wordpress_text) ? __("Enter your WordPress URL.", "select-and-share") : $wordpress_text;
  
  $email_text = get_option("ss_email_text");
  $email_text = empty($email_text) ? __("Which e-mail do you want to send this content to?", "select-and-share") : $email_text;
  
  $networks = explode(",", SS_NETWORKS);
  
  $stats = get_option("ss_stats");  

?>
  
<div class="wrap">
  <div id="icon-select-and-share" class="icon32"><br></div>
  <h2>Select and Share</h2>
    
  <?php if(isset($message)): ?>
  <div id="message" class="updated"><p><?php echo $message; ?></p></div>
  <?php endif; ?>
  
  <h3><?php _e("Social Networks", "select-and-share"); ?></h3>
 
  <p><?php _e("Just click on the icon to enable or disable a social network.", "select-and-share"); ?></p>
    
  <form name="form" action="" method="post">
   
  <div class="networks">
    <?php foreach($networks as $network): ?>
      <label for="networks_<?php echo $network ?>" class="ss-image">
        <img src="<?php echo  plugins_url("icons/$network.png", __FILE__) ?>" class="<?php echo in_array($network, $selected_networks) ? "checked" : "non-checked" ?>" />
        <input type="checkbox" id="networks_<?php echo $network ?>" name="networks[<?php echo $network ?>]" value="<?php echo $network ?>" style="display:none" <?php echo in_array($network, $selected_networks) ? "checked" : "" ?> />
      </label>        
    <?php endforeach; ?>
   </div>
   
   <p>
    <input type="text" value="<?php echo $title_networks; ?>" size="60" id="title_networks" name="title_networks" /><br />
    <input type="text" value="<?php echo $wordpress_text; ?>" size="60" id="wordpress_text" name="wordpress_text" /><br />
    <input type="text" value="<?php echo $email_text; ?>" size="60" id="email_text" name="email_text" />
   </p>
   
   <h3>Select and Share - <?php _e("Stats", "select-and-share"); ?></h3>
    
   <p class="stats">
    <label for="ss_stats" id="label_ss_stats">
      <?php _e("Active Stats?", "select-and-share"); ?>:
      <img src="<?php echo plugins_url("icons/".($stats ? "check" : "uncheck").".png", __FILE__) ?>" />
      <input type="checkbox" id="ss_stats" style="display:none" name="ss_stats" <?php echo $stats ? "checked" : "" ?> value="true" />
    </label>
   </p>
   
   <input type="submit" name="Submit" class="button-primary" id="ss-submit" value="<?php esc_attr_e('Save Changes') ?>" />

  </form>
  
</div>

<?php
  
}
