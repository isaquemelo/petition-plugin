<?php 
    function sign_petition($params) {
        $petition_id = $params['petition_id'];
        $name = $params['name'];
        $email = $params['email'];
        $country = $params['country'];
        $keep_me_updated = $params['keep_me_updated'];

        $post_metadatum = [
            'petition_id' => $petition_id,
            'email' => $email,
            'name' => $name,
            'country' => $country,
            'keep_me_updated' => $keep_me_updated,
        ];

        $post_args = [
            'post_type' => 'signature',
            'post_status' => 'publish',
            'meta_input' => $post_metadatum,
        ];

        $post_id = wp_insert_post( $post_args, false );
        return $post_id;
    }

    add_action( 'rest_api_init', function () {
        register_rest_route( 'petition', '/sign', array(
            'methods' => 'POST',
            'callback' => 'sign_petition',
            'args' => [
                'petition_id' => array(
                    'required' => true,
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param ) && get_post($param);
                    }
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