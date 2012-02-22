<?php
/*
Plugin Name: Essentials Tag Alphabet
Plugin URI: http://www.pressessentials.com/
Description: This plugin adds an alphabet listing of your post tags to the page.
Version: 1.1
Author: Patriek Jeuriens
Author URI: http://www.pressessentials.com/

Code is based on the following code posted by Mfields on the wordpress forums.
*/

if (!is_admin()) {
	wp_enqueue_style( 'pss-tagalphabet', plugin_dir_url( __FILE__ ) . 'essentials-tag-alphabet.css', false, '1.0', 'screen' );
	//wp_enqueue_script('pss-color-jquery-js', plugin_dir_url( __FILE__ ) . 'jquery.animate-colors.js', false, false);
	//wp_enqueue_script('pss-tagalphabet-js', plugin_dir_url( __FILE__ ) . 'essentials-tag-alphabet.js', false, false);
	add_shortcode('tagalphabet', 'pss_tagalphabet_func');
}

function pss_tagalphabet_func($atts) {

	extract(shortcode_atts(array(
		'columns' => '2',
		'taxonomy'=> 'post_tag',
		'exclude'=> '0'		
	), $atts));
	
		/* Process
	================================================= */
	$list = '';
	
	$tags = get_terms($taxonomy,"hide_empty=1&exclude=$exclude");
	$hightags = get_terms($taxonomy,"hide_empty=1&orderby=count&order=desc&exclude=$exclude&number=5&fields=ids");
	$groups = array();
	$tagcount = count($tags);
	$counter = 1;
	$columnbreak = ($tagcount / $columns) + 2;
	$columncount = 1;
	
	if ($columns == 2) {
		$style = 'width: 50%';
	} elseif ($columns == 3) {
		$style = 'width: 33%';
	} elseif ($columns == 4) {	
		$style = 'width: 25%';
	} elseif ($columns == 5) {
		$style = 'width: 20%';
	} else {
		$style = 'width: 15%';
	}

	if( $tags && is_array( $tags ) ) {
		foreach( $tags as $tag ) {
			$first_letter = strtoupper( $tag->name[0] );
			$groups[ $first_letter ][] = $tag;
		}
		if( !empty( $groups ) ) {
			foreach( $groups as $letter => $tags ) {
				if ($counter == 1){
					$list .= '<div class="alphabet-column" style="'. $style .'">';
				}
				if  ($counter > ($columnbreak * $columncount)){
					$counter++;	
					$list .= '</div><div class="alphabet-column" style="'. $style .'">';
					$columncount++;
				}
				if ($counter > ($tagcount)){
					$list .= '</div><!-- end columns -->';
				}

				if  ($counter != ceil($columnbreak * $columncount) -1){
				$list .= "\n\t". '<div class="alphabet-capital">' . apply_filters( 'the_title', $letter ) . '</div>';
				$list .= "\n\t" . '<ul class="alphabet">';
				}
				
				foreach( $tags as $tag ) {
					$counter++;	
					if  ($counter >= ($columnbreak * $columncount)){
						$list .= '</div><div class="alphabet-column" style="'. $style .'">';
						$list .= "\n\t". '<div class="alphabet-capital">' . apply_filters( 'the_title', $letter ) . '</div>';
						$list .= "\n\t" . '<ul class="alphabet">';		
						$columncount++;
					}
				
					$url = attribute_escape(get_term_link($tag,$taxonomy) );
					$name = apply_filters( 'the_title', ucwords($tag->name) );
					
					if (in_array($tag->term_id, $hightags)) {			 
						$list .= "\n\t\t" . '<li class="alphabet-item pulsate"><a href="' . $url . '">' . $name . '</a> (' . intval( $tag->count ) . ')</li>';
					}  else {
						$list .= "\n\t\t" . '<li class="alphabet-item"><a href="' . $url . '">' . $name . '</a> (' . intval( $tag->count ). ')</li>';
					}
					
				}
				$list .= "\n\t" . '</ul>';
			}
		}
	} else {
		$list .= "\n\t" . '<p>No tags found.</p>';
	}	
	$output = '<div id="alphabet-container"><div id="alphabet-main">'. $list .'</div></div></div><br class="clear" />';
	return $output;

}
?>