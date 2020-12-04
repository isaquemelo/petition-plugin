<?php

add_action('wp_enqueue_scripts', function(){
    // Only enqueue when necessary
    if(is_singular('petition')) {
        $url = trailingslashit( get_site_url() );
        wp_enqueue_script( 'form', plugin_dir_url( __DIR__  ) . 'assets/js/form.js', array('jquery'), filemtime(__DIR__.'/../assets/js/form.js'), true );
        wp_localize_script('form', 'petitionPlugin', array(
            'url' => $url
            )
        );
        wp_enqueue_style( 'petition',  plugin_dir_url(__DIR__ ) . 'assets/css/petition.css' );
    }
});
