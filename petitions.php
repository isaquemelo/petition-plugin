<?php

/*
* Plugin Name: Abaixo Assinados - Hacklab
* Plugin Description: Abaixo Assinados by hacklab
* Author: hacklab/
*/
include __DIR__ . '/library/api.php';
include __DIR__ . '/library/ctps.php';
include __DIR__ . '/library/admin-columns.php';
include __DIR__ . '/library/assets.php';
include __DIR__ . '/library/ajax.php';
include __DIR__ . '/library/metaboxes.php';
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


function get_languages($petition_id) {
	global $wpdb;
	$results = $wpdb->get_results("SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'petition_parent' and meta_value = '{$petition_id}'", OBJECT);

	return $results;
}
