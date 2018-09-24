// registration code for industry partners post type
	function register_industrypartner_posttype() {
		$labels = array(
			'name' 				=> _x( 'Industry Partners', 'post type general name' ),
			'singular_name'		=> _x( 'Industry Partner', 'post type singular name' ),
			'add_new' 			=> __( 'Add New' ),
			'add_new_item' 		=> __( 'Industry Partner' ),
			'edit_item' 		=> __( 'Industry Partner' ),
			'new_item' 			=> __( 'Industry Partner' ),
			'view_item' 		=> __( 'Industry Partner' ),
			'search_items' 		=> __( 'Industry Partner' ),
			'not_found' 		=> __( 'Industry Partner' ),
			'not_found_in_trash'=> __( 'Industry Partner' ),
			'parent_item_colon' => __( 'Industry Partner' ),
			'menu_name'			=> __( 'Industry Partners' )
		);
		
		$taxonomies = array();
		
		$supports = array('title','editor','author','thumbnail','excerpt','revisions','post-formats');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Industry Partner'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type' 	=> 'post',
			'has_archive' 		=> true,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'industrypartner', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 6,
			'menu_icon' 		=> '/wp-content/plugins/easy-content-types//includes/images/icon.png',
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('industrypartner',$post_type_args);
	}
	add_action('init', 'register_industrypartner_posttype');




	$intro_6_metabox = array(
    'id' => 'intro',
    'title' => 'Intro',
    'page' => array('industrypartner'),
    'context' => 'normal',
    'priority' => 'default',
    'fields' => array(
                
                array(
                    'name'             => 'Image',
                    'desc'             => 'Company Image of the industry partner',
                    'id'                 => 'ecpt_ipimage',
                    'class'             => 'ecpt_ipimage',
                    'type'             => 'upload',
                    'rich_editor'     => 0,            
                    'max'             => 0                
                ),
                            
                array(
                    'name'             => 'About',
                    'desc'             => 'Introtext about the industry partner',
                    'id'                 => 'ecpt_ipabout',
                    'class'             => 'ecpt_ipabout',
                    'type'             => 'textarea',
                    'rich_editor'     => 1,            
                    'max'             => 0                
                ),
                )
);            
            
add_action('admin_menu', 'ecpt_add_intro_6_meta_box');
function ecpt_add_intro_6_meta_box() {
    global $intro_6_metabox;        
    foreach($intro_6_metabox['page'] as $page) {
        add_meta_box($intro_6_metabox['id'], $intro_6_metabox['title'], 'ecpt_show_intro_6_box', $page, 'normal', 'default', $intro_6_metabox);
    }
}
// function to show meta boxes
function ecpt_show_intro_6_box()    {
    global $post;
    global $intro_6_metabox;
    global $ecpt_prefix;
    global $wp_version;
    
    // Use nonce for verification
    echo '<input type="hidden" name="ecpt_intro_6_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';
    foreach ($intro_6_metabox['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
                '<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
                break;
            case 'date':
                if($meta) { $value = ecpt_timestamp_to_date($meta); } else { $value = ''; }
                echo '<input type="text" class="ecpt_datepicker" name="' . $field['id'] . '" id="' . $field['id'] . '" value="'. $value . '" size="30" style="width:97%" />' . '' . stripslashes($field['desc']);
                break;
            case 'upload':
                echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload Image" /><br/>', '', stripslashes($field['desc']);
                break;
            case 'textarea':
            
                if($field['rich_editor'] == 1) {
                    if($wp_version >= 3.3) {
                        echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id']));
                    } else {
                        // older versions of WP
                        $editor = '';
                        if(!post_type_supports($post->post_type, 'editor')) {
                            $editor = wp_tiny_mce(true, array('editor_selector' => $field['class'], 'remove_linebreaks' => false) );
                        }
                        $field_html = '<div style="width: 97%; border: 1px solid #DFDFDF;"><textarea name="' . $field['id'] . '" class="' . $field['class'] . '" id="' . $field['id'] . '" cols="60" rows="8" style="width:100%">'. $meta . '</textarea></div><br/>' . __(stripslashes($field['desc']));
                        echo $editor . $field_html;
                    }
                } else {
                    echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);                
                }
                
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option value="' . $option . '"', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>', '', stripslashes($field['desc']);
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option, '"', $meta == $option ? ' checked="checked"' : '', ' /> ', $option;
                }
                echo '<br/>' . stripslashes($field['desc']);
                break;
            case 'multicheck':
                foreach ($field['options'] as $option) {
                    echo '<input type="checkbox" name="' . $field['id'] . '[' . $option . ']" value="' . $option . '"' . checked( true, in_array( $option, $meta ), false ) . '/> ' . $option;
                }
                echo '<br/>' . stripslashes($field['desc']);
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' /> ';
                echo stripslashes($field['desc']);
                break;
            case 'slider':
                echo '<input type="text" rel="' . $field['max'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="1" style="float: left; margin-right: 5px" />';
                echo '<div class="ecpt-slider" rel="' . $field['id'] . '" style="float: left; width: 60%; margin: 5px 0 0 0;"></div>';        
                echo '<div style="width: 100%; clear: both;">' . stripslashes($field['desc']) . '</div>';
                break;
            case 'repeatable' :
                
                $field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_field_name" value=""/>';
                if(is_array($meta)) {
                    $count = 1;
                    foreach($meta as $key => $value) {
                        $field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta[$key] . '" size="30" style="width:90%" />';
                        if($count > 1) {
                            $field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
                        }
                        $field_html .= '</div>';
                        $count++;
                    }
                } else {
                    $field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta . '" size="30" style="width:90%" /></div>';
                }
                $field_html .= '<button class="ecpt_add_new_field button-secondary">' . __('Add New', 'ecpt') . '</button>  ' . __(stripslashes($field['desc']));
                
                echo $field_html;
                
                break;
            
            case 'repeatable upload' :
            
                $field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_upload_field_name" value=""/>';
                if(is_array($meta)) {
                    $count = 1;
                    foreach($meta as $key => $value) {
                        $field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta[$key] . '" size="30" style="width:80%" /><button class="button-secondary ecpt_upload_image_button">Upload File</button>';
                        if($count > 1) {
                            $field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
                        }
                        $field_html .= '</div>';
                        $count++;
                    }
                } else {
                    $field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta . '" size="30" style="width:80%" /><input class="button-secondary ecpt_upload_image_button" type="button" value="Upload File" /></div>';
                }
                $field_html .= '<button class="ecpt_add_new_upload_field button-secondary">' . __('Add New', 'ecpt') . '</button>  ' . __(stripslashes($field['desc']));        
            
                echo $field_html;
            
                break;
        }
        echo '<td>',
            '</tr>';
    }
    
    echo '</table>';
}    
// Save data from meta box
add_action('save_post', 'ecpt_intro_6_save');
function ecpt_intro_6_save($post_id) {
    global $post;
    global $intro_6_metabox;
    
    // verify nonce
    if (!wp_verify_nonce($_POST['ecpt_intro_6_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    foreach ($intro_6_metabox['fields'] as $field) {
    
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        
        if ($new && $new != $old) {
            if($field['type'] == 'date') {
                $new = ecpt_format_date($new);
                update_post_meta($post_id, $field['id'], $new);
            } else {
                if(is_string($new)) {
                    $new = $new;
                }
                update_post_meta($post_id, $field['id'], $new);
                
                
            }
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
$details_7_metabox = array(
    'id' => 'details',
    'title' => 'Details',
    'page' => array('industrypartner'),
    'context' => 'normal',
    'priority' => 'default',
    'fields' => array(
                
                array(
                    'name'             => 'Company Name',
                    'desc'             => 'Name of the company',
                    'id'                 => 'ecpt_ipcompanyname',
                    'class'             => 'ecpt_ipcompanyname',
                    'type'             => 'text',
                    'rich_editor'     => 0,            
                    'max'             => 0                
                ),
                            
                array(
                    'name'             => 'Company Website',
                    'desc'             => 'URL Adress of the company',
                    'id'                 => 'ecpt_ipcompanyurl',
                    'class'             => 'ecpt_ipcompanyurl',
                    'type'             => 'text',
                    'rich_editor'     => 0,            
                    'max'             => 0                
                ),
                
				array(
                    'name'             => 'Company Region',
                    'desc'             => 'Region of the company',
                    'id'                 => 'ecpt_ipcompanycountry',
                    'class'             => 'ecpt_ipcompanycountry',
                    'type'             => 'text',
                    'rich_editor'     => 0,            
                    'max'             => 0                
                ),
                )
);            
            
add_action('admin_menu', 'ecpt_add_details_7_meta_box');
function ecpt_add_details_7_meta_box() {
    global $details_7_metabox;        
    foreach($details_7_metabox['page'] as $page) {
        add_meta_box($details_7_metabox['id'], $details_7_metabox['title'], 'ecpt_show_details_7_box', $page, 'normal', 'default', $details_7_metabox);
    }
}
// function to show meta boxes
function ecpt_show_details_7_box()    {
    global $post;
    global $details_7_metabox;
    global $ecpt_prefix;
    global $wp_version;
    
    // Use nonce for verification
    echo '<input type="hidden" name="ecpt_details_7_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    
    echo '<table class="form-table">';
    foreach ($details_7_metabox['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
                '<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
                break;
            case 'date':
                if($meta) { $value = ecpt_timestamp_to_date($meta); } else { $value = ''; }
                echo '<input type="text" class="ecpt_datepicker" name="' . $field['id'] . '" id="' . $field['id'] . '" value="'. $value . '" size="30" style="width:97%" />' . '' . stripslashes($field['desc']);
                break;
            case 'upload':
                echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload Image" /><br/>', '', stripslashes($field['desc']);
                break;
            case 'textarea':
            
                if($field['rich_editor'] == 1) {
                    if($wp_version >= 3.3) {
                        echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id']));
                    } else {
                        // older versions of WP
                        $editor = '';
                        if(!post_type_supports($post->post_type, 'editor')) {
                            $editor = wp_tiny_mce(true, array('editor_selector' => $field['class'], 'remove_linebreaks' => false) );
                        }
                        $field_html = '<div style="width: 97%; border: 1px solid #DFDFDF;"><textarea name="' . $field['id'] . '" class="' . $field['class'] . '" id="' . $field['id'] . '" cols="60" rows="8" style="width:100%">'. $meta . '</textarea></div><br/>' . __(stripslashes($field['desc']));
                        echo $editor . $field_html;
                    }
                } else {
                    echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);                
                }
                
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option value="' . $option . '"', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>', '', stripslashes($field['desc']);
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option, '"', $meta == $option ? ' checked="checked"' : '', ' /> ', $option;
                }
                echo '<br/>' . stripslashes($field['desc']);
                break;
            case 'multicheck':
                foreach ($field['options'] as $option) {
                    echo '<input type="checkbox" name="' . $field['id'] . '[' . $option . ']" value="' . $option . '"' . checked( true, in_array( $option, $meta ), false ) . '/> ' . $option;
                }
                echo '<br/>' . stripslashes($field['desc']);
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' /> ';
                echo stripslashes($field['desc']);
                break;
            case 'slider':
                echo '<input type="text" rel="' . $field['max'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="1" style="float: left; margin-right: 5px" />';
                echo '<div class="ecpt-slider" rel="' . $field['id'] . '" style="float: left; width: 60%; margin: 5px 0 0 0;"></div>';        
                echo '<div style="width: 100%; clear: both;">' . stripslashes($field['desc']) . '</div>';
                break;
            case 'repeatable' :
                
                $field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_field_name" value=""/>';
                if(is_array($meta)) {
                    $count = 1;
                    foreach($meta as $key => $value) {
                        $field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta[$key] . '" size="30" style="width:90%" />';
                        if($count > 1) {
                            $field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
                        }
                        $field_html .= '</div>';
                        $count++;
                    }
                } else {
                    $field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta . '" size="30" style="width:90%" /></div>';
                }
                $field_html .= '<button class="ecpt_add_new_field button-secondary">' . __('Add New', 'ecpt') . '</button>  ' . __(stripslashes($field['desc']));
                
                echo $field_html;
                
                break;
            
            case 'repeatable upload' :
            
                $field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_upload_field_name" value=""/>';
                if(is_array($meta)) {
                    $count = 1;
                    foreach($meta as $key => $value) {
                        $field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta[$key] . '" size="30" style="width:80%" /><button class="button-secondary ecpt_upload_image_button">Upload File</button>';
                        if($count > 1) {
                            $field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
                        }
                        $field_html .= '</div>';
                        $count++;
                    }
                } else {
                    $field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[]" id="' . $field['id'] . '[]" value="' . $meta . '" size="30" style="width:80%" /><input class="button-secondary ecpt_upload_image_button" type="button" value="Upload File" /></div>';
                }
                $field_html .= '<button class="ecpt_add_new_upload_field button-secondary">' . __('Add New', 'ecpt') . '</button>  ' . __(stripslashes($field['desc']));        
            
                echo $field_html;
            
                break;
        }
        echo '<td>',
            '</tr>';
    }
    
    echo '</table>';
}    
// Save data from meta box
add_action('save_post', 'ecpt_details_7_save');
function ecpt_details_7_save($post_id) {
    global $post;
    global $details_7_metabox;
    
    // verify nonce
    if (!wp_verify_nonce($_POST['ecpt_details_7_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
    foreach ($details_7_metabox['fields'] as $field) {
    
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        
        if ($new && $new != $old) {
            if($field['type'] == 'date') {
                $new = ecpt_format_date($new);
                update_post_meta($post_id, $field['id'], $new);
            } else {
                if(is_string($new)) {
                    $new = $new;
                }
                update_post_meta($post_id, $field['id'], $new);
                
                
            }
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
