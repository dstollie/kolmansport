<?php
/*
Plugin Name: Oypo-self-managed
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: oypo-self-managed
Domain Path: /languages
*/

require_once( 'vendor/autoload.php' );

const PLUGIN_PREFIX = "oypo_self_managed";

function prefix($value) {
	return PLUGIN_PREFIX . "_" . $value;
}

if(!function_exists('mdd')) {
	function mdd($value)
	{
		echo "</pre>";
		print_r($value);
		exit;
	}
}

new Dstollie\Oypo\Bootstrap(__DIR__);
