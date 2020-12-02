<?php 
    function sign_petition($params) {
        var_dump($params);
    }

    add_action( 'rest_api_init', function () {
        register_rest_route( 'petition', '/sign', array(
            'methods' => 'POST',
            'callback' => 'sign_petition',
            'args' => [
                'petition_id' => array(
                    'required' => true,
                ),

                'name' => array(
                    'required' => true,
                ),

                'email' => array(
                    'required' => true,
                ),

                'country' => array(
                    'required' => true,
                ),

                'keep_me_updated' => array(
                    'required' => true,
                ),
            ],

            'permission_callback' => '__return_true',
        ) );
    } );