<?php
// USER FUNCTIONS
function oypo_edit_posts() {

	if( function_exists( 'add_meta_box' )) {
		add_meta_box( 'Oypo', 'Oypo fotowinkel', 'oypo_options_box', 'page', 'advanced', 'high' );
		add_meta_box( 'Oypo', 'Oypo fotowinkel', 'oypo_options_box', 'post', 'advanced', 'high' );
		
	}else{
		add_action('dbx_post_advanced', 'oypo_options_box' );
		add_action('dbx_page_advanced', 'oypo_options_box' );
	}
}

function oypo_save_user_form($post_id) {

	global $post;

	// RETURN ON AUTOSAVE AND QUICK SUBMIT
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post->ID;
	if(!isset($_POST['oypo_submit'])) return $post->ID;
	
	// SAVE POSTED VARS
	if(isset($_POST['oypo_enabled'])){ add_post_meta($post->ID, 'oypo_enabled', $_POST['oypo_enabled'], true) or update_post_meta($post->ID, 'oypo_enabled', $_POST['oypo_enabled']); }
	if(isset($_POST['oypo_soort'])){ add_post_meta($post->ID, 'oypo_soort', $_POST['oypo_soort'], true) or update_post_meta($post->ID, 'oypo_soort', $_POST['oypo_soort']); }
	if(isset($_POST['oypo_mapid'])){ add_post_meta($post->ID, 'oypo_mapid', $_POST['oypo_mapid'], true) or update_post_meta($post->ID, 'oypo_mapid', $_POST['oypo_mapid']); }
	if(isset($_POST['oypo_nonav'])){ add_post_meta($post->ID, 'oypo_nonav', $_POST['oypo_nonav'], true) or update_post_meta($post->ID, 'oypo_nonav', $_POST['oypo_nonav']); }

}
	
function oypo_options_box(){

	// VARS
	global $post, $oypo_errors;
	
	$oypo_meta['oypo_enabled'] = get_post_meta($post->ID, 'oypo_enabled', true);
	$oypo_meta['oypo_soort'] = get_post_meta($post->ID, 'oypo_soort', true);
	$oypo_meta['oypo_mapid'] = get_post_meta($post->ID, 'oypo_mapid', true);
	$oypo_meta['oypo_nonav'] = get_post_meta($post->ID, 'oypo_nonav', true);
	
	// FIX VARS
	$var_array = array('enabled_checked_0', 'enabled_checked_1', 'enabled_div_style', 'soort_map_checked', 'soort_opties_style', 'soort_inlogkaartjes_checked', 'soort_mapid_checked', 'nonav_checked_on', 'nonav_checked_off');
	foreach($var_array as $var){ $oypo_meta[$var] = ''; }
	
	// RADIO & DISPLAY-STYLE FIXES
	if($oypo_meta['oypo_enabled'] == "on"){
		$oypo_meta['enabled_checked_1'] = 'checked';
	}else{
		$oypo_meta['enabled_checked_0'] = 'checked';
		$oypo_meta['enabled_div_style'] = 'display: none;';
	}
	if($oypo_meta['oypo_soort'] == "mappen"){ 
		$oypo_meta['soort_map_checked'] = 'checked';
		$oypo_meta['soort_opties_style'] = 'display:none;';
	}elseif($oypo_meta['oypo_soort'] == "inlogkaartjes"){ 
		$oypo_meta['soort_inlogkaartjes_checked'] = 'checked'; 
		$oypo_meta['soort_opties_style'] = 'display:none;';
	}else{ 
		$oypo_meta['soort_mapid_checked'] = 'checked';
	}
	if($oypo_meta['oypo_nonav'] == "on"){
		$oypo_meta['nonav_checked_on'] = 'checked';
	}else{
		$oypo_meta['nonav_checked_off'] = 'checked';
	}
	
	// SHOW ERRORS
	if($oypo_meta['oypo_enabled'] == "on" && $oypo_meta['oypo_soort'] == "mapid"){
		if(empty($oypo_meta['oypo_mapid'])){ $oypo_errors[] = "Het ID van de fotomap is nog niet ingevuld."; }
		if(!empty($oypo_meta['oypo_mapid']) && strlen($oypo_meta['oypo_mapid']) < 16){ $oypo_errors[] = "Het ingevulde ID van de fotomap is onjuist."; }
	
		if(sizeof($oypo_errors)>0){ 
	
			echo '<div id="setting-error-settings_updated" class="updated settings-error" style="margin-top: 10px;">';
			echo "<p>De volgende fouten zijn gevonden:</p>"; 
			foreach($oypo_errors as $error){ echo '<p><strong>- ' . $error . '</strong></p>'; } 
			echo '</div>';
		
		}
	}
	
	?>
	
	<div id="help">
		<div id="helptxt"></div>
		<div align="right"><input type="button" value="sluiten" style="margin:0 5px 5px 0;padding:0px 5px;border:1px solid #555;cursor:hand" onclick="styleRef('help').display='none'"></div>
	</div>
	
	
	<input type="hidden" id="oypo_submit" name="oypo_submit" value="1" />
	
	<div class="label">Inschakelen op deze pagina:</div><div class="option"><input type="radio" id="oypo_enabled_on_0" name="oypo_enabled" value="" <?php print($oypo_meta['enabled_checked_0']); ?> onClick="styleRef('oypo_enabled_div').display='none';"><label for="oypo_enabled_on_0">Nee</label>&nbsp;&nbsp;<input type="radio" id="oypo_enabled_on_1" name="oypo_enabled" value="on" <?php print($oypo_meta['enabled_checked_1']); ?> onClick="styleRef('oypo_enabled_div').display='block';"><label for="oypo_enabled_on_1">Ja</label></div>
	<div id="oypo_enabled_div" style="<?php print($oypo_meta['enabled_div_style']); ?>">
	
		<div class="label">Layout:</div>
		<div class="option">
			<input type="radio" id="rs1" name="oypo_soort" value="mapid" <?php print($oypo_meta['soort_mapid_checked']); ?> onClick="styleRef('ss1').display='block';styleRef('ss2').display='none'"><label for="rs1">Specifieke fotomap</label><br />
			<input type="radio" id="rs2" name="oypo_soort" value="mappen" <?php print($oypo_meta['soort_map_checked']); ?> onClick="styleRef('ss1').display='none';styleRef('ss2').display='block'"><label for="rs2">Mappen-overzicht</label><br />
			<input type="radio" id="rs3" name="oypo_soort" value="inlogkaartjes" <?php print($oypo_meta['soort_inlogkaartjes_checked']); ?> onClick="styleRef('ss1').display='none';styleRef('ss2').display='none'"><label for="rs3">Inlogkaartjes</label><br />
		</div>
			
		<div class="help" onClick="showHelp(this, 10)"></div><br />
	
		<div id="ss1" style="<?php print($oypo_meta['soort_opties_style']); ?>">
			<div class="label">ID van de fotomap:</div><div class="option"><input type="text" name="oypo_mapid" id="mapid" value="<?php print($oypo_meta['oypo_mapid']); ?>" style="width: 140px;"></div><div class="help" onClick="showHelp(this, 3)"></div>
			<div class="label">Mappennavigatie:</div><div class="option"><input type="radio" id="nonav0" name="oypo_nonav" value="" <?php print($oypo_meta['nonav_checked_off']); ?>><label for="nonav0">Aan</label>&nbsp;&nbsp;<input type="radio" id="nonav1" value="on" name="oypo_nonav" <?php print($oypo_meta['nonav_checked_on']); ?>><label for="nonav1">Uit</label></div><div class="help" onClick="showHelp(this, 5)"></div>
		</div>

	</div>
		
	<div class="clear"></div>
	<?php
}

?>