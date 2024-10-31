var _ss_title_page = jQuery('title').text();
var _ss_url_site = window.location.href;
var _ss_data_stats = new _ss_stats();
var _ss_stats_enable = false;
var _ss_selected_text;
var _ss_data_stats_url;
var _ss_email_is_invalid;
var _ss_url_is_invalid

jQuery(window).load(function() {                
   _ss_set_bind_body();
});

function _ss_get_selected_text() {
  if (window.getSelection) { _ss_selected_text = window.getSelection(); }
  else if (document.getSelection) { _ss_selected_text = document.getSelection(); }
  else if (document.selection) { _ss_selected_text = document.selection.createRange().text; }
  _ss_selected_text = String(_ss_selected_text).replace(/\n/gi, " ");
  _ss_create_social_networks();
}

function _ss_set_bind_body() {  
  jQuery('body').bind({
      mouseup: function(event) {
        _ss_get_selected_text();
        _ss_social_networks(event);
      },
      mousemove: function(event) {
        if(_ss_selected_text.length == 0) {
          jQuery('#ss_social_networks').fadeOut();
          jQuery('#ss_social_networks').remove();
        }
      },
      click: function(event) {
        jQuery('#ss_social_networks').hide();
        jQuery('#ss_social_networks').remove();
      }
  }); 
}

function _ss_shared_network(network, ss_selected_text) {
  
  jQuery('#ss_social_networks').hide();
  
  _ss_data_stats.network = network;
  _ss_data_stats.selected_text = ss_selected_text;
  
  switch(network) {
    case 'facebook':
      _ss_window_open('http://www.facebook.com/sharer.php?s=100&p[title]=' + _ss_title_page + '&p[summary]=' + ss_selected_text + '&p[url]=' + _ss_url_site, 626, 436);
      break;
    case 'delicious':
      _ss_window_open('http://www.delicious.com/save?mini=true&url=' + escape(_ss_url_site) + '&title=' + _ss_title_page + '&notes=' + ss_selected_text, 1000, 560);
      break;
    case 'google':
      _ss_window_open('https://www.google.com/bookmarks/mark?op=edit&bkmk=' + escape(_ss_url_site) + '&title=' + _ss_title_page + '&annotation=' + ss_selected_text, 800, 500);
      break;
    case 'hotmail':
      _ss_window_open('http://bl139w.blu139.mail.live.com/default.aspx?rru=compose&subject=' + _ss_title_page + '&body=' + ss_selected_text + '<br><br>' + escape(_ss_url_site), 1000, 500);
      break;
    case 'linkedin':
      _ss_window_open('http://www.linkedin.com/shareArticle?mini=true&url=' + _ss_url_site + '&title=' + _ss_title_page + '&summary=' + ss_selected_text, 800, 500);
      break;
    case 'orkut':
      _ss_window_open('http://promote.orkut.com/preview?nt=orkut.com&tt=' + _ss_title_page + '&du=' + escape(_ss_url_site) + '&cn=' + ss_selected_text, 800, 500);
      break;
    case 'twitter':
      _ss_window_open('http://twitter.com/share?count=horizontal&counturl=' + _ss_url_site + '&original_referer=' + _ss_url_site + '&text=' + ss_selected_text + '&url=' + _ss_url_site, 500, 200);
      break;
    case 'yahoo':
      _ss_window_open('http://br.mc1140.mail.yahoo.com/mc/compose?.rand' + Math.random() + '&subject=' + _ss_title_page + '&body=' + ss_selected_text + '<br><br>' + escape(_ss_url_site), 800, 600);
      break;
    case 'email':
      _ss_share_email(ss_selected_text);
      break;
    case 'wordpress':
      _ss_share_wordpress(ss_selected_text, 'http://');
      break;
  }
   
  jQuery('#ss_social_networks').remove();
  
}

function _ss_window_open(url, width, height) {
  if(_ss_stats_enable) { _ss_stats_send(); } 
  return window.open(url, 'sharer', 'toolbar=0,status=0,width=' + width + ',height=' + height);
}

function _ss_social_networks(event) {
  if(_ss_selected_text.length > 0) {
    jQuery('#ss_social_networks').css({"top" : event.clientY - (jQuery('#ss_social_networks').height() / 2), "left" : event.clientX - (jQuery('#ss_social_networks').width() / 2)});
    jQuery('#ss_social_networks').fadeIn();
    jQuery('#ss_social_networks').bind({
       mouseleave: function(event) {
         jQuery(this).fadeOut();
         jQuery('#ss_social_networks').unbind();
       }
    });
  } 
}

function _ss_non_shared_text() {
  jQuery('#ss_social_networks').hide();
  jQuery('#ss_social_networks').remove();
  _ss_selected_text = null;
}


function _ss_create_social_networks() {
  var social_networks = '<table id="ss_social_networks" border="0">' + 
    '<tr>' + 
      '<td>' + _ss_title_networks + '</td>' + 
      '<td style="padding-left: 0px"><a href="javascript:void(0)" onclick="_ss_non_shared_text()">X</a></td>' + 
    '</tr>' + 
    '<tr>' + 
      '<td colspan="2">' + 
        '<ul>';
        jQuery.each(_ss_networks, function(index, network) {                     
          social_networks += '<li title="' + network + '" class="' + network + '" onclick="_ss_shared_network(\'' + network + '\', \'' + _ss_selected_text + '\')">&nbsp;</li>';
        });
        social_networks += '</ul>' + 
      '</td>' +
   '</tr>' + 
  '</table>';
 jQuery('body').append(social_networks);
}

function _ss_share_wordpress(ss_selected_text, init_url) {
  var url = prompt(_ss_wordpress_text, init_url);
  var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
  if(url != null && url != "http://") {
    if(regexp.test(url)) {
      if(url.charAt(url.length - 1) != '/') { url = url + '/' }
      _ss_window_open(url + 'wp-admin/press-this.php?u=' + _ss_url_site + '&t=' + _ss_title_page + '&s=' + ss_selected_text + '&v=1', 950, 600);
    } else {
      alert(_ss_url_is_invalid);
     _ss_share_wordpress(ss_selected_text, url);
    }
  }
}

function _ss_share_email(ss_selected_text, init_email) {
  var email = prompt(_ss_email_text, init_email);
  var regexp = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
  if(email != null) {
    if(regexp.test(email)) {
      win = _ss_window_open('mailto:' + email + '?subject=' + _ss_title_page + '&body=' + ss_selected_text + escape('\n\n') + escape(_ss_url_site), 1, 1);
      win.close();
    } else {
      alert(_ss_email_is_invalid);
     _ss_share_email(ss_selected_text, email);
    }
  }
}

function _ss_stats_send() {   
  jQuery.post(_ss_data_stats_url, _ss_data_stats);
}

function _ss_stats() {
  this.title_page = _ss_title_page;
  this.url_site = _ss_url_site;
  this.network;
  this.selected_text;
}
