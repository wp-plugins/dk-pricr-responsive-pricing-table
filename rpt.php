<?php
/**
 * Plugin Name: Responsive Pricing Table
 * Plugin URI: http://wpdarko.com/responsive-pricing-table/
 * Description: A responsive, easy and elegant way to present your offer to your visitors. Just create a new pricing table (custom type) and copy-paste the shortcode into your posts/pages. Find support and information on the <a href="http://wpdarko.com/responsive-pricing-table/">plugin's page</a>. This free version is NOT limited and does not contain any ad. Check out the <a href='http://wpdarko.com/responsive-pricing-table-pro/'>PRO version</a> for more great features.
 * Version: 3.4.2
 * Author: WP Darko
 * Author URI: http://wpdarko.com
 * License: GPL2
 */

function rpt_free_pro_check() {
    if (is_plugin_active('responsive-pricing-table-pro/rpt_pro.php')) {
        
        function my_admin_notice(){
        echo '<div class="updated">
                <p><strong>PRO</strong> version is activated.</p>
              </div>';
        }
        add_action('admin_notices', 'my_admin_notice');
        
        deactivate_plugins(__FILE__);
    }
}

add_action( 'admin_init', 'rpt_free_pro_check' );

//adds stylesheet
add_action( 'wp_enqueue_scripts', 'add_rpt_style' );
function add_rpt_style() {
	wp_enqueue_style( 'rpt', plugins_url('css/rpt_custom_style.min.css', __FILE__));
}

add_action( 'init', 'create_rpt_pricing_table_type' );

function create_rpt_pricing_table_type() {
  register_post_type( 'rpt_pricing_table',
    array(
      'labels' => array(
        'name' => 'Pricing Tables',
        'singular_name' => 'Pricing Table'
      ),
      'public' => true,
      'has_archive' => false,
      'hierarchical' => false,
      'supports'           => array( 'title' ),
      'menu_icon'    => 'dashicons-plus',
    )
  );
}

/*
 * Include and setup custom metaboxes and fields.
 * @category Responsive Pricing Table
 * @package  Metaboxes
 */

add_filter( 'dkrpt_meta_boxes', 'rpt_metaboxes' );

/*
 * Define the metabox and field configurations.
 * @param  array $meta_boxes
 * @return array
 */
function rpt_metaboxes( array $meta_boxes ) {

	//hiding fields from custom fields list
	$prefix = '_rpt_';

	//price table single metabox (built with dkrpt)
	$meta_boxes['rpt_tables_group'] = array(
		'id'         => 'tables_group',
		'title'      => 'Create / Remove your plans',
		'pages'      => array( 'rpt_pricing_table', ),
		'dkrpt_styles' => true,
		'fields'     => array(
			array(
				'id'          => $prefix . 'plan_group',
				'type'        => 'group',
				'options'     => array(
					'group_title'   => 'Plan {#}',
					'add_button'    => 'Add Plan',
					'remove_button' => 'Remove Plan',
					'sortable'      => true,
				),
				'fields'      => array(
					array(
						'name' => 'Plan header',
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
                        'sanitization_cb' => false,
					),	
					array(
						'name' => 'Description',
						'id'   => $prefix . 'description',
						'type' => 'text',
                        'sanitization_cb' => false,
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
						'id'   => $prefix . 'recurrence',
						'type' => 'text',
                        'sanitization_cb' => false,
                        'attributes'  => array(
                            'placeholder' => 'eg. per month, one time fee',
                        ),
					),	
					array(
						'name' => 'Small icon',
						'id'   => $prefix . 'icon',
						'type' => 'file',
                        'attributes'  => array(
                            'placeholder' => 'recommended size: 30 x 30',
                        ),
					),
					array(
					'name' => 'Plan features',
					'desc' => '<div style="margin-top: 20px;line-height:24px; padding:10px; padding-left:30px; border-left:grey solid 4px;"><span style="font-style:normal; color:black;"><span style="color:black;"><strong>&#60;strong&#62;</strong></span> tags allowed for bold text.<br/><span style="color:#bbbbbb; font-size:12px;">eg. "&#60;strong&#62;5&#60;/strong&#62; products in the store".</span><br/>Use prefix "<span style="color:black;"><strong>-n</strong></span>" if the feature isn\'t available in this plan.<br/><span style="color:#bbbbbb; font-size:12px;">eg. "-n Custom domain name."</span></span></div>',
					'id'   => $prefix . 'features_desc',
					'type' => 'title',
					),
					array(
					    'name' => 'Features',
					    'id' => $prefix . 'features',
					    'type' => 'textarea',
                        'attributes'  => array(
                            'placeholder' => 'one per line',
                        ),
					),
					array(
						'name' => 'Plan button',
						'id'   => $prefix . 'plan_button_desc',
						'type' => 'title',
					),
					array(
						'name' => 'Button text',
						'id'   => $prefix . 'btn_text',
						'type' => 'text',
                        'attributes'  => array(
                            'placeholder' => 'eg. Sign up, Buy',
                        ),
					),	
					array(
						'name' => 'Button link',
						'id'   => $prefix . 'btn_link',
						'type' => 'text',
                        'sanitization_cb' => false,
                        'attributes'  => array(
                            'placeholder' => 'eg. http://anything.com',
                        ),
					),
					array(
						'name' => 'Plan styling',
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
    
    function lala() {
        return "<p>This free version is <strong>NOT</strong> limited and does <strong>not</strong> contain any ad. Check out the <a style='color:rgb(97, 209, 170);' href='http://wpdarko.com/darko-tools/responsive-pricing-table-pro/'>PRO version</a> for more great features.</p>";
    }
    
    //go pro
	$meta_boxes['rpt_pro_group'] = array(
		'id'         => $prefix . 'pro_group',
		'title'      => 'Responsive Pricing Table PRO',
		'pages'      => array( 'rpt_pricing_table', ),
		'dkrpt_styles' => true,
		'context'	 => 'side',
        'priority' => 'low',
		'fields'      => array(
			array(
				'name' => '',
				'id'   => $prefix . 'pro_desc',
				'type' => 'title',
                'desc' => lala(),
			),

		),
	);
	
	//price table single metabox (built with dkrpt)
	$meta_boxes['rpt_settings_group'] = array(
		'id'         => $prefix . 'settings_group',
		'title'      => 'Pricing table settings',
		'pages'      => array( 'rpt_pricing_table', ),
		'dkrpt_styles' => true,
		'context'	 => 'side',
        'priority' => 'high',
		'fields'      => array(
			array(
				'name' => 'General settings',
				'id'   => $prefix . 'other_settings_desc',
				'type' => 'title',
			),
		    array(
		    	'name' => 'Change currency',
		    	'id'   => $prefix . 'currency',
		    	'type' => 'text',
		    ),
		    array(
				'name' => 'Links behavior',
				'id'   => $prefix . 'open_newwindow',
				'type' => 'select',
			    'options' => array(
			    	'currentwindow'   => 'Open in current window',
			        'newwindow' => 'Open in new window/tab',
			    ),
			    'default' => 'currentwindow',
			),
			array(
				'name' => 'Font sizes',
				'id'   => $prefix . 'font_sizes_desc',
				'type' => 'title',
			),
		    array(
			    'name'    => 'Title font size',
			    'id'      => $prefix . 'title_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'tiny' => 'Tiny',
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
			),
			array(
			    'name'    => 'Subtitle font size',
			    'id'      => $prefix . 'subtitle_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'tiny' => 'Tiny',
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
			),
			array(
			    'name'    => 'Description font size',
			    'id'      => $prefix . 'description_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
			),
			array(
			    'name'    => 'Price font size',
			    'id'      => $prefix . 'price_fontsize',
			    'type'    => 'select',
			    'options' => array(
                    'supertiny' => 'Tiny',
			        'tiny' => 'Small',
			        'small'   => 'Normal',
			        'normal'     => 'Big',
			    ),
			    'default' => 'normal',
			),
			array(
			    'name'    => 'Recurrence font size',
			    'id'      => $prefix . 'recurrence_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
			),
			array(
			    'name'    => 'Features font size',
			    'id'      => $prefix . 'features_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
			),
			array(
			    'name'    => 'Button font size',
			    'id'      => $prefix . 'button_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
			),
		),
	);

	return $meta_boxes;
}

add_action( 'init', 'rpt_initialize_dkrpt_meta_boxes', 9999 );

//metabox class
function rpt_initialize_dkrpt_meta_boxes() {

	if ( ! class_exists( 'dkrpt_Meta_Box' ) )
		require_once 'dkrpt/init.php';
}

//shortcode columns
add_action( 'manage_rpt_pricing_table_posts_custom_column' , 'rpt_custom_columns', 10, 2 );

function rpt_custom_columns( $column, $post_id ) {
    switch ( $column ) {
	case 'dk_shortcode' :
		global $post;
		$slug = '' ;
		$slug = $post->post_name;
   
    
    	    $shortcode = '<span style="border: solid 3px lightgray; background:white; padding:7px; font-size:17px; line-height:40px;">[rpt name="'.$slug.'"]</strong>';
	    echo $shortcode; 
	    break;
    }
}

function add_rpt_pricing_table_columns($columns) {
    return array_merge($columns, 
              array('dk_shortcode' => __('Shortcode'),
                    ));
}
add_filter('manage_rpt_pricing_table_posts_columns' , 'add_rpt_pricing_table_columns');

//rpt shortcode
function rpt_sc($atts) {
	extract(shortcode_atts(array(
		"name" => ''
	), $atts));
	
	$output = '';
	
  query_posts( array( 'post_type' => 'rpt_pricing_table', 'name' => $name, ) );
  if ( have_posts() ) : while ( have_posts() ) : the_post();

    global $post;
	$entries = get_post_meta( $post->ID, '_rpt_plan_group', true );
		
	$nb_entries = count($entries);;
	
	//get font sizes
	$title_fontsize = get_post_meta( $post->ID, '_rpt_title_fontsize', true );
	if ($title_fontsize == 'small') {
		$title_fs_class = ' rpt_sm_title';
	} else if ($title_fontsize == 'tiny') {
		$title_fs_class = ' rpt_xsm_title';
	} else {
		$title_fs_class = '';
	}
	
	$subtitle_fontsize = get_post_meta( $post->ID, '_rpt_subtitle_fontsize', true );
	if ($subtitle_fontsize == 'small') {
		$subtitle_fs_class = ' rpt_sm_subtitle';
	} else if ($subtitle_fontsize == 'tiny') {
		$subtitle_fs_class = ' rpt_xsm_subtitle';
	} else {
		$subtitle_fs_class = '';
	}
	
	$description_fontsize = get_post_meta( $post->ID, '_rpt_description_fontsize', true );
	if ($description_fontsize == 'small') {
		$description_fs_class = ' rpt_sm_description';
	} else {
		$description_fs_class = '';
	}
	
	$price_fontsize = get_post_meta( $post->ID, '_rpt_price_fontsize', true );
	if ($price_fontsize == 'small') {
		$price_fs_class = ' rpt_sm_price';
	} else if ($price_fontsize == 'tiny') {
		$price_fs_class = ' rpt_xsm_price';
    } else if ($price_fontsize == 'supertiny') {
		$price_fs_class = ' rpt_xxsm_price';
	} else {
		$price_fs_class = '';
	}
	
	$recurrence_fontsize = get_post_meta( $post->ID, '_rpt_recurrence_fontsize', true );
	if ($recurrence_fontsize == 'small') {
		$recurrence_fs_class = ' rpt_sm_recurrence';
	} else {
		$recurrence_fs_class = '';
	}
	
	$features_fontsize = get_post_meta( $post->ID, '_rpt_features_fontsize', true );
	if ($features_fontsize == 'small') {
		$features_fs_class = ' rpt_sm_features';
	} else {
		$features_fs_class = '';
	}
	
	$button_fontsize = get_post_meta( $post->ID, '_rpt_button_fontsize', true );
	if ($button_fontsize == 'small') {
		$button_fs_class = ' rpt_sm_button';
	} else {
		$button_fs_class = '';
	}
	
	//opening rpt_pricr container
	$output .= '<div id="rpt_pricr" class="rpt_plans rpt_'.$nb_entries .'_plans rpt_style_basic">';
	
	//opening rpt_pricr inner
	$output .= '<div class="'. $title_fs_class . $subtitle_fs_class . $description_fs_class . $price_fs_class . $recurrence_fs_class . $features_fs_class. $button_fs_class .'">';
	
	foreach ($entries as $key => $plans) {
	
	if (!empty($plans['_rpt_recommended'])){
		$is_reco = $plans['_rpt_recommended'];
		//opening plan
		if ($is_reco == true ){
		    $reco = '<img class="rpt_recommended" src="' . plugins_url('img/rpt_recommended.png', __FILE__) . '"/>';
		    $reco_class = 'rpt_recommended_plan';
		} else if ($is_reco == false ) {
		    $reco = '';
		    $reco_class = '';
		} 
	} else {
		$reco = '';
		$reco_class = '';
	}
	
	$output .= '<div class="rpt_plan rpt_plan_' . $key . ' ' . $reco_class . '">';
		
		//title
		if (!empty($plans['_rpt_title'])){
			$output .= '<div class="rpt_title rpt_title_' . $key . '">';
			
			if (!empty($plans['_rpt_icon'])){
				$output .= '<img height=30px width=30px src="' . $plans['_rpt_icon'] . '" class="rpt_icon rpt_icon_' . $key . '"/> ';
			}
			
			$output .= $plans['_rpt_title'];
			$output .= $reco . '</div>';
		}
		
		//head
		$output .= '<div class="rpt_head rpt_head_' . $key . '">';
		
			//recurrence
			if (!empty($plans['_rpt_recurrence'])){
			    	$output .= '<div class="rpt_recurrence rpt_recurrence_' . $key . '">' . $plans['_rpt_recurrence'] . '</div>';
			}
			
			//price
			if (!empty($plans['_rpt_price'])){
			    
			    $output .= '<div class="rpt_price rpt_price_' . $key . '">';
			    
			    if (!empty($plans['_rpt_free'])){
			    	if ($plans['_rpt_free'] == true ){
			    		$output .= __( 'Free' );
			    	} else {
				    	$output .= '<span class="rpt_currency"></span>' . $plans['_rpt_price'];
			    	}		
			    } else {
			    	
			    	$currency = get_post_meta( $post->ID, '_rpt_currency', true );
			    
			    	if (!empty($currency)){
			    		$output .= '<span class="rpt_currency">';
			    		$output .= $currency;
						$output .= '</span>';
					}
			    	
			    	$output .= $plans['_rpt_price'];
			    
			    }
			    
			    $output .= '</div>';
			}
			
			//subtitle
			if (!empty($plans['_rpt_subtitle'])){
			    	$output .= '<div style="color:' . $plans['_rpt_color'] . ';" class="rpt_subtitle rpt_subtitle_' . $key . '">' . $plans['_rpt_subtitle'] . '</div>';
			    }
			
			//description	
			if (!empty($plans['_rpt_description'])){
			    $output .= '<div class="rpt_description rpt_description_' . $key . '">' . $plans['_rpt_description'] . '</div>';
			}
			
		//closing plan head
		$output .= '</div>';
		
		
		if (!empty($plans['_rpt_features'])){
			

            $output .= '<div class="rpt_features rpt_features_' . $key . '">';

			
			$string = $plans['_rpt_features'];
			$stringAr = explode("\n", $string);
			$stringAr = array_filter($stringAr, 'trim'); // remove any extra \r characters left behind
			
			$features = '';
			
			foreach ($stringAr as $feature) {
				$features[] .= strip_tags($feature,'<strong></strong><br><br/></br>');
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
											
					$output .= '<div style="color:' . $check_color . ';" class="rpt_feature rpt_feature_' . $key . '-' . $small_key . '">';
					$output .= $feature;
					$output .= '</div>';
							
				} 
			}
			
			$output .= '</div>';
		}
		
		if (!empty($plans['_rpt_btn_text'])){
			$btn_text =	$plans['_rpt_btn_text'];
			if (!empty($plans['_rpt_btn_link'])){
				$btn_link =	$plans['_rpt_btn_link'];
			} else { $btn_link = '#'; }
		} else {
			$btn_text =	'';
			$btn_link = '#';
		}
		
		//link option
		$newcurrentwindow = get_post_meta( $post->ID, '_rpt_open_newwindow', true );
		if ($newcurrentwindow == 'newwindow'){
			$link_behavior = 'target="_blank"';
		} else {
			$link_behavior = 'target="_self"';
		}
		
		//foot
        if (!empty($plans['_rpt_btn_text'])){
		  $output .= '<a '. $link_behavior .' href="' . $btn_link . '" style="background:' . $plans['_rpt_color'] . '" class="rpt_foot rpt_foot_' . $key . '">';
        } else {
          $output .= '<a '. $link_behavior .' style="background:' . $plans['_rpt_color'] . '" class="rpt_foot rpt_foot_' . $key . '">';
        }
		
			//closing foot
			$output .= $btn_text;
		
		//closing foot
		$output .= '</a>';
		
	$output .= '</div>';
	
	}
	
	//closing rpt_inner
	$output .= '</div>';
	
	//closing rpt_container
	$output .= '</div>';
	
	$output .= '<div style="clear:both;"></div>';
  	
  	
  endwhile; endif; wp_reset_query(); 
	
  return $output;

}
add_shortcode("rpt", "rpt_sc");

?>