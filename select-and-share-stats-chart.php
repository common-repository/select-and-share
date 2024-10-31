<?php

function select_and_share_ui_stats_chart() {
  
  global $wpdb, $ss_networks;
  
  $table_name = SS_STATS_NETWORKS_TABLE;
  $networks = $wpdb->get_results("SELECT * FROM $table_name ORDER BY count");
  $total = 0;

?>

<div class="wrap">
  <div id="icon-select-and-share" class="icon32"><br></div>
  <h2>Select and Share - <?php _e("Stats Chart", "select-and-share"); ?></h2>
     
  <script src="<?php echo plugins_url("js/MochiKit/MochiKit.js", __FILE__) ?>" type="text/javascript"></script> 
  <script src="<?php echo plugins_url("js/PlotKit/Base.js", __FILE__) ?>" type="text/javascript"></script> 
  <script src="<?php echo plugins_url("js/PlotKit/Layout.js", __FILE__) ?>" type="text/javascript"></script> 
  <script src="<?php echo plugins_url("js/PlotKit/Canvas.js", __FILE__) ?>" type="text/javascript"></script> 
  <script src="<?php echo plugins_url("js/PlotKit/SweetCanvas.js", __FILE__) ?>" type="text/javascript"></script>
  
  <p><?php _e("Follow the stats of your shared content in the chart.", "select-and-share"); ?></p>
  
  <?php if(empty($networks)): ?>
    <?php _e("No stats found.", "select-and-share") ?>
  <?php else: ?>
    <table>
      <tr>
        <td><div><canvas id="chart" width="600" height="450"></canvas></div></td>
        <td valign="top">
          <table id="values" class="zebra">
              <thead>
                <tr><th colspan="4"><?php _e("Social Networks", "select-and-share"); ?></th></tr>
              </thead>
              
              <tbody> 
                <?php foreach($ss_networks as $key => $network): ?>
                  <tr>
                    <?php  
                      $count = select_and_share_netwotk_count($networks, $network);
                      $total += $count; 
                    ?>
                    <td><img src="<?php echo plugins_url("icons/$network.png", __FILE__) ?>" alt="<?php echo $network ?>" title="<?php echo $network ?>" /></td>
                    <td><?php echo $network; ?></td>
                    <td style="display:none;"><?php echo $key; ?></td>
                    <td><?php echo $count ?></td>
                  </tr> 
                <?php endforeach; ?>
              </tbody> 
              <tfoot>
                <tr><th colspan="4">Total: <?php echo $total; ?></th></tr>
              </tfoot>
          </table>
        </td>
      </tr>
    </table>  
  
    <script type="text/javascript"> 
    
      function chart() {
        
         var labels = [
         <?php foreach($ss_networks as $key => $network): ?>
          {v:<?php echo $key ?>, label:IMG({src:"<?php echo plugins_url("icons/$network.png", __FILE__) ?>", width:16, height:16, title:"<?php echo $network ?>"})},
         <?php endforeach; ?>
         ];
                
        var optsLayout = {"pieRadius": 0.4, "xTicks": labels };
        var optsRender = { drawBackground: false }; 
          
        var layout = new Layout("pie", optsLayout);       
        layout.addDatasetFromTable("dataset", $("values"), xcol = 2, ycol = 3);
        layout.evaluate();
        var chart = new SweetCanvasRenderer($("chart"), layout, optsRender);
        chart.render();
    
      }
       
      addLoadEvent(chart);
    
    </script> 

<?php endif; ?>
</div>

<?php
  
}
