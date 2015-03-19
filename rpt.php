<?php
/*
Plugin Name: Responsive Pricing Table
Plugin URI: http://wpdarko.com/support/documentation/get-started-responsive-pricing-table/
Description: A responsive, easy and elegant way to present your offer to your visitors. Just create a new pricing table (custom type) and copy-paste the shortcode into your posts/pages. Find support and information on the <a href="http://wpdarko.com/responsive-pricing-table/">plugin's page</a>. This free version is NOT limited and does not contain any ad. Check out the <a href='http://wpdarko.com/items/responsive-pricing-table-pro/'>PRO version</a> for more great features.
Version: 4.0
Author: WP Darko
Author URI: http://wpdarko.com
License: GPL2
 */

// Check for the PRO version
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

/* Enqueue styles */
function add_rpt_scripts() {
	wp_enqueue_style( 'rpt', plugins_url('css/rpt_style.min.css', __FILE__));
}

add_action( 'wp_enqueue_scripts', 'add_rpt_scripts', 99 );

/* Enqueue admin styles */
add_action( 'admin_enqueue_scripts', 'add_admin_rpt_style' );

function add_admin_rpt_style() {
	wp_enqueue_style( 'rpt', plugins_url('css/admin_de_style.min.css', __FILE__));
}

// Create Pricing Table custom type
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

add_action( 'init', 'create_rpt_pricing_table_type' );

/* Hide View/Preview since it's a shortcode */
function rpt_pricing_table_admin_css() {
    global $post_type;
    $post_types = array( 
                        'rpt_pricing_table',
                  );
    if(in_array($post_type, $post_types))
    echo '<style type="text/css">#post-preview, #view-post-btn{display: none;}</style>';
}

function remove_view_link_rpt_pricing_table( $action ) {

    unset ($action['view']);
    return $action;
}

add_filter( 'post_row_actions', 'remove_view_link_rpt_pricing_table' );
add_action( 'admin_head-post-new.php', 'rpt_pricing_table_admin_css' );
add_action( 'admin_head-post.php', 'rpt_pricing_table_admin_css' );

// Adding the CMB2 Metabox class
if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
    require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

// Registering Pricing Table metaboxes
function rpt_register_plan_group_metabox() {
    
    $plan_skeleton_url = plugins_url('dk-pricr-responsive-pricing-table/img/skeleton_plan.png');
    $prefix = '_rpt_';
   
    // Tables group
    $main_group = new_cmb2_box( array(
        'id' => $prefix . 'plan_metabox',
        'title' => '<span class="dashicons dashicons-welcome-add-page"></span> Pricing Table Plans',
        'object_types' => array( 'rpt_pricing_table' ),
    ));

        $rpt_plan_group = $main_group->add_field( array(
            'id' => $prefix . 'plan_group',
            'type' => 'group',
            'options' => array(
                'group_title' => 'Plan {#}',
                'add_button' => 'Add another plan',
                'remove_button' => 'Remove plan',
                'sortable' => true,
            ),
        ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => 'Plan Header',
                'id' => $prefix . 'head_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));

            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-edit"></span> Title',
                'id' => $prefix . 'title',
                'type' => 'text',
                'row_classes' => 'de_first de_fifty de_text de_input',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-edit"></span> Subtitle',
                'id'   => $prefix . 'subtitle',
                'type' => 'text',
                'sanitization_cb' => false,
                'row_classes' => 'de_fifty de_text de_input',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-edit"></span> Description',
                'id'   => $prefix . 'description',
                'type' => 'text',
                'sanitization_cb' => false,
                'row_classes' => 'de_first de_fifty de_text de_input',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-edit"></span> Price',		
	       	    'id'   => $prefix . 'price',
                'type' => 'text',
                'row_classes' => 'de_twentyfive de_text de_input',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-edit"></span> Recurrence',
                'id'   => $prefix . 'recurrence',
                'type' => 'text',
                'sanitization_cb' => false,
                'row_classes' => 'de_twentyfive de_text de_input',
                'attributes'  => array(
                    'placeholder' => 'eg. per month',
                ),
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-format-image"></span> Small icon',
                'id'   => $prefix . 'icon',
                'type' => 'file',
                'attributes'  => array(
                    'placeholder' => 'recommended size: 30 x 30',
                ),
                'row_classes' => 'de_hundred de_upload de_input',
            ));
            
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-admin-post"></span> Mark as a RECOMMENDED plan',
                'desc' => 'Checking this will highlight the plan (eg. best deal).',
                'id' => $prefix . 'recommended',
                'type' => 'checkbox',
                'default' => false,
                'row_classes' => 'de_first de_fifty de_checkbox',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-admin-post"></span> Remove currency sign',
				'desc' => 'Checking this will remove the currency sign (eg. free plans).',
				'id'   => $prefix . 'free',
				'type' => 'checkbox',
				'default' => false,
                'row_classes' => 'de_fifty de_checkbox',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => 'Plan Features',
                'id' => $prefix . 'features_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-edit"></span> Feature list',
				'id' => $prefix . 'features',
				'type' => 'textarea',
                'attributes'  => array(
                    'placeholder' => 'one per line',
                    'rows' => 10,
                ),
                'row_classes' => 'de_first de_fifty de_textarea de_input',
            ));
            
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => 'Tips & Tricks',
                'desc' => '<span class="dashicons dashicons-yes"></span> Add images (not recommended)<br/><span style="color:#bbb;">&lt;img src="http://yoursite.com/yourimage.png"/&gt;</span><br/><br/><span class="dashicons dashicons-yes"></span> Add links<br/><span style="color:#bbb;">&lt;a href="http://yoursite.com"&gt;Go to yoursite.com&lt;/a&gt;</span><br/><br/><span class="dashicons dashicons-yes"></span> Add bold text<br/><span style="color:#bbb;">&lt;strong&gt;Something <strong>important</strong>&lt;/strong&gt;</span><br/><br/><span style="color:#8a7463;"><span class="dashicons dashicons-lock"></span> PRO Add Tooltips<br/>Tooltips are info bubbles for your features.</span>',
                'id'   => $prefix . 'features_desc',
                'type' => 'title',
                'row_classes' => 'de_fifty de_info',
            ));
            
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => 'Plan Button',
                'id' => $prefix . 'button_header',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-edit"></span> Button text',
                'id'   => $prefix . 'btn_text',
                'type' => 'text',
                'attributes'  => array(
                    'placeholder' => 'eg. Sign up, Buy',
                ),
                'row_classes' => 'de_first de_fifty de_text de_input',
            ));
            
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '<span class="dashicons dashicons-admin-links"></span> Button link',
	               'id'   => $prefix . 'btn_link',
	               'type' => 'text',
                'sanitization_cb' => false,
                'attributes'  => array(
                    'placeholder' => 'eg. http://anything.com',
                ),
                'row_classes' => 'de_fifty de_text de_input',
            ));
            
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => 'Plan styling',
                'id'   => $prefix . 'styling_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_heading',
            ));
            
            $main_group->add_group_field( $rpt_plan_group, array(
               'name' => '<span class="dashicons dashicons-admin-appearance"></span> Color',
               'desc' => 'This color will be used for several elements of the plan.',
	           'id'   => $prefix . 'color',
	           'type' => 'colorpicker',
	           'default'  => '#9fdb80',
               'row_classes' => 'de_hundred de_color',
            ));
    
            $main_group->add_group_field( $rpt_plan_group, array(
                'name' => '',
                'id'   => $prefix . 'sep_header',
                'type' => 'title',
                'row_classes' => 'de_hundred',
            ));
    
    // Settings group
    $side_group = new_cmb2_box( array(
        'id' => $prefix . 'settings_metabox',
        'title' => '<span class="dashicons dashicons-admin-tools"></span> Pricing Table Settings',
        'object_types' => array( 'rpt_pricing_table' ),
        'context' => 'side',
        'priority' => 'high',
        'closed' => true,
    ));
        
        $side_group->add_field( array(
            'name' => 'General settings',
            'id'   => $prefix . 'other_settings_desc',
            'type' => 'title',
            'row_classes' => 'de_hundred_side de_heading',
        ));
    
        $side_group->add_field( array(
            'name' => '<span class="dashicons dashicons-edit"></span> Change currency',
		    'id'   => $prefix . 'currency',
		    'type' => 'text',
            'row_classes' => 'de_hundred_side de_text_side de_input',
        ));
    
        $side_group->add_field( array(
            'name'    => '<span style="color:#8a7463;"><span class="dashicons dashicons-lock"></span> PRO Select table skin</span>',
			'id'      => $prefix . 'skin',
			'type'    => 'select',
			'options' => array(
			    'basic' => 'Skins are new table designs',
			),
			'default' => 'basic',
            'row_classes' => 'de_hundred_side de_text_side',
        ));
    
        $side_group->add_field( array(
            'name' => '<span class="dashicons dashicons-arrow-down"></span> Links behavior',
			'id'   => $prefix . 'open_newwindow',
			'type' => 'select',
			'options' => array(
				'currentwindow'   => 'Open in current window',
			    'newwindow' => 'Open in new window/tab',
			),
			'default' => 'currentwindow',
            'row_classes' => 'de_hundred_side de_text_side',
        ));
    
        $side_group->add_field( array(
            'name' => '<span class="dashicons dashicons-admin-generic"></span> Force original fonts',
            'desc' => 'By default this plugin will use your theme\'s font, check this to force the use of the plugin\'s original fonts.',
		    'id'   => $prefix . 'original_font',
		    'type' => 'checkbox',
            'row_classes' => 'de_hundred_side de_checkbox_side',
            'default' => false,
        ));    
    
        $side_group->add_field( array(
				'name' => 'Font sizes',
				'id'   => $prefix . 'font_sizes_desc',
				'type' => 'title',
                'row_classes' => 'de_hundred_side de_heading',
        ));
    
        $side_group->add_field( array(
			    'name'    => '<span class="dashicons dashicons-arrow-down"></span> Title',
			    'id'      => $prefix . 'title_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'tiny' => 'Tiny',
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
                'row_classes' => 'de_hundred_side de_text_side',
        ));
    
        $side_group->add_field( array(
			    'name'    => '<span class="dashicons dashicons-arrow-down"></span> Subtitle',
			    'id'      => $prefix . 'subtitle_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'tiny' => 'Tiny',
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
                'row_classes' => 'de_hundred_side de_text_side',
        ));
    
        $side_group->add_field( array(
			    'name'    => '<span class="dashicons dashicons-arrow-down"></span> Description',
			    'id'      => $prefix . 'description_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
                'row_classes' => 'de_hundred_side de_text_side',
        ));
    
        $side_group->add_field( array(
			    'name'    => '<span class="dashicons dashicons-arrow-down"></span> Price',
			    'id'      => $prefix . 'price_fontsize',
			    'type'    => 'select',
			    'options' => array(
                    'supertiny' => 'Tiny',
			        'tiny' => 'Small',
			        'small'   => 'Normal',
			        'normal'     => 'Big',
			    ),
			    'default' => 'normal',
                'row_classes' => 'de_hundred_side de_text_side',
        ));
    
        $side_group->add_field( array(
			    'name'    => '<span class="dashicons dashicons-arrow-down"></span> Recurrence',
			    'id'      => $prefix . 'recurrence_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
                'row_classes' => 'de_hundred_side de_text_side',
        ));
    
        $side_group->add_field( array(
			    'name'    => '<span class="dashicons dashicons-arrow-down"></span> Button',
			    'id'      => $prefix . 'button_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
                'row_classes' => 'de_hundred_side de_text_side',
        ));
            
        $side_group->add_field( array(
			    'name'    => '<span class="dashicons dashicons-arrow-down"></span> Features',
			    'id'      => $prefix . 'features_fontsize',
			    'type'    => 'select',
			    'options' => array(
			        'small'   => 'Small',
			        'normal'     => 'Normal',
			    ),
			    'default' => 'normal',
                'row_classes' => 'de_hundred_side de_text_side',
        ));
    
    // Help group
    $help_group = new_cmb2_box( array(
        'id' => $prefix . 'help_metabox',
        'title' => '<span class="dashicons dashicons-sos"></span> Help & Support',
        'object_types' => array( 'rpt_pricing_table' ),
        'context' => 'side',
        'priority' => 'high',
        'closed' => true,
        'row_classes' => 'de_hundred de_heading',
    ));
    
        $help_group->add_field( array(
            'name' => '',
                'desc' => 'Plan\'s skeleton<br/><img src="'.$plan_skeleton_url.'"/><br/>Find help at WPdarko.com<br/><br/><a target="_blank" href="http://wpdarko.com/support/forum/plugins/responsive-pricing-table/"><span class="dashicons dashicons-arrow-right-alt2"></span> Support forum</a><br/><a target="_blank" href="http://wpdarko.com/support/documentation/get-started-responsive-pricing-table/"><span class="dashicons dashicons-arrow-right-alt2"></span> Documentation</a>',
                'id'   => $prefix . 'help_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_info de_info_side',
        ));
    
    // PRO group
    $pro_group = new_cmb2_box( array(
        'id' => $prefix . 'pro_metabox',
        'title' => '<span class="dashicons dashicons-awards"></span> PRO version',
        'object_types' => array( 'rpt_pricing_table' ),
        'context' => 'side',
        'priority' => 'high',
        'closed' => true,
        'row_classes' => 'de_hundred de_heading',
    ));
    
        $pro_group->add_field( array(
            'name' => '',
                'desc' => 'This free version is <strong>not</strong> limited and does <strong>not</strong> contain any ad. Check out the PRO version for more great features.<br/><br/><a target="_blank" href="http://wpdarko.com/items/responsive-pricing-table-pro"><span class="dashicons dashicons-arrow-right-alt2"></span> See plugin\'s page</a>',
                'id'   => $prefix . 'pro_desc',
                'type' => 'title',
                'row_classes' => 'de_hundred de_info de_info_side',
        ));
}

add_action( 'cmb2_init', 'rpt_register_plan_group_metabox' );

// Shortcode column
function rpt_custom_columns( $column, $post_id ) {
    switch ( $column ) {
	case 'dk_shortcode' :
		global $post;
		$slug = '' ;
		$slug = $post->post_name;
        $shortcode = '<span style="border: solid 3px lightgray; background:white; padding:2px 7px 5px; font-size:18px; line-height:40px;">[rpt name="'.$slug.'"]</strong>';
	    echo $shortcode; 
	    break;
    }
}

add_action( 'manage_rpt_pricing_table_posts_custom_column' , 'rpt_custom_columns', 10, 2 );

function add_rpt_pricing_table_columns($columns) {
    return array_merge($columns, array('dk_shortcode' => __('Shortcode'),));
}

add_filter('manage_rpt_pricing_table_posts_columns' , 'add_rpt_pricing_table_columns');

// Display Shortcode
function rpt_sc($atts) {
	extract(shortcode_atts(array(
		"name" => ''
	), $atts));
	$output2 = '';

global $post;    
    
$args = array('post_type' => 'rpt_pricing_table', 'name' => $name); 
$custom_posts = get_posts($args);
foreach($custom_posts as $post) : setup_postdata($post);    

	$entries = get_post_meta( $post->ID, '_rpt_plan_group', true );
		
	$nb_entries = count($entries);;
    
    // Forcing original fonts?
    $original_font = get_post_meta( $post->ID, '_rpt_original_font', true );
    if ($original_font == true){
        $ori_f = 'rpt_plan_ori';
    } else {
        $ori_f = '';
    }
	
	// Get font sizes
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
	
	// Opening rpt_pricr container
	$output2 .= '<div id="rpt_pricr" class="rpt_plans rpt_'.$nb_entries .'_plans rpt_style_basic">';
	
	// Opening rpt_pricr inner
	$output2 .= '<div class="'. $title_fs_class . $subtitle_fs_class . $description_fs_class . $price_fs_class . $recurrence_fs_class . $features_fs_class. $button_fs_class .'">';
	
	foreach ($entries as $key => $plans) {
	
	if (!empty($plans['_rpt_recommended'])){
		$is_reco = $plans['_rpt_recommended'];
        
		//Opening plan
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
	
	$output2 .= '<div class="rpt_plan  '.$ori_f.' rpt_plan_' . $key . ' ' . $reco_class . '">';
		
		// Title
		if (!empty($plans['_rpt_title'])){
			$output2 .= '<div class="rpt_title rpt_title_' . $key . '">';
			
			if (!empty($plans['_rpt_icon'])){
				$output2 .= '<img height=30px width=30px src="' . $plans['_rpt_icon'] . '" class="rpt_icon rpt_icon_' . $key . '"/> ';
			}
			
			$output2 .= $plans['_rpt_title'];
			$output2 .= $reco . '</div>';
		}
		
		// Head
		$output2 .= '<div class="rpt_head rpt_head_' . $key . '">';
		
			// Recurrence
			if (!empty($plans['_rpt_recurrence'])){
			    	$output2 .= '<div class="rpt_recurrence rpt_recurrence_' . $key . '">' . $plans['_rpt_recurrence'] . '</div>';
			}
			
			// Price
			if (!empty($plans['_rpt_price'])){
			    
			    $output2 .= '<div class="rpt_price rpt_price_' . $key . '">';
			    
			    if (!empty($plans['_rpt_free'])){
			    	if ($plans['_rpt_free'] == true ){
			    		$output2 .= '';
			    	} else {
				    	$output2 .= '<span class="rpt_currency"></span>' . $plans['_rpt_price'];
			    	}		
			    } else {
			    	
			    	$currency = get_post_meta( $post->ID, '_rpt_currency', true );
			    
			    	if (!empty($currency)){
			    		$output2 .= '<span class="rpt_currency">';
			    		$output2 .= $currency;
						$output2 .= '</span>';
					}
			    	
			    	$output2 .= $plans['_rpt_price'];
			    
			    }
			    
			    $output2 .= '</div>';
			}
			
			// Subtitle
			if (!empty($plans['_rpt_subtitle'])){
			    	$output2 .= '<div style="color:' . $plans['_rpt_color'] . ';" class="rpt_subtitle rpt_subtitle_' . $key . '">' . $plans['_rpt_subtitle'] . '</div>';
			    }
			
			// Description	
			if (!empty($plans['_rpt_description'])){
			    $output2 .= '<div class="rpt_description rpt_description_' . $key . '">' . $plans['_rpt_description'] . '</div>';
			}
			
		// Closing plan head
		$output2 .= '</div>';
		
		
		if (!empty($plans['_rpt_features'])){
			

            $output2 .= '<div class="rpt_features rpt_features_' . $key . '">';

			
			$string = $plans['_rpt_features'];
			$stringAr = explode("\n", $string);
			$stringAr = array_filter($stringAr, 'trim');
			
			$features = '';
			
			foreach ($stringAr as $feature) {
				$features[] .= strip_tags($feature,'<strong></strong><br><br/></br><img><a>');
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
											
					$output2 .= '<div style="color:' . $check_color . ';" class="rpt_feature rpt_feature_' . $key . '-' . $small_key . '">';
					$output2 .= $feature;
					$output2 .= '</div>';
							
				} 
			}
			
			$output2 .= '</div>';
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
		
		// Link option
		$newcurrentwindow = get_post_meta( $post->ID, '_rpt_open_newwindow', true );
		if ($newcurrentwindow == 'newwindow'){
			$link_behavior = 'target="_blank"';
		} else {
			$link_behavior = 'target="_self"';
		}
		
		// Foot
        if (!empty($plans['_rpt_btn_text'])){
		  $output2 .= '<a '. $link_behavior .' href="' . $btn_link . '" style="background:' . $plans['_rpt_color'] . '" class="rpt_foot rpt_foot_' . $key . '">';
        } else {
          $output2 .= '<a '. $link_behavior .' style="background:' . $plans['_rpt_color'] . '" class="rpt_foot rpt_foot_' . $key . '">';
        }

        $output2 .= $btn_text;
		
		// Closing foot
		$output2 .= '</a>';
		
    $output2 .= '</div>';

	}
	
	// Closing rpt_inner
	$output2 .= '</div>';
	
	// Closing rpt_container
	$output2 .= '</div>';
	
	$output2 .= '<div style="clear:both;"></div>';
  	
  endforeach; wp_reset_query(); 
  return $output2;

}

add_shortcode("rpt", "rpt_sc");
?>