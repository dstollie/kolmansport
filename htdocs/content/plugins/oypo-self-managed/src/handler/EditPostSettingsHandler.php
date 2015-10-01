<?php
/**
 *
 * EditPostSettingsHandler
 *
 * @package: Dstollie\Oypo\Handler
 * @author: Dennis Stolmeijer <zakelijk@dennisstolemeijer.nl>
 *
 * Created at: 23-9-2015 at 21:35
 *
 */

namespace Dstollie\Oypo\Handler;


use duncan3dc\Laravel\Blade;
use WP_Post;

class EditPostSettingsHandler
{

	private $nonce_field_name;

	private $oypo_enabled_field_name;

	private $register_post_types = ['post', 'page'];

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct()
	{
		$this->nonce_field_name = prefix('inner_box_nonce');
		$this->oypo_enabled_field_name = prefix('enabled');

		// Register the action hooks
		add_action('add_meta_boxes', array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save'));
	}

	/**
	 * Adds the meta box container.
	 *
	 * @param string $post_type
	 *
	 */
	public function add_meta_box($post_type)
	{
		if (in_array($post_type, $this->register_post_types)) {
			add_meta_box(
				'oypo_self_managed',
				__('Oypo Self Managed Plugin', prefix('textdomain')),
				array($this, 'render_meta_box_content'),
				$post_type,
				'advanced',
				'high'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @return int
	 */
	public function save($post_id)
	{
		if(!$this->nonce_is_valid())
			return $post_id;

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}

		$post_type = $_POST['post_type'];

		if (!in_array($post_type, $this->register_post_types)) {
			return $post_id;
		}

		// If the use is not allowed to edit this post type, dont allow him to change the oypo settings
		if(!current_user_can('edit_' . $post_type, $post_id)) {
			return $post_id;
		}

		/* OK, its safe for us to save the data now. */

		// Sanitize the user input.
		$data = boolval(sanitize_text_field($_POST[$this->oypo_enabled_field_name]));

		// Update the meta field.
		update_post_meta($post_id, $this->oypo_enabled_field_name, $data);
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content($post)
	{
		// Use get_post_meta to retrieve an existing value from the database.
		$enabled = esc_attr(get_post_meta($post->ID, $this->oypo_enabled_field_name, true));

		echo Blade::render("edit_post_settings",  [
//			'description' => __('Description for this field', prefix('textdomain')),
			'enabled' => $enabled,
			'oypo_enabled_field_name' => $this->oypo_enabled_field_name,
			'nonce_field_name' => $this->nonce_field_name
		]);
	}

	private function nonce_is_valid() {
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if (!isset($_POST[$this->nonce_field_name]))
			return false;

		$nonce = $_POST[$this->nonce_field_name];

		// Verify that the nonce is valid.
		if (!wp_verify_nonce($nonce, $this->nonce_field_name))
			return false;

		return true;
	}
}
