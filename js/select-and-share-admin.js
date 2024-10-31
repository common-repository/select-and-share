jQuery(window).load(function() {
                      
  jQuery("label.ss-image").click(function() {                                                
    jQuery(this).find("img").removeClass();                                                
    if(jQuery(this).find("input").is(":checked")) {          
      jQuery(this).find("img").addClass("checked");
    } else {
      jQuery(this).find("img").addClass("non-checked");
    }
  });
  
  jQuery("#label_ss_stats").click(function() {                                                
    jQuery(this).find("img").removeClass();
    var src = jQuery(this).find("img").attr("src");
    if(jQuery("#ss_stats").is(":checked")) {
      jQuery(this).find("img").attr("src", src.replace("/uncheck.png", "/check.png"));
    } else {
      jQuery(this).find("img").attr("src", src.replace("/check.png", "/uncheck.png"));
    }
  });
  
  jQuery("table.zebra").find("tr:nth-child(even)").each(
      function(i) {
        if( 0 == jQuery(this).find("th").length ) {
          jQuery(this).addClass("even");
        }
      }
    );
  
});
