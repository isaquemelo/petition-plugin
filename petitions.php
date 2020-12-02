<?php

/*
* Plugin Name: Abaixo Assinados - Hacklab
* Plugin Description: Abaixo Assinados by hacklab
* Author: hacklab/
*/
include __DIR__ . '/api.php';
include __DIR__ . '/ctps.php';
include __DIR__ . '/assets.php';
include __DIR__ . '/ajax.php';
include __DIR__ . '/metaboxes.php';
// include __DIR__ . '/pagebuilder.php';

// Adding rewrite rule for caching
function petitions_cache_rewrite() {
	add_rewrite_rule('petitions/([0-9]+)/create$', 'wp-admin/admin-ajax.php?action=petition_createpost&petition_id=$1', 'top');
}
add_action('init', 'petitions_cache_rewrite');

add_filter('template_include', 'petitions_single_template');

function petitions_single_template($template) {
	if (is_singular('petition')) {
		return plugin_dir_path(__FILE__) . 'single-petition.php';
	}
	return $template;
}

function count_signatures($petition_id) {
	global $wpdb;
	$results = $wpdb->get_results("SELECT count(*) as qtd FROM {$wpdb->prefix}postmeta WHERE meta_key = 'petition_id' and meta_value = '{$petition_id}'", OBJECT);

	foreach ($results as $r) {
		return $r->qtd;
	}
}


function get_languages($petition_id) {
	global $wpdb;
	$results = $wpdb->get_results("SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'petition_parent' and meta_value = '{$petition_id}'", OBJECT);

	return $results;
}
