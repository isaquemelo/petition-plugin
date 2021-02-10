<?php

/*
* Plugin Name: Abaixo Assinados - Hacklab
* Plugin Description: Abaixo Assinados by hacklab
* Author: hacklab/
*/
include __DIR__ . '/library/api.php';
include __DIR__ . '/library/widgets.php';
include __DIR__ . '/library/ctps.php';
include __DIR__ . '/library/admin-columns.php';
include __DIR__ . '/library/assets.php';
include __DIR__ . '/library/ajax.php';
include __DIR__ . '/library/metaboxes.php';
include __DIR__ . '/library/register-sidebars.php';
include __DIR__ . '/library/export-signatures.php';
include __DIR__ . '/library/settings-page.php';
// include __DIR__ . '/pagebuilder.php';

add_filter('template_include', 'petitions_single_template');

function petitions_single_template($template) {
	if (is_singular('petition')) {
		return plugin_dir_path(__FILE__) . 'templates/single-petition.php';
	}
	return $template;
}

function count_signatures($petition_id) {
	global $wpdb;

	$results = $wpdb->get_results(
		"SELECT count(*) as qtd FROM 
		$wpdb->postmeta as pm
		JOIN $wpdb->posts AS p ON pm.post_id = p.ID
		WHERE pm.meta_key = 'petition_id' 
		AND pm.meta_value = '{$petition_id}' AND post_status = 'publish'", OBJECT);

	foreach ($results as $r) {
		return $r->qtd;
	}
}

function set_wp_mail_content_type(){
    return "text/html";
}

add_filter( 'wp_mail_content_type','set_wp_mail_content_type' );

function get_languages($petition_id) {
	global $wpdb;
	$results = $wpdb->get_results("SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'petition_parent' and meta_value = '{$petition_id}'", OBJECT);

	return $results;
}

// Add action hook only if action=download_csv
if ( isset($_GET['action'] ) && $_GET['action'] == 'download_csv' )  {
	// Handle CSV Export
	add_action( 'admin_init', 'csv_export' );
}

function csv_export() {
    // Check for current user privileges 
    // if( !current_user_can( 'manage_options' ) ){ return false; }

    // Check if we are in WP-Admin
    if( !is_admin() ){ return false; }

    // Nonce Check
    // $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
    // if ( ! wp_verify_nonce( $nonce, 'download_csv' ) ) {
    //     die( 'Security check error' );
    // }
    
    ob_start();

    $domain = $_SERVER['SERVER_NAME'];
    $filename = 'petition-signatures-' . $domain . '-' . time() . '.csv';
    
    $header_row = array(
        'Email',
		'Name',
		'Country',
		'Keep me Updated?',
		'Date'
	);
	
    $data_rows = array();
    global $wpdb;
    $sql = 'SELECT * FROM ' . $wpdb->users;
	$users = $wpdb->get_results( $sql, 'ARRAY_A' );
	
    foreach ( $users as $user ) {
        $row = array(
            $user['user_email'],
            $user['user_name']
        );
        $data_rows[] = $row;
	}
	
    $fh = @fopen( 'php://output', 'w' );
    fprintf( $fh, chr(0xEF) . chr(0xBB) . chr(0xBF) );
    header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
    header( 'Content-Description: File Transfer' );
    header( 'Content-type: text/csv' );
    header( "Content-Disposition: attachment; filename={$filename}" );
    header( 'Expires: 0' );
    header( 'Pragma: public' );
    fputcsv( $fh, $header_row );
    foreach ( $data_rows as $data_row ) {
        fputcsv( $fh, $data_row );
    }
    fclose( $fh );
    
    ob_end_flush();
    
    die();
}