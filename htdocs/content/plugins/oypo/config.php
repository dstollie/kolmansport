<?php

// ACTIONS AND FILTERS
add_action('admin_menu', 'oypo_admin_menu' );
add_action('admin_menu', 'oypo_edit_posts');
add_action('admin_init', 'oypo_save_settings' );
add_action('admin_init', 'register_oypo_settings' );
add_action('admin_enqueue_scripts', 'oypo_load_js_and_css' );
add_action('save_post', 'oypo_save_user_form');
add_filter('plugin_action_links', 'oypo_plugin_action_links', 10, 2 );
add_filter("the_content",'oypo_on_website');

// LOAD SCRIPS AND CSS
function oypo_load_js_and_css() {

	wp_register_style( 'colorpicker.css', OYPO_PLUGIN_URL . 'css/colorpicker.css', array(), '1.0.0.0' );
	wp_register_style( 'oypo.css', OYPO_PLUGIN_URL . 'css/oypo.css', array(), '1.0.0.0' );
	wp_enqueue_style( 'colorpicker.css');
	wp_enqueue_style( 'oypo.css');

	wp_register_script( 'oypo.js', OYPO_PLUGIN_URL . 'js/oypo.js', array('jquery'), '1.0.0.0' );
	wp_enqueue_script( 'colorpicker.js',  OYPO_PLUGIN_URL . 'js/colorpicker.js');
	wp_enqueue_script( 'oypo.js' );
}

// REGISTER ALL SETTINGS
function register_oypo_settings(){

	register_setting('oypo-settings', 'oypo-userid');
	register_setting('oypo-settings', 'oypo-wl-on');
	register_setting('oypo-settings', 'oypo-wl-id');
	register_setting('oypo-settings', 'oypo-kleuren');
	register_setting('oypo-settings', 'oypo-v1');
	register_setting('oypo-settings', 'oypo-v2');
	register_setting('oypo-settings', 'oypo-v3');
	register_setting('oypo-settings', 'oypo-v4');
	register_setting('oypo-settings', 'oypo-v5');
	register_setting('oypo-settings', 'oypo-v6');
	register_setting('oypo-settings', 'oypo-v1-trans');
	register_setting('oypo-settings', 'oypo-stylesheet');

}

// SHOW ON WEBSITE
function oypo_on_website($content){
	global $post, $oypo_errors;
	if(!is_404()){
	
		// VARS
		$oypo_settings['oypo-userid'] = get_option('oypo-userid');
		$oypo_settings['oypo-wl-on'] = get_option('oypo-wl-on');
		$oypo_settings['oypo-wl-id'] = get_option('oypo-wl-id');
		$oypo_settings['oypo-kleuren'] = get_option('oypo-kleuren');
		$oypo_settings['oypo-v1'] = get_option('oypo-v1');
		$oypo_settings['oypo-v2'] = get_option('oypo-v2');
		$oypo_settings['oypo-v3'] = get_option('oypo-v3');
		$oypo_settings['oypo-v4'] = get_option('oypo-v4');
		$oypo_settings['oypo-v5'] = get_option('oypo-v5');
		$oypo_settings['oypo-v6'] = get_option('oypo-v6');
		$oypo_settings['oypo-v1-trans'] = get_option('oypo-v1-trans');
		$oypo_settings['oypo-stylesheet'] = get_option('oypo-stylesheet');
		
		$oypo_meta['oypo_enabled'] = (get_post_meta($post->ID, 'oypo_enabled', true)!='') ? get_post_meta($post->ID, 'oypo_enabled', true) : get_option('oypo_enabled');
		$oypo_meta['oypo_soort'] = (get_post_meta($post->ID, 'oypo_soort', true)!='') ? get_post_meta($post->ID, 'oypo_soort', true) : get_option('oypo_soort');
		$oypo_meta['oypo_mapid'] = (get_post_meta($post->ID, 'oypo_mapid', true)!='') ? get_post_meta($post->ID, 'oypo_mapid', true) : get_option('oypo_mapid');
		$oypo_meta['oypo_nonav'] = (get_post_meta($post->ID, 'oypo_nonav', true)!='') ? get_post_meta($post->ID, 'oypo_nonav', true) : get_option('oypo_nonav');
		
		// GENERATION
		if($oypo_meta['oypo_enabled']){
	
			// BUILD THE JAVASCRIPT
			$oypo_code = "<script type=\"text/javascript\">\r\n";

			// Soort
			if($oypo_meta['oypo_soort'] == "mapid"){
				$oypo_code .= "var mode='map';\r\n";
				$oypo_code .= "var mapid='" . $oypo_meta['oypo_mapid'] . "';\r\n";
				$oypo_code .= "var nonav=";
				$oypo_code .= ($oypo_meta['oypo_nonav'] == "on") ? 1 : 0;
				$oypo_code .= ";\r\n";
			} elseif ($oypo_meta['oypo_soort'] == "mappen") {
				$oypo_code .= "var mode='user';\r\n";
				$oypo_code .= "var userid='" . $oypo_settings['oypo-userid'] . "';\r\n";
			} else {
				$oypo_code .= "var mode='school';\r\n";
			}
	
			// Whitelabel verzending
			if ($oypo_settings['oypo-wl-on'] == "on"){ $oypo_code .= "var wl='" . $oypo_settings['oypo-wl-id'] . "';\r\n"; }
			
			// Kleuren
			if($oypo_settings['oypo-kleuren'] == "stylesheet"){
				$oypo_code .= "var css='" . $oypo_settings['oypo-stylesheet'] . "';\r\n"; 
			}else{
				if($oypo_settings['oypo-kleuren'] == "custom"){
				
					// Transparantie
					if($oypo_settings['oypo-v1-trans'] == "on"){ $oypo_code .= "var transparency=1;\r\n"; }
				
					for ($i=1;$i<7;$i++){
						$oypo_code .= "var kleur" .$i. "='" . $oypo_settings['oypo-v' . $i] . "';\r\n";
					}
				}
			}
			
			$oypo_code .= "</script>\r\n";
			$oypo_code .= "<script type=\"text/javascript\" src=\"//www.oypo.nl/pixxer/api/templates/1207a.js\"></script>\r\n";
			$oypo_code .= "<div id=\"pixxer_iframe\"></div>\r\n";
	
			// ERROR HANDLING
			if($oypo_meta['oypo_soort'] == "mappen" && empty($oypo_settings['oypo-userid'])){ $oypo_errors[] = "Geen oypo gebruikersnaam ingevoerd"; }
			if($oypo_meta['oypo_soort'] == "mapid" && empty($oypo_meta['oypo_mapid'])){ $oypo_errors[] = "Geen mapid ingevoerd"; }
			if($oypo_settings['oypo-wl-on'] == "on" && empty($oypo_settings['oypo-wl-id'])){ $oypo_errors[] = "Geen whitelabel-id ingevoerd"; }
			if($oypo_settings['oypo-kleuren'] == "stylesheet"){
				if(strlen($oypo_settings['oypo-stylesheet'])<13 || preg_match('/http:\/\//i', substr($oypo_settings['oypo-stylesheet'], 0, 7)) == 0){
					$oypo_errors[] = 'Het adres van het stylesheet is niet volledig. Voer de volledige url in (voorbeeld: http://www.domeinnaam.nl/oypo.css)';
				}
			}
			
			// SHOW ERRORS
			if(sizeof($oypo_errors)>0){ 
				foreach($oypo_errors as $error){ $content .= '<p>Oypo implementatiefout - ' . $error . '</p>'; } 
				
			// SHOW OYPO
			}else{
				$content .= $oypo_code;
			}
			
		}
		
		return $content;
		
	}	
}	

?>