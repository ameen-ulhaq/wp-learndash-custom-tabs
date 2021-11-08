<?php
/**
 * Adding post type for custom tabs.
 */
function lms_custom_tabs_register_post_type() {

    $labels = array( 
        'name' => __( 'Custom Tabs' , 'tutsplus' ),
        'singular_name' => __( 'Custom Tab' , 'tutsplus' ),
        'menu_name' => _x('Custom Tab', 'admin menu'),
        'name_admin_bar' => _x('Custom Tab', 'admin bar'),
        'add_new' => __( 'New Custom Tab' , 'tutsplus' ),
        'add_new_item' => __( 'Add New Custom Tab' , 'tutsplus' ),
        'edit_item' => __( 'Edit Custom Tab' , 'tutsplus' ),
        'new_item' => __( 'New Custom Tab' , 'tutsplus' ),
        'view_item' => __( 'View Custom Tab' , 'tutsplus' ),
        'search_items' => __( 'Search Custom Tabs' , 'tutsplus' ),
        'not_found' =>  __( 'No Custom Tabs Found' , 'tutsplus' ),
        'not_found_in_trash' => __( 'No Custom Tabs found in Trash' , 'tutsplus' ),
    );

    $args = array(
        'labels' => $labels,
        'has_archive' => false,
        'public' => true,
        'hierarchical' => false,
        'supports' => array(
            'title', 
            'editor',
            'thumbnail',
            'page-attributes'
        ),
        'show_in_rest' => true,
        'show_in_menu' =>   'learndash-lms',
    );

    register_post_type('lms_custom_tabs', $args);
}
add_action( 'init', 'lms_custom_tabs_register_post_type' );