<?php

/*
* Plugin Name: Abaixo Assinados - Hacklab
* Plugin Description: Abaixo Assinados by hacklab
* Author: hacklab/
*/
include __DIR__ . '/library/utils.php';
include __DIR__ . '/library/api.php';
include __DIR__ . '/library/widgets.php';
include __DIR__ . '/library/ctps.php';
include __DIR__ . '/library/admin-columns.php';
include __DIR__ . '/library/assets.php';
//include __DIR__ . '/library/ajax.php';
include __DIR__ . '/library/metaboxes.php';
include __DIR__ . '/library/register-sidebars.php';
include __DIR__ . '/library/export-signatures.php';
include __DIR__ . '/library/settings-page.php';
// include __DIR__ . '/pagebuilder.php';

add_filter('template_include', 'petitions_single_template');

function petitions_single_template($template) {
   
    set_block_custom_colors();

    if (is_singular('petition')) {
		return plugin_dir_path(__FILE__) . 'templates/single-petition.php';
	}
	return $template;
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

add_action( 'init', 'update_signature_with_no_title' );

function update_signature_with_no_title(){
    global $wpdb;
    $results = $wpdb->get_results("SELECT ID FROM {$wpdb->prefix}posts 
        WHERE post_title = '' AND post_type = 'signature'", OBJECT);
   
    foreach ($results as $post) {
        $name = get_post_meta($post->ID, 'name', true);
        wp_update_post(['ID' => $post->ID, 'post_title' => $name]);
    }
}


function set_block_custom_colors(){
    $colors = [
                'primary' => newspack_get_primary_color(),
                'secondary' => newspack_get_secondary_color()
                ]; 
    ?>

    <style type="text/css">
        .progress-bar .progressed-area, .petition-form button{
            background-color: <?= $colors['primary'] ?> !important;
        }

        .progress .progress-helper span:first-child, .signatures-count .quantity span:last-child{
            color: <?= $colors['primary'] ?> !important;
        }

       .progress .progress-helper span:last-child{
            color: <?= $colors['secondary'] ?> !important;
       }
        
    </style>
    
    <?php 
}

add_action('init', function() {

    wp_register_script('petition-block-js', plugins_url('assets/js/output/petition-block.js', __FILE__) );

    wp_enqueue_style( 'petition-block-style', plugins_url('assets/css/petition.css', __FILE__), false, '1.0.0', 'all');

    wp_register_style('petition-block-editor-style', plugins_url('assets/css/petition-editor.css', __FILE__), false, '1.0.0', 'all');
 
    register_block_type('petitions/petition-block', [
        'editor_script' => 'petition-block-js',
        'editor_style' => 'petition-block-editor-style',
        'render_callback' => 'petition_block_render',
        'attributes' => [
            'petitionID' => [
                'type' => 'number',
                'default' => null
            ],
            'showSignaturesMax' => [
                'type' => 'number',
                'default' => 5
            ],
            'showTotal' => [
                'type' => 'boolean',
                'default' => true
            ],
            'showGoal' => [
                'type' => 'boolean',
                'default' => true
            ],
        ],
    ]);
});

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

function progress_bar($petition_id, $signatures_count, $goal, $show = true){
    if(!$show) return '';
    
    return "<div class='progress'>
                <div class='progress-bar'>
                    <div class='progressed-area' style='width:".progress_calc($signatures_count, $goal)."%'></div>
                    <div class='progress-info'>
                        <span>".$signatures_count."</span>
                        <span>".$goal."</span>
                    </div>
                </div>
                <div class='progress-helper'>
                    <span>".get_post_meta($petition_id, 'petition_form_signatures', true)."</span>
                    <span>".get_post_meta($petition_id, 'petition_form_goal', true)."</span>
                </div>
            </div>";
}

function total($signatures_count, $petition_id, $show=true){
    if(!$show) return '';
    
    return "<div class='quantity'>
                <span>".$signatures_count."</span>
                <span>".get_post_meta($petition_id, 'petition_form_signatures', true )."</span>
            </div>";
}

function petition_block_render($attr, $content){
    $petition_id = $attr['petitionID'];

    $signatures_count = count_signatures($petition_id);
    $goal = get_post_meta($petition_id, 'petition_goal', true );

    return "<div class='petition-block'>
                <div class='signatures-information'>
                    <div class='signatures-count'>
                        ".total($signatures_count, $petition_id, $attr['showTotal'])."
                        <div class='join'><a href='". get_the_permalink($petition_id) ."'>".get_post_meta($petition_id, 'petition_form_join_title', true )."</a></div>"
                    .progress_bar($petition_id, $signatures_count, $goal, $attr['showGoal']).
                    "</div>
                    ".sigantures_history($petition_id, $attr['showSignaturesMax'])."  
                </div>
            </div>
            ";
}

function progress_calc($signatures_count, $goal){

    if($signatures_count < $goal) {
        $complete = ($signatures_count / $goal) * 100;
    } else {
        $complete = 100;
    }
    
    return $complete;
}

function sigantures_history($petition_id, $signatures_max){
     
    $history = "<div class='signatures-history' data-signature-text='".get_post_meta($petition_id, 'petition_form_submission', true)."'>";

    $svg = "<svg aria-hidden='true' focusable='false' data-prefix='fas' data-icon='user' role='img' xmlns='http://www.w3.org/2000/svg'  viewBox='0 0 448 512' class='svg-inline--fa fa-user fa-w-14 fa-3x'>
            <path fill='currentColor' d='M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z' class=''>
            </path>
        </svg>"; 

    $highlight_ids = get_post_meta(get_the_ID(), 'highlight_signatures', true);
    $highlight_ids = array_map('intval', explode(",", $highlight_ids));
    $highlights = new WP_Query(['post__in' => $highlight_ids, 'post_type' => 'signature']);
    
    $common_signatures = new WP_Query( [
        'post_type' => 'signature',
        'post__not_in' => $highlight_ids,
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'petition_id',
                'value' => $petition_id,
                'compare' => '='
            ],
            [
                'key' => 'show_signature',
                'value' => true,
                'compare' => '='
            ]
        ],
        'posts_per_page' => $signatures_max
    ] );
     
    $signatures = new WP_Query();
    $signatures->posts = array_merge( $highlights->posts, $common_signatures->posts );
    $signatures->post_count = $highlights->post_count + $common_signatures->post_count;
 
        if ( $signatures->have_posts() &&  get_post_meta($petition_id, 'petition_signatures_shown', true) !== '0') {
            
            while ( $signatures->have_posts() ) {
                
                $signatures->the_post(); 
                
                $history .= "<div class='user-signature'>".$svg.get_post_meta(get_the_ID(), 'name', true) . ' '. get_post_meta($petition_id, 'petition_form_submission', true)."</div>";
            }
        } else {
            // no posts found
        }
        /* Restore original Post Data */
        wp_reset_postdata();
 
    return $history .= "</div>";
}