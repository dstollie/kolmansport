<?php
function oypo_plugin_action_links( $links, $file ) {
	if ( $file == plugin_basename( dirname(__FILE__).'/oypo.php' ) ) {
		$links[] = '<a href="' . admin_url( 'admin.php?page=oypo_main_config' ) . '">'.__( 'Settings' ).'</a>';
	}
	return array_reverse($links);
}

function oypo_admin_menu() {
	add_submenu_page('plugins.php','Oypo Instellingen','Oypo instellingen', 'manage_options', 'oypo_main_config', 'oypo_settings_page');
}

function oypo_valid_vars(){

	global $oypo_errors, $oypo_settings;

	$oypo_errors = array();

	// ERROR HANDLING
	if(empty($oypo_settings['userid'])){ $oypo_errors[] = "U heeft nog geen gebruikersnaam opgegeven."; }
	if($oypo_settings['wl_on'] == "on" && empty($oypo_settings['wl_id'])){ $oypo_errors[] = "Het ID voor White label verzending is niet ingevuld"; }
	if($oypo_settings['kleuren'] == "stylesheet"){
		if(strlen($oypo_settings['stylesheet'])<13 || preg_match('/http:\/\//i', substr($oypo_settings['stylesheet'], 0, 7)) == 0){
			$oypo_errors[] = 'Het adres van het stylesheet is niet volledig. Voer de volledige url in (voorbeeld: http://www.domeinnaam.nl/oypo.css)';
		}
	}

	if(isset($_POST['oypo_submit'])){

		echo '<div id="setting-error-settings_updated" class="updated settings-error" style="margin-top: 10px;">';

		if(sizeof($oypo_errors)==0){
			echo "<p><strong>Instellingen opgeslagen.</strong></p>";
		}else{
			echo "<p>De volgende fouten zijn gevonden:</p>";
			foreach($oypo_errors as $error){ echo '<p><strong>- ' . $error . '</strong></p>'; }
		}

		echo '</div>';
	}

}


function oypo_generate_preview(){

	global $oypo_settings, $oypo_errors;

	if(sizeof($oypo_errors) == 0){

		$code  = '<script type="text/javascript">';
		$code .= 'delete transparency;';
		$code .= 'var mode=\'user\';';
		$code .= 'var userid=\''. $oypo_settings['userid'] . '\';';

		// TRANSPARENCY
		if($oypo_settings['v1_trans'] == "on"){ $code .= 'var transparency=1;'; }

		// Whitelabel verzending
		if ($oypo_settings['wl_on'] == "on" && !empty($oypo_settings['wl_id'])){ $code .= 'var wl=\'' . $oypo_settings['wl_id'] . '\';'; }

		// Kleuren
		if ($oypo_settings['kleuren'] == "stylesheet"){ $code .= 'var css=\'' . $oypo_settings['stylesheet'] . '\';'; }
		if ($oypo_settings['kleuren'] == "custom"){
			for($i=1;$i<7;$i++){
				$code .= 'var kleur'. $i .'=\'' . $oypo_settings['v' . $i] . '\';';
			}
		}

		$code .= '</script>';
		$code .= '<script type="text/javascript" src="//www.oypo.nl/pixxer/api/templates/1207a.js"></script>';
		$code .= '<div id="pixxer_iframe"></div>';

		echo $code;

	}else{
		echo '<span class="description">- Voorbeeld kan niet gemaakt worden. Vul alle velden correct in.</span>';
	}
}

function oypo_save_settings(){

	// SET GLOBALS
	global $oypo_settings;

	// SAVE CHANGES
	if(isset($_POST['oypo_userid'])){ update_option('oypo-userid', $_POST['oypo_userid']); }
	if(isset($_POST['oypo_password'])){ update_option('oypo-password', $_POST['oypo_password']); }
	if(isset($_POST['oypo_wl_on'])){ update_option('oypo-wl-on', $_POST['oypo_wl_on']); }
	if(isset($_POST['oypo_wl_id'])){ update_option('oypo-wl-id', $_POST['oypo_wl_id']); }
	if(isset($_POST['oypo_kleuren'])){ update_option('oypo-kleuren', $_POST['oypo_kleuren']); }
	if(isset($_POST['oypo_v1'])){ update_option('oypo-v1', $_POST['oypo_v1']); }
	if(isset($_POST['oypo_v2'])){ update_option('oypo-v2', $_POST['oypo_v2']); }
	if(isset($_POST['oypo_v3'])){ update_option('oypo-v3', $_POST['oypo_v3']); }
	if(isset($_POST['oypo_v4'])){ update_option('oypo-v4', $_POST['oypo_v4']); }
	if(isset($_POST['oypo_v5'])){ update_option('oypo-v5', $_POST['oypo_v5']); }
	if(isset($_POST['oypo_v6'])){ update_option('oypo-v6', $_POST['oypo_v6']); }
	if(isset($_POST['oypo_v1_trans'])){ update_option('oypo-v1-trans', $_POST['oypo_v1_trans']); }else{ update_option('oypo-v1-trans', ''); }
	if(isset($_POST['oypo_stylesheet'])){ update_option('oypo-stylesheet', $_POST['oypo_stylesheet']); }

	// GET VALUES
	$oypo_settings['userid'] = get_option('oypo-userid');
	$oypo_settings['password'] = get_option('oypo-password');
	$oypo_settings['wl_on'] = get_option('oypo-wl-on');
	$oypo_settings['wl_id'] = get_option('oypo-wl-id');
	$oypo_settings['kleuren'] = get_option('oypo-kleuren');
	$oypo_settings['v1'] = (get_option('oypo-v1') == '') ? '#FFFFFF' : get_option('oypo-v1');
	$oypo_settings['v2'] = (get_option('oypo-v2') == '') ? '#54544F' : get_option('oypo-v2');
	$oypo_settings['v3'] = (get_option('oypo-v3') == '') ? '#FFFFFF' : get_option('oypo-v3');
	$oypo_settings['v4'] = (get_option('oypo-v4') == '') ? '#666666' : get_option('oypo-v4');
	$oypo_settings['v5'] = (get_option('oypo-v5') == '') ? '#00BBFF' : get_option('oypo-v5');
	$oypo_settings['v6'] = (get_option('oypo-v6') == '') ? '#F0F0F0' : get_option('oypo-v6');
	$oypo_settings['v1_trans'] = get_option('oypo-v1-trans');
	$oypo_settings['stylesheet'] = (get_option('oypo-stylesheet') == '') ? '' : get_option('oypo-stylesheet');

}


function oypo_settings_page() {

	// IMPORT VARS
	global $oypo_settings, $oypo_errors, $wp_version;

	// ERRORS
	oypo_valid_vars();

	// VARS FIXES
	$var_array = array('wl_on_checked', 'wl_off_checked', 'wl_style', 'kleuren_custom_checked', 'kleuren_custom_style', 'kleuren_stylesheet_checked', 'kleuren_stylesheet_style', 'kleuren_standaard_checked', 'v1_trans_checked');
	foreach($var_array as $var){ $oypo_settings[$var] = ''; }

	// RADIO FIXES
	if($oypo_settings['wl_on'] == "on"){
		$oypo_settings['wl_on_checked'] = 'checked';
	}else{
		$oypo_settings['wl_off_checked'] = 'checked';
		$oypo_settings['wl_style'] = 'display: none;';
	}
	if($oypo_settings['kleuren'] == "custom"){
		$oypo_settings['kleuren_custom_checked'] = 'checked';
		$oypo_settings['kleuren_custom_style'] = 'display: table-row;';
	}elseif($oypo_settings['kleuren'] == "stylesheet"){
		$oypo_settings['kleuren_stylesheet_checked'] = 'checked';
		$oypo_settings['kleuren_stylesheet_style'] = 'display: table-row;';
	}else{
		$oypo_settings['kleuren_standaard_checked'] = 'checked';
	}
	if($oypo_settings['v1_trans'] == "on"){
		$oypo_settings['v1_trans_checked'] = 'checked';
	}

	// CSS VERSION FIXES
	$wrap_padding = ($wp_version > 3.7) ? '0 0 15px 0' : '0 0 15px 15px';

	?>
	
	<form method="post">
	
	<div class="wrap">
	
		<div id="icon-options-general" class="icon32"></div>
		<h2>Oypo Instellingen</h2>

		<div id="help">
			<div id="helptxt"></div>
			<div align="right"><input type="button" value="sluiten" style="margin:0 5px 5px 0;padding:0px 5px;border:1px solid #555;cursor:hand" onclick="styleRef('help').display='none'"></div>
		</div>
		
		<div class="widget-liquid-left" style="margin-right: 0px; width: 100%;">
		
			<div id="widgets-left" style="margin-right: 0px;">
			
				<div class="widgets-holder-wrap" style="background: transparent;">
					<div class="sidebar-name"><h3>Accountinstellingen</h3></div>
					<div class="widget-holder inactive">
					
						<div class="wrap" style="padding: <?php print($wrap_padding); ?>;">
							
							<table class="wp-list-table widefat fixed users" cellspacing="0" border="0" style="border-bottom: 0px;">
								<tr class="alternate"><td><b>Gebruikersnaam van uw Oypo-account:</b></td><td><div class="option"><input type="text" name="oypo_userid" id="oypo_userid" value="<?php print($oypo_settings['userid']); ?>" style="width: 130px;"></div></td><td><div class="help" onClick="showHelp(this, 1)"></div></td></tr>
								<tr class="alternate"><td><b>Wachtwoord van uw Oypo-account:</b></td><td><div class="option"><input type="text" name="oypo_password" id="oypo_password" value="<?php print($oypo_settings['password']); ?>" style="width: 130px;"></div></td><td><div class="help" onClick="showHelp(this, 13)"></div></td></tr>
								<tr class="alternate"><td><b>White label verzending:</b></td><td><div class="option"><input type="radio" id="wlcheck0" name="oypo_wl_on" value="" <?php print($oypo_settings['wl_off_checked']); ?> onClick="styleRef('wl1').display='none';"><label for="wlcheck0">nee</label>&nbsp;&nbsp;<input type="radio" id="wlcheck1" name="oypo_wl_on" value="on" <?php print($oypo_settings['wl_on_checked']); ?> onClick="styleRef('wl1').display='table-row';"><label for="wlcheck1">ja</label></div></td><td><div class="help" onClick="showHelp(this, 8)"></div></td></tr>
								<tr id="wl1" class="alternate" style="<?php print($oypo_settings['wl_style']); ?>"><td><b>ID van het white label:</b></td><td><div class="label"></div><div class="option"><input type="text" name="oypo_wl_id" id="oypo_wl_id" value="<?php print($oypo_settings['wl_id']); ?>" style="width: 100px;" /></div></td><td><div class="help" onClick="showHelp(this, 9)"></div></td></tr>	
							</table>
							
						</div>
					</div>
				</div>
						
				<div class="widgets-holder-wrap" style="background: transparent;">
					<div class="sidebar-name"><h3>Kleurinstellingen</h3></div>
					<div class="widget-holder inactive">
						
						<div class="sidebar-description">
							<p class="description">Kies voor het standaard kleurenschema van Oypo (met witte achtergrond), of kies uw eigen kleuren bij 'Zelf aanpassen'. Als u volledige vrijheid wilt hebben, dan kunt u ook uw eigen stylesheet gebruiken door de url in te vullen van de locatie waar uw eigen stylesheet-bestand staat.</p>
						</div>
						
						<div class="wrap" style="padding: <?php print($wrap_padding); ?>;">
							
							<table class="wp-list-table widefat fixed users" cellspacing="0" border="0" style="border-bottom: 0px;">
								<tr class="alternate"><td><b>Kleurenschema:</b></td><td>
									<div class="option">
										<input type="radio" id="rk1" name="oypo_kleuren" <?php print($oypo_settings['kleuren_standaard_checked']); ?> value="standaard" onClick="styleRef('sk1').display='none';styleRef('sk2').display='none'"><label for="rk1">Standaard</label><br />
										<input type="radio" id="rk2" name="oypo_kleuren" <?php print($oypo_settings['kleuren_custom_checked']); ?> value="custom" onClick="styleRef('sk1').display='table-row';styleRef('sk2').display='none'"><label for="rk2">Zelf aanpassen</label><br />
										<input type="radio" id="rk3" name="oypo_kleuren" <?php print($oypo_settings['kleuren_stylesheet_checked']); ?> value="stylesheet" onClick="styleRef('sk1').display='none';styleRef('sk2').display='table-row'"><label for="rk3">Eigen stylesheet gebruiken</label><br />
									</div>
								</td><td><div class="help" onClick="showHelp(this, 11)"></div></td></tr>
								
								<tr id="sk1" class="alternate" style="<?php print($oypo_settings['kleuren_custom_style']); ?>">
									<td>
										<div class="label">Achtergrond:</div>
										<div class="label">Achtergrond blokken:</div>
										<div class="label">Tekst:</div>
										<div class="label">Links/buttons:</div>
										<div class="label">Achtergrond Titelbalken:</div>
										<div class="label">Voorgrond Titelbalken:</div>
									</td>
									<td>
										<div class="label" style="width: 1px; margin-right: 0px;">&nbsp;</div><div id="v1" class="vakje" style="background-color: <?php print($oypo_settings['v1']); ?>"></div><input type="hidden" id="oypo_v1" name="oypo_v1" value="<?php print($oypo_settings['v1']); ?>" />
										<div class="label" style="width: 1px; margin-right: 0px;">&nbsp;</div><div id="v6" class="vakje" style="background-color: <?php print($oypo_settings['v6']); ?>"></div><input type="hidden" id="oypo_v6" name="oypo_v6" value="<?php print($oypo_settings['v6']); ?>" />
										<div class="label" style="width: 1px; margin-right: 0px;">&nbsp;</div><div id="v4" class="vakje" style="background-color: <?php print($oypo_settings['v4']); ?>"></div><input type="hidden" id="oypo_v4" name="oypo_v4" value="<?php print($oypo_settings['v4']); ?>" />
										<div class="label" style="width: 1px; margin-right: 0px;">&nbsp;</div><div id="v5" class="vakje" style="background-color: <?php print($oypo_settings['v5']); ?>"></div><input type="hidden" id="oypo_v5" name="oypo_v5" value="<?php print($oypo_settings['v5']); ?>" />
										<div class="label" style="width: 1px; margin-right: 0px;">&nbsp;</div><div id="v2" class="vakje" style="background-color: <?php print($oypo_settings['v2']); ?>"></div><input type="hidden" id="oypo_v2" name="oypo_v2" value="<?php print($oypo_settings['v2']); ?>" />
										<div class="label" style="width: 1px; margin-right: 0px;">&nbsp;</div><div id="v3" class="vakje" style="background-color: <?php print($oypo_settings['v3']); ?>"></div><input type="hidden" id="oypo_v3" name="oypo_v3" value="<?php print($oypo_settings['v3']); ?>" />
									</td>
									<td>
										<div class="label" style="clear: none; width: 70px;">Transparant:</div>
										<div class="option" style="width: 20px; margin-left: 5px;"><input type="checkbox" id="oypo_v1_trans" name="oypo_v1_trans" style="margin-top: 5px;" <?php print($oypo_settings['v1_trans_checked']); ?> /></div>
										<div class="help" onClick="showHelp(this, 12)"></div>
									</td>
								</tr>
								
								<tr id="sk2" class="alternate" style="<?php print($oypo_settings['kleuren_stylesheet_style']); ?>">
									<td><b>URL van de stylesheet:</b></td><td><div class="option"><input type="text" name="oypo_stylesheet" id="oypo_stylesheet" placeholder="http://" value="<?php print($oypo_settings['stylesheet']); ?>" size="70" style="width:150px"></div></td><td><div class="help" onClick="showHelp(this, 4)"></div></td>
								</tr>
							
							</table>
							
						</div>
					
						<div class="clear"></div>
					</div>
				
				</div>
								
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" name="oypo_submit" />
				
				<br /><br />
				<div class="clear"></div>
			
				<div class="widgets-holder-wrap" style="background: transparent;">
					<div class="sidebar-name"><h3>Voorbeeld</h3></div>
					<div class="widget-holder inactive">
						
						<div class="sidebar-description">
							<p class="description">Hieronder ziet u een voorbeeldintegratie op basis van het ingestelde kleurenschema.</p>
						</div>
						
						<div class="wrap" style="padding: <?php print($wrap_padding); ?>;">
							<?php oypo_generate_preview(); ?>
						</div>
					</div>
				</div>
			
			</div>
			
		<div class="clear"></div>
		<br /><br />
		
		</form>
		
		<div class="clear"></div>
		
	</div>
	
	<?php
}
