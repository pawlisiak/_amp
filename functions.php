<?php

function amp_setup() {
	// Theme supports post thumbnail
	add_theme_support('post-thumbnails');

	// Theme supports responsive images
	add_image_size('thumb-xlarge', 1200, 9999, false);
	add_image_size('thumb-large',  900,  9999, false);
	add_image_size('thumb-medium', 660,  9999, false);
	add_image_size('thumb-small',  320,  9999, false);

	// Theme supports few nav menus
	register_nav_menu('primary', __('Primary Menu', 'amp'));
}
add_action('after_setup_theme', 'amp_setup');

function amp_post_thumbnail($html, $post_id, $post_thumbnail_id, $size, $attr) {
    $id = get_post_thumbnail_id();
    $src_xlarge	= wp_get_attachment_image_src($id, 'thumb-xlarge');
    $src_large	= wp_get_attachment_image_src($id, 'thumb-large');
    $src_medium = wp_get_attachment_image_src($id, 'thumb-medium');
    $src_small	= wp_get_attachment_image_src($id, 'thumb-small');
    $alt = get_the_title($id);

    // Check to see if a 'retina' class exists in the array when calling "the_post_thumbnail()", if so output different <img/> html
    $output = '<amp-img 	src="' . $src_small[0] . '"
				srcset="' .
					$src_xlarge[0]	. ' 1200w, ' .
					$src_large[0]	. ' 900w, ' .
					$src_medium[0]	. ' 660w, ' .
					$src_small[0]	. ' 320w ' .
				'"
				alt="' . $alt . '"
				width="' . $src_small[1] . '"
				height="' . $src_small[2] . '"
				layout="responsive"></amp-img>';

    return $output;
}
add_filter('post_thumbnail_html', 'amp_post_thumbnail', 99, 5);

function amp_image($content)
{
	global $post;
	$pattern = "/<img(.*?)src=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")/";
	$replacement = '<amp-img$1src=$2$3.$4$5 layout="responsive"';
	$output = preg_replace($pattern, $replacement, $content);
	return $output;
}
add_filter('the_content', 'amp_image');

function amp_video($content)
{
	$pattern = array('<video', '</video');
	$replacement = array('<amp-video layout="responsive"', '</amp-video');
	$content = str_replace($pattern, $replacement, $content);
	return $content;
}
add_filter('the_content', 'amp_video');
