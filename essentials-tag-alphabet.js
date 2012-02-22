jQuery(document).ready(function() {
  var col = jQuery(".pulsate a").css('color');
  function pulsate() {
    jQuery(".pulsate a").
	  animate({color: '#FFF'}, 2000, 'linear').
      animate({color: col}, 2000, 'linear', pulsate);
  }
  pulsate();
});