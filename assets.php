<?php

add_action('wp_enqueue_scripts', function(){
    wp_enqueue_script( 'form', plugin_dir_url( __FILE__ ) . 'assets/js/form.js', array('jquery'), filemtime(__DIR__.'/assets/js/form.js'), true );
    wp_enqueue_style( 'petition',  plugin_dir_url(__FILE__) . 'assets/css/petition.css' );
});
