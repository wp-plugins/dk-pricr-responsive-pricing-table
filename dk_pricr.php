<?php
/**
 * Plugin Name: DK Pricr - Responsive Pricing Table
 * Plugin URI: http://wpdarko.com/darko-tools/dk-pricr/
 * Description: A responsive, easy and elegant way to present your offer to your visitors. Find support and [shortcodes] explanation on the <a href='http://wpdarko.com/darko-tools/dk-pricr/'>Plugin's site</a>. This plugin has been tested with WordPress 4.0. Make sure you check out all our useful themes and plugins at <a href='http://wpdarko.com/'>WPDarko.com</a>.
 * Version: 1.0
 * Author: WP Darko
 * Author URI: http://wpdarko.com/
 * License: GPL2
 */

//adds stylesheet
add_action( 'wp_enqueue_scripts', 'add_pricr_style' );
function add_pricr_style() {
	wp_enqueue_style( 'dk_pricr', plugins_url('css/dk_pricr.css', __FILE__));
}

//adds dk_pricr shortcode
add_shortcode( 'dk_pricr', 'dk_pricr_shortcode' );
function dk_pricr_shortcode( $atts , $content = null ) {

	$a = shortcode_atts( array(
		'plans' => 3,
	), $atts );
	
	return "<div id='dk_pricr' class='dk_". esc_attr($a['plans']) ."_plans'>". do_shortcode($content) ."<div style='clear:both;'></div></div>";
}

//adds dk_plan shortcode
add_shortcode( 'dk_plan', 'dk_plan_shortcode' );
function dk_plan_shortcode( $atts, $content = null ) {

	$a = shortcode_atts( array(
		'title' => '',
		'sub_title' => '',
		'price' => '',
		'button_url' => '#',
		'button_text' => '',
		'color' => 'grey',
	), $atts );

	return "
	<div class='dk_plan'>
		<div class='dk_title'>
			<h4>" . strip_tags($a['title'], '<strong></strong>') . "<br/><div class='dk_subtitle'>" . strip_tags($a['sub_title'], '<strong></strong>') . "</div></h4>
		    <div class='dk_price' style='color:". esc_attr($a['color']) .";'>" . esc_attr($a['price']) . "</div>
		</div>
		<div class='dk_features'>". do_shortcode($content) ."</div>
		<div class='dk_link'>
			<a style='background:". esc_attr($a['color']) .";' href='". esc_attr($a['button_url']) ."'>". strip_tags($a['button_text'], '<strong></strong>') ."</a></div></div>";
}

//adds feature shortcode
add_shortcode( 'dk_ft', 'dk_ft_shortcode' );
function dk_ft_shortcode( $atts ) {

	$a = shortcode_atts( array(
		'name' => '&nbsp;',
		'available' => 'yes',
	), $atts );

	$is_available = esc_attr($a['available']);
	if ($is_available == 'no' || $is_available == 'No') {
		$ava = 'dk_disable';
	} else {$ava = '';}
	
	return "<div class='dk_feature ".$ava."'><div>". strip_tags($a['name'], '<strong>') ."</div></div>";
}?>