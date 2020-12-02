<?php 

function petition_post_type() {

	$labels = array(
		'name'                  => _x( 'Petitions', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Petition', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Petitions', 'jeo' ),
		'name_admin_bar'        => __( 'Petition', 'jeo' ),
		'archives'              => __( 'Petitions', 'jeo' ),
		'attributes'            => __( 'Item Attributes', 'jeo' ),
		'parent_item_colon'     => __( 'Parent Item:', 'jeo' ),
		'all_items'             => __( 'All Items', 'jeo' ),
		'add_new_item'          => __( 'Add New Item', 'jeo' ),
		'add_new'               => __( 'Add New', 'jeo' ),
		'new_item'              => __( 'New Item', 'jeo' ),
		'edit_item'             => __( 'Edit Item', 'jeo' ),
		'update_item'           => __( 'Update Item', 'jeo' ),
		'view_item'             => __( 'View Item', 'jeo' ),
		'view_items'            => __( 'View Items', 'jeo' ),
		'search_items'          => __( 'Search Item', 'jeo' ),
		'not_found'             => __( 'Not found', 'jeo' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'jeo' ),
		'featured_image'        => __( 'Featured Image', 'jeo' ),
		'set_featured_image'    => __( 'Set featured image', 'jeo' ),
		'remove_featured_image' => __( 'Remove featured image', 'jeo' ),
		'use_featured_image'    => __( 'Use as featured image', 'jeo' ),
		'insert_into_item'      => __( 'Insert into item', 'jeo' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'jeo' ),
		'items_list'            => __( 'Items list', 'jeo' ),
		'items_list_navigation' => __( 'Items list navigation', 'jeo' ),
		'filter_items_list'     => __( 'Filter items list', 'jeo' ),
	);
	$args = array(
		'label'                 => __( 'Petition', 'jeo' ),
		'labels'                => $labels,
		'taxonomies'            => array( 'category', 'post_tag', 'languages'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'show_in_rest' => true,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt'),
		'capability_type'       => 'page',
		'rewrite'				=> [ 'slug' => 'petition'] 
	);
	register_post_type( 'petition', $args );

}
add_action( 'init', 'petition_post_type', 0 );


function signatures_post_type() {

	$labels = array(
		'name'                  => _x( 'Assinaturas', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Assinatura', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Assinaturas', 'text_domain' ),
		'name_admin_bar'        => __( 'Assinatura', 'text_domain' ),
		'archives'              => __( 'Assinaturas', 'text_domain' ),
		'attributes'            => __( 'Item Attributes', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'view_items'            => __( 'View Items', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Assinatura', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail'),
		'taxonomies'            => array( 'category', 'post_tag', '' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'signatures', $args );

}
add_action( 'init', 'signatures_post_type', 0 );

// Register Custom Taxonomy
function languages_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Languages', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Language', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Languages', 'jeo' ),
		'all_items'                  => __( 'All Items', 'jeo' ),
		'parent_item'                => __( 'Parent Item', 'jeo' ),
		'parent_item_colon'          => __( 'Parent Item:', 'jeo' ),
		'new_item_name'              => __( 'New Item', 'jeo' ),
		'add_new_item'               => __( 'Add New', 'jeo' ),
		'edit_item'                  => __( 'Edit Item', 'jeo' ),
		'update_item'                => __( 'Update Item', 'jeo' ),
		'view_item'                  => __( 'View Item', 'jeo' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'jeo' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'jeo' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'jeo' ),
		'popular_items'              => __( 'Popular Items', 'jeo' ),
		'search_items'               => __( 'Search Items', 'jeo' ),
		'not_found'                  => __( 'Not Found', 'jeo' ),
		'no_terms'                   => __( 'No items', 'jeo' ),
		'items_list'                 => __( 'Items list', 'jeo' ),
		'items_list_navigation'      => __( 'Items list navigation', 'jeo' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_in_rest' 				 => true,
	);
	register_taxonomy( 'languages', array( 'petition' ), $args );

}
add_action( 'init', 'languages_taxonomy', 0 );