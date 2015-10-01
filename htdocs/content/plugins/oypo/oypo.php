<?php
/**
 * @package Oypo
 */
/*
Plugin Name: Oypo
Plugin URI: http://www.oypo.nl
Description: Met deze plugin kunt u eenvoudig de fotomappen van uw Oypo-account op uw Wordpress site laten zien. Op de instellingen-pagina van de plugin vult u uw Oypo-gebruikersnaam in en stelt u de gewenste kleurinstellingen in. Bij het wijzigen van een pagina ziet u onder het tekstkader nu een Oypo Fotowinkel kader waarmee u een gewenste fotomap kunt toevoegen aan de pagina.
Version: 1.0.3
Author: Oypo
Author URI: http://www.oypo.nl/
License: GPLv2 or later
Copyright 2013 Oypo  (email : support@oypo.nl)


*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define('OYPO_PLUGIN_URL', plugin_dir_url( __FILE__ ));

// CONFIG
require_once dirname( __FILE__ ) . '/config.php';

// ADMIN FUNCTIONS
if ( is_admin() ){ require_once dirname( __FILE__ ) . '/admin.php'; }

// EDIT POSTS FUNCTIONS
require_once dirname( __FILE__ ) . '/posts.php';