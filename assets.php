<?php

add_action('wp_enqueue_scripts', function(){
    wp_enqueue_script( 'form', plugin_dir_url( __FILE__ ) . '/js/form.js', array('jquery', 'jquery-mask'), filemtime(__DIR__.'/js/form.js'), true );
    // wp_enqueue_script( 'liveblog-vendor', plugin_dir_url( __FILE__ ) . 'liveblog-frontend/dist/vendor.js', array('liveblog-manifest'), '1.4.2', true );
    // wp_enqueue_script( 'liveblog-app', plugin_dir_url( __FILE__ ) . 'liveblog-frontend/dist/app.js', array(), '1.4.2', true );
});
