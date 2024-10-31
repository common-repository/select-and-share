<?php

function select_and_share_ui_stats() {
  
  global $wpdb;
  
  $table_name = SS_STATS_TABLE;
  $page = isset($_GET["paged"]) ? $_GET["paged"] : 1;

  $total = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");
  $ppp = intval("30");
  $wp_query->found_posts = $total;
  $wp_query->max_num_pages = ceil($wp_query->found_posts / $ppp);
  $on_page = $page;
  if($on_page == 0){ $on_page = 1; }
  $offset = ($on_page-1) * $ppp;
  $wp_query->request = "SELECT * FROM $table_name ORDER BY created_at DESC  LIMIT $ppp OFFSET $offset";
  $contents = $wpdb->get_results($wp_query->request, OBJECT);
  $totalpages = $wp_query->max_num_pages;
  
  $url = esc_url($_SERVER["REQUEST_URI"]);
  $page_links = paginate_links( array("base" => add_query_arg("paged", "%#%", $url),"format" => "",	"prev_text" => __("&laquo;"),	"next_text" => __("&raquo;"),	"total" => $totalpages,	"current" => $page ));

?>

<div class="wrap">
  <div id="icon-select-and-share" class="icon32"><br></div>
  <h2>Select and Share - <?php _e("Stats", "select-and-share") ?></h2>

  <div class="tablenav">
    <div style="float:left;"><?php _e("You can follow what's being shared on your blog: what content has been shared, how many times, and in which social networks.", "select-and-share"); ?></div>
  	<?php if(empty($contents)): ?><p style="clear:both;padding-top: 15px;"><?php _e("No stats found.", "select-and-share") ?></p><?php endif; ?>
    <?php	if($page_links): ?>
  	 <div class="tablenav-pages"><?php echo $page_links ?></div>
  	<?php endif; ?>
	  <br class="clear" />
	</div>
  
	<?php if($contents): ?>
    <table class="widefat" cellspacing="0">
    
      <thead>
        <tr>
          <th scope="col" class="manage-column"><?php _e("Page"); ?></th>
          <th scope="col" class="manage-column"><?php _e("Text"); ?></th>
          <th scope="col" class="manage-column"><?php _e("Shared", "select-and-share"); ?></th>
          <th scope="col" class="manage-column"><?php _e("Social Networks", "select-and-share"); ?></th>
        </tr>
      </thead>
    
      <tfoot>
        <tr>
          <th scope="col" class="manage-column"><?php _e("Page"); ?></th>
          <th scope="col" class="manage-column"><?php _e("Text"); ?></th>
          <th scope="col" class="manage-column"><?php _e("Shared", "select-and-share"); ?></th>
          <th scope="col" class="manage-column"><?php _e("Social Networks", "select-and-share"); ?></th>
        </tr>
      </tfoot>
    
      <tbody class="plugins">
      <?php foreach($contents as $content): ?>
        <tr>
          <td><a href="<?php echo $content->url ?>" target="_new"><?php echo $content->title ?></a></td>
          <td>
            <a href="#TB_inline?height=350&width=800&inlineId=content<?php echo $content->id ?>" title="<?php echo $content->title ?>" class="thickbox"><?php echo select_and_share_truncate($content->contents) ?></a>
            <div id="content<?php echo $content->id ?>" style="display:none">
              <p><?php echo $content->contents; ?></p> 
            </div> 
          </td>
          <td><?php echo $content->count ?></td>
          <td class="networks"><?php echo join(unserialize($content->networks), ", "); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
      
    </table>
  <?php endif; ?>

</div>

<?php
  
}
