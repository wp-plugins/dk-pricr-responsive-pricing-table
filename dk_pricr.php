<?php
/**
 * Plugin Name: DK Pricr - Responsive Pricing Table
 * Plugin URI: http://wpdarko.com/darko-tools/dk-pricr/
 * Description: A responsive, easy and elegant way to present your offer to your visitors. Just create a new pricing table (custom type) and copy-paste the shortcode into your posts/pages. This plugin has been tested with WordPress 4.0. Make sure you check out all our useful themes and plugins at <a href='http://wpdarko.com/'>WPDarko.com</a>.
 * Version: 2.0
 * Author: WP Darko
 * Author URI: http://wpdarko.com/
 * License: GPL2
 */
 
 
//adds stylesheet
add_action( 'wp_enqueue_scripts', 'add_pricr_style' );
function add_pricr_style() {
	wp_enqueue_style( 'dk_pricr', plugins_url('css/dk_custom_style.css', __FILE__));
}

add_action( 'init', 'create_dk_pricing_table_type' );

function create_dk_pricing_table_type() {
  register_post_type( 'dk_pricing_table',
    array(
      'labels' => array(
        'name' => __( 'Pricing Tables' ),
        'singular_name' => __( 'Pricing Table' )
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => false,
      'supports'           => array( 'title' ),
      'menu_icon'    => plugins_url('img/dk_icon.png', __FILE__),
    )
  );
}

/*
 * Include and setup custom metaboxes and fields.
 * @category DK Pricr
 * @package  Metaboxes
 */

add_filter( 'cmb_meta_boxes', 'dkp_metaboxes' );

/*
 * Define the metabox and field configurations.
 * @param  array $meta_boxes
 * @return array
 */
function dkp_metaboxes( array $meta_boxes ) {

	//hiding fields from custom fields list
	$prefix = '_dkp_';

	//price table single metabox (built with CMB)
	$meta_boxes['tables_group'] = array(
		'id'         => 'tables_group',
		'title'      => __( 'Create / Remove your plans', 'cmb' ),
		'pages'      => array( 'dk_pricing_table', ),
		'cmb_styles' => true,
		'fields'     => array(
			array(
				'id'          => $prefix . 'plan_group',
				'type'        => 'group',
				'options'     => array(
					'group_title'   => __( 'Plan {#}', 'cmb' ),
					'add_button'    => __( 'Add Plan', 'cmb' ),
					'remove_button' => __( 'Remove Plan', 'cmb' ),
					'sortable'      => true, // beta
				),
				'fields'      => array(
					array(
						'name' => '&#8212; Plan header',
						'id'   => $prefix . 'header_desc',
						'type' => 'title',
					),
					array(
						'name' => 'Title',
						'id'   => $prefix . 'title',
						'type' => 'text',
					),
					array(
						'name' => 'Recommended?',
						'desc' => 'check this if it\'s a recommended plan',
						'id'   => $prefix . 'recommended',
						'type' => 'checkbox',
						'default' => false,
					),
					array(
						'name' => 'Subtitle',
						'id'   => $prefix . 'subtitle',
						'type' => 'text',
					),	
					array(
						'name' => 'Description',
						'id'   => $prefix . 'description',
						'type' => 'text',
					),				
					array(
						'name' => 'Price',		
						'id'   => $prefix . 'price',
						'type' => 'text',
					),
					array(
						'name' => 'Free?',
						'desc' => 'check this if this plan is free',
						'id'   => $prefix . 'free',
						'type' => 'checkbox',
						'default' => false,
					),
					array(
						'name' => 'Recurrence',
						'desc' => 'eg. "per month", "one time fee"',
						'id'   => $prefix . 'recurrence',
						'type' => 'text',
					),	
					array(
						'name' => 'Small icon',
						'desc' => 'recommended size: 30 x 30',
						'id'   => $prefix . 'icon',
						'type' => 'file',
					),
					array(
					'name' => '&#8212; Plan feature',
					'desc' => '<div style="line-height:24px; padding:10px; padding-left:30px; border-left:grey solid 4px;"><span style="font-style:normal; color:black;"><span style="color:black;"><strong>&#60;strong&#62;</strong></span> tags allowed for bold text.<br/><span style="color:#bbbbbb; font-size:12px;">eg. "&#60;strong&#62;5&#60;/strong&#62; products in the store".</span><br/>Use prefix "<span style="color:black;"><strong>-n</strong></span>" if the feature isn\'t available in this plan.<br/><span style="color:#bbbbbb; font-size:12px;">eg. "-n Custom domain name."</span></span></div>',
					'id'   => $prefix . 'features_desc',
					'type' => 'title',
					),
					array(
					    'name' => 'Features',
					    'desc' => 'one per line',
					    'id' => $prefix . 'features',
					    'type' => 'textarea',
					),
					array(
						'name' => '&#8212; Plan button',
						'id'   => $prefix . 'plan_button_desc',
						'type' => 'title',
					),
					array(
						'name' => 'Button text',
						'desc' => 'eg. "Sign up", "Buy"',
						'id'   => $prefix . 'btn_text',
						'type' => 'text',
					),	
					array(
						'name' => 'Button link',
						'desc' => 'eg. "http://anything.com"',
						'id'   => $prefix . 'btn_link',
						'type' => 'text',
					),
					array(
						'name' => '&#8212; Plan styling',
						'id'   => $prefix . 'styling_desc',
						'type' => 'title',
					),
					array(
						'name' => 'Color',
						'id'   => $prefix . 'color',
						'type' => 'colorpicker',
						'default'  => '#9fdb80',
					),	
				),
			),
		),
	);
	
	//price table single metabox (built with CMB)
	$meta_boxes['dk_settings_group'] = array(
		'id'         => $prefix . 'settings_group',
		'title'      => __( 'Pricing table settings', 'cmb' ),
		'pages'      => array( 'dk_pricing_table', ),
		'cmb_styles' => true,
		'context'	 => 'side',
		'fields'      => array(
		    array(
		    	'name' => 'Change currency',
		    	'id'   => $prefix . 'currency',
		    	'type' => 'text',
		    	'default' => '$',
		    ),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'dkp_initialize_cmb_meta_boxes', 9999 );

//metabox class
function dkp_initialize_cmb_meta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once 'cmb/init.php';
}

//shortcode columns
add_action( 'manage_dk_pricing_table_posts_custom_column' , 'custom_columns', 10, 2 );

function custom_columns( $column, $post_id ) {
    switch ( $column ) {
	case 'shortcode' :
		global $post;
		$slug = '' ;
		$slug = $post->post_name;
   
    
    	    $shortcode = '<span style="font-size:18px; line-height:40px;">[dk_pricing name="'.$slug.'"]</strong>';
	    echo $shortcode; 
	    break;
    }
}

function add_dk_pricing_table_columns($columns) {
    return array_merge($columns, 
              array('shortcode' => __('Shortcode'),
                    ));
}
add_filter('manage_dk_pricing_table_posts_columns' , 'add_dk_pricing_table_columns');


//dk_pricing shortcode
function dkp_sc($atts) {
	extract(shortcode_atts(array(
		"name" => ''
	), $atts));
	
	$output = '';
	
  query_posts( array( 'post_type' => 'dk_pricing_table', 'name' => $name, ) );
  if ( have_posts() ) : while ( have_posts() ) : the_post();

    global $post;
	$entries = get_post_meta( $post->ID, '_dkp_plan_group', true );
		
	$nb_entries = count($entries);;
	
	//opening dk_pricr
	$output .= '<div id="dk_pricr" class="dk_plans dk_'.$nb_entries.'_plans dk_style_basic">';
	
	foreach ($entries as $key => $plans) {
	
	if (!empty($plans['_dkp_recommended'])){
		$is_reco = $plans['_dkp_recommended'];
		//opening plan
		if ($is_reco == true ){
		    $reco = '<img class="dk_recommended" src="' . plugins_url('img/dk_recommended.png', __FILE__) . '"/>';
		    $reco_class = 'dk_recommended_plan';
		} else if ($is_reco == false ) {
		    $reco = '';
		    $reco_class = '';
		} 
	} else {
		$reco = '';
		$reco_class = '';
	}
	
	$output .= '<div class="dk_plan dk_plan_' . $key . ' ' . $reco_class . '">';
		
		//title
		if (!empty($plans['_dkp_title'])){
			$output .= '<div class="dk_title dk_title_' . $key . '">';
			
			if (!empty($plans['_dkp_icon'])){
				$output .= '<img height=30px width=30px src="' . $plans['_dkp_icon'] . '" class="dk_icon dk_icon_' . $key . '"/> ';
			}
			
			$output .= $plans['_dkp_title'];
			$output .= $reco . '</div>';
		}
		
		//head
		$output .= '<div class="dk_head dk_head_' . $key . '">';
		
			//recurrence
			if (!empty($plans['_dkp_recurrence'])){
			    	$output .= '<div class="dk_recurrence dk_recurrence_' . $key . '">' . $plans['_dkp_recurrence'] . '</div>';
			}
			
			//price
			if (!empty($plans['_dkp_price'])){
			    
			    $output .= '<div class="dk_price dk_price_' . $key . '">';
			    
			    if (!empty($plans['_dkp_free'])){
			    	if ($plans['_dkp_free'] == true ){
			    		$output .= __( 'Free' );
			    	} else {
				    	$output .= '<span class="dk_currency">$</span>' . $plans['_dkp_price'];
			    	}		
			    } else {
			    	
			    	$currency = get_post_meta( $post->ID, '_dkp_currency', true );
			    
			    	if (!empty($currency)){
			    		$output .= '<span class="dk_currency">';
			    		$output .= $currency;
						$output .= '</span>';
					}
			    	
			    	$output .= $plans['_dkp_price'];
			    
			    }
			    
			    $output .= '</div>';
			}
			
			//subtitle
			if (!empty($plans['_dkp_subtitle'])){
			    	$output .= '<div style="color:' . $plans['_dkp_color'] . ';" class="dk_subtitle dk_subtitle_' . $key . '">' . $plans['_dkp_subtitle'] . '</div>';
			    }
			
			//description	
			if (!empty($plans['_dkp_description'])){
			    $output .= '<div class="dk_description dk_description_' . $key . '">' . $plans['_dkp_description'] . '</div>';
			}
			
		//closing plan head
		$output .= '</div>';
		
		
		if (!empty($plans['_dkp_features'])){
			
			$output .= '<div class="dk_features dk_features_' . $key . '">';
			
			$string = $plans['_dkp_features'];
			$stringAr = explode("\n", $string);
			$stringAr = array_filter($stringAr, 'trim'); // remove any extra \r characters left behind
			
			$features = '';
			
			foreach ($stringAr as $feature) {
				$features[] .= strip_tags($feature,'<strong></strong>');
			}
			
			foreach ($features as $small_key => $feature){
				if (!empty($feature)){
					
					$check = substr($feature, 0, 2);
					if ($check == '-n') {
						$feature = substr($feature, 2);
						$check_color = '#bbbbbb';
					} else {
						$check_color = 'black';
					}	
											
					$output .= '<div style="color:' . $check_color . ';" class="dk_feature dk_feature_' . $key . '-' . $small_key . '">';
					$output .= $feature;
					$output .= '</div>';
				
					
				} 
			}
			
			$output .= '</div>';
		}
		
		if (!empty($plans['_dkp_btn_text'])){
			$btn_text =	$plans['_dkp_btn_text'];
			if (!empty($plans['_dkp_btn_link'])){
				$btn_link =	$plans['_dkp_btn_link'];
			} else { $btn_link = 'http://#'; }
		} else {
			$btn_text =	'Sign up';
			$btn_link = 'http://#';
		}
		
		//foot
		$output .= '<a href="' . $btn_link . '" style="background:' . $plans['_dkp_color'] . '" class="dk_foot dk_foot_' . $key . '">';
		
			//closing foot
			$output .= $btn_text;
		
		//closing foot
		$output .= '</a>';
		
	$output .= '</div>';
	
	}
	
	$output .= '</div>';
	
	$output .= '<div style="clear:both;"></div>';
  	
  	
  endwhile; endif; wp_reset_query(); 
	
  return $output;

}
add_shortcode("dk_pricing", "dkp_sc");

?>