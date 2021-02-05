<?php 

function petition_post_type() {

	$labels = array(
		'name'                  => _x( 'Petitions', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Petition', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Petitions', 'petition' ),
		'name_admin_bar'        => __( 'Petition', 'petition' ),
		'archives'              => __( 'Petitions', 'petition' ),
		'attributes'            => __( 'Item Attributes', 'petition' ),
		'parent_item_colon'     => __( 'Parent Item:', 'petition' ),
		'all_items'             => __( 'All Items', 'petition' ),
		'add_new_item'          => __( 'Add New Item', 'petition' ),
		'add_new'               => __( 'Add New', 'petition' ),
		'new_item'              => __( 'New Item', 'petition' ),
		'edit_item'             => __( 'Edit Item', 'petition' ),
		'update_item'           => __( 'Update Item', 'petition' ),
		'view_item'             => __( 'View Item', 'petition' ),
		'view_items'            => __( 'View Items', 'petition' ),
		'search_items'          => __( 'Search Item', 'petition' ),
		'not_found'             => __( 'Not found', 'petition' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'petition' ),
		'featured_image'        => __( 'Featured Image', 'petition' ),
		'set_featured_image'    => __( 'Set featured image', 'petition' ),
		'remove_featured_image' => __( 'Remove featured image', 'petition' ),
		'use_featured_image'    => __( 'Use as featured image', 'petition' ),
		'insert_into_item'      => __( 'Insert into item', 'petition' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'petition' ),
		'items_list'            => __( 'Items list', 'petition' ),
		'items_list_navigation' => __( 'Items list navigation', 'petition' ),
		'filter_items_list'     => __( 'Filter items list', 'petition' ),
	);
	$args = array(
		'label'                 => __( 'Petition', 'petition' ),
		'labels'                => $labels,
		'taxonomies'            => array('languages'),
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
		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes'),
		'capability_type'       => 'page',
		'rewrite'				=> [ 'slug' => 'petition'] 
	);
	register_post_type( 'petition', $args );

}
add_action( 'init', 'petition_post_type', 0 );


function signatures_post_type() {

	$labels = array(
		'name'                  => _x( 'Signatures', 'Post Type General Name', 'petition' ),
		'singular_name'         => _x( 'Signature', 'Post Type Singular Name', 'petition' ),
		'menu_name'             => __( 'Signatures', 'petition' ),
		'name_admin_bar'        => __( 'Signature', 'petition' ),
		'archives'              => __( 'Signatures', 'petition' ),
		'attributes'            => __( 'Item Attributes', 'petition' ),
		'parent_item_colon'     => __( 'Parent Item:', 'petition' ),
		'all_items'             => __( 'All Items', 'petition' ),
		'add_new_item'          => __( 'Add New Item', 'petition' ),
		'add_new'               => __( 'Add New', 'petition' ),
		'new_item'              => __( 'New Item', 'petition' ),
		'edit_item'             => __( 'Edit Item', 'petition' ),
		'update_item'           => __( 'Update Item', 'petition' ),
		'view_item'             => __( 'View Item', 'petition' ),
		'view_items'            => __( 'View Items', 'petition' ),
		'search_items'          => __( 'Search Item', 'petition' ),
		'not_found'             => __( 'Not found', 'petition' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'petition' ),
		'featured_image'        => __( 'Featured Image', 'petition' ),
		'set_featured_image'    => __( 'Set featured image', 'petition' ),
		'remove_featured_image' => __( 'Remove featured image', 'petition' ),
		'use_featured_image'    => __( 'Use as featured image', 'petition' ),
		'insert_into_item'      => __( 'Insert into item', 'petition' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'petition' ),
		'items_list'            => __( 'Items list', 'petition' ),
		'items_list_navigation' => __( 'Items list navigation', 'petition' ),
		'filter_items_list'     => __( 'Filter items list', 'petition' ),
	);
	$args = array(
		'label'                 => __( 'Assinatura', 'petition' ),
		'labels'                => $labels,
		'supports'              => array(),
		// 'taxonomies'            => array( 'category', 'post_tag', '' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		// 'capability_type'       => 'page',
	);
	register_post_type( 'signature', $args );
	remove_post_type_support('signature', 'title');
	remove_post_type_support('signature', 'editor');
}

add_action( 'init', 'signatures_post_type', 0 );

// Register Custom Taxonomy
function languages_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Languages', 'Taxonomy General Name', 'petition' ),
		'singular_name'              => _x( 'Language', 'Taxonomy Singular Name', 'petition' ),
		'menu_name'                  => __( 'Languages', 'petition' ),
		'all_items'                  => __( 'All Items', 'petition' ),
		'parent_item'                => __( 'Parent Item', 'petition' ),
		'parent_item_colon'          => __( 'Parent Item:', 'petition' ),
		'new_item_name'              => __( 'New Item', 'petition' ),
		'add_new_item'               => __( 'Add New', 'petition' ),
		'edit_item'                  => __( 'Edit Item', 'petition' ),
		'update_item'                => __( 'Update Item', 'petition' ),
		'view_item'                  => __( 'View Item', 'petition' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'petition' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'petition' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'petition' ),
		'popular_items'              => __( 'Popular Items', 'petition' ),
		'search_items'               => __( 'Search Items', 'petition' ),
		'not_found'                  => __( 'Not Found', 'petition' ),
		'no_terms'                   => __( 'No items', 'petition' ),
		'items_list'                 => __( 'Items list', 'petition' ),
		'items_list_navigation'      => __( 'Items list navigation', 'petition' ),
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
