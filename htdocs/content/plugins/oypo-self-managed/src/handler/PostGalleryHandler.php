<?php
/**
 *
 * PostGalleryHandler
 *
 * @package: Dstollie\Oypo\Handler
 * @author: Dennis Stolmeijer <zakelijk@dennisstolemeijer.nl>
 *
 * Created at: 22-9-2015 at 22:32
 *
 */

namespace Dstollie\Oypo\Handler;


use duncan3dc\Laravel\Blade;

class PostGalleryHandler
{

	private $attr;
	private static $selector = "oypo-gallery";

	public function __construct($attr)
	{
		$this->attr = $attr;
	}

	public function renderData()
	{
		global $post;

		$data = [
			'selector' => self::$selector,
			'profielId' => "DA358423DA5FF67C"
		];

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if (isset($this->attr['orderby'])) {
			$this->attr['orderby'] = sanitize_sql_orderby($this->attr['orderby']);
			if (!$this->attr['orderby'])
				unset($this->attr['orderby']);
		}

		$atts = $data['atts'] = shortcode_atts(array(
			'order' => 'ASC',
			'orderby' => 'menu_order ID',
			'id' => $post->ID,
			'itemtag' => 'dl',
			'icontag' => 'dt',
			'captiontag' => 'dd',
			'columns' => 3,
			'size' => 'thumbnail',
			'include' => '',
			'exclude' => ''
		), $this->attr);

		$data = array_merge($atts, $data);

		$id = intval($data['id']);

		if ('RAND' == $data['order'])
			$data['orderby'] = 'none';

		$postDefaultArgs = [
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => $data['order'],
			'orderby' => $data['orderby']
		];

		if (!empty($data['include'])) {
			$include = preg_replace('/[^0-9,]+/', '', $data['include']);
			$_attachments = get_posts(array_merge($postDefaultArgs, ['include' => $include]));

			$attachments = array();
			foreach ($_attachments as $key => $val) {
				$attachments[$val->ID] = $_attachments[$key];
			}

		} elseif (!empty($data['exclude'])) {
			$exclude = preg_replace('/[^0-9,]+/', '', $data['exclude']);
			$attachments = get_children(array_merge($postDefaultArgs, [
				'post_parent' => $id,
				'exclude' => $exclude
			]));
		} else {
			$attachments = get_children(array_merge($postDefaultArgs, [
				'post_parent' => $id
			]));
		}

//		if (empty($attachments))
//			return '';

//		if (is_feed()) {
//			$output = "\n";
//			foreach ($attachments as $att_id => $attachment)
//				$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
//			return $output;
//		}

		$data['itemtag'] = tag_escape($data['itemtag']);
		$data['captiontag'] = tag_escape($data['captiontag']);
		$columns = $data['columns'] = intval($data['columns']);
		$data['itemwidth'] = ($columns > 0 ? floor(100 / $columns) : 1000) . "%";
		$data['float'] = is_rtl() ? 'right' : 'left';

		$data['attachments'] = [];

		// Now we are going to iterate over all the gallery items
		$i = 0;
		foreach ($attachments as $id => $attachment) {

			$attachmentData = [];

			$permalink = isset($attr['link']) && 'file' == $attr['link'] ? false : true;

			$attachmentData['link'] = wp_get_attachment_link($id, $data['size'], $permalink, false);
			$attachmentData['highResPhoto'] = wp_get_attachment_url($id);
			$attachmentData['thumbnailPhoto'] = wp_get_attachment_thumb_url($id);
			$attachmentData['thumbnailPhoto'] = "http://www.kijk.us/00973968-thumb.jpg";
			$attachmentData['postExcerpt'] = trim($attachment->post_excerpt) ? wptexturize($attachment->post_excerpt) : null;

			$attachmentData['showBr'] = false;
			if ($columns > 0 && ++$i % $columns == 0)
				$attachmentData['showBr'] = true;

			$data['attachments'][] = $attachmentData;
		}

		return $data;
	}

	public function getContent()
	{
		return Blade::render("post_gallery", $this->renderData());
	}

}