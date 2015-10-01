<?php
/**
 *
 * bootstrap
 *
 * @package: Dstollie\Oypo
 * @author: Dennis Stolmeijer <zakelijk@dennisstolemeijer.nl>
 *
 * Created at: 21-9-2015 at 23:51
 *
 */

namespace Dstollie\Oypo;

use Dstollie\Oypo\Handler\BeforeUploadHandler;
use Dstollie\Oypo\Handler\EditPostSettingsHandler;
use Dstollie\Oypo\Handler\PostGalleryHandler;
use duncan3dc\Helpers\Env;
use duncan3dc\Laravel\Blade;
use duncan3dc\Laravel\BladeInstance;

class Bootstrap
{

	public static $plugin_path;
	public static $restricted_photo_folder = "secret";

	function __construct($plugin_path)
	{
		self::$plugin_path = $plugin_path;
		self::$restricted_photo_folder = $plugin_path . DIRECTORY_SEPARATOR . self::$restricted_photo_folder;

		$views = $plugin_path  . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR .  'views';
		$cache = $plugin_path . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'cache';

		$bladeInstance = new BladeInstance($views, $cache);
		Blade::setInstance($bladeInstance);

		$this->register_filters();
		$this->register_scripts();

		if ( is_admin() ) {
			new EditPostSettingsHandler();
		}
	}

	private function register_scripts()
	{
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_individual_scripts']);
	}

	public function enqueue_individual_scripts()
	{
		$oypo_script_url = 'http://www.oypo.nl/pixxer/api/pixxerinit.asp?
		buttonadd=http://www.kijk.us/selectionadd.gif&
		buttondel=http://www.kijk.us/selectiondel.gif';

		wp_enqueue_script('oypo-initalization-script', $oypo_script_url, array(), '1.0.0', true);
	}

	private function register_filters()
	{
//		add_filter('wp_handle_upload_prefilter', [$this, 'before_upload']);
		add_filter('post_gallery', [$this, 'post_gallery'], 10, 3);
	}

	/**
	 * Triggered when an file is being uploaded
	 *
	 * @param $file
	 */
	public function before_upload($file)
	{
		$beforeUploadHandler = new BeforeUploadHandler();
		$beforeUploadHandler
			->setFile($file)
			->copyToRestrictedAreaFolder();

		return $file;
	}

	function post_gallery($output = '', $attr, $instance)
	{
		$galleryHandler = new PostGalleryHandler($attr);
		return $galleryHandler->getContent();
	}
}
