<?php 
    function sign_petition($params) {
        $petition_id = $params['petition_id'];
        $child_id = $params['child_id'];
        $name = $params['name'];
        $email = $params['email'];
        $country = $params['country'];
        $keep_me_updated = $params['keep_me_updated'];
        $gcaptcha = $params['g-recaptcha-response'];

        if(!class_exists('ReCaptchaResponse')){
            require_once ('recaptchalib.php');
        }
    
        $secret = "6LeEm6oUAAAAAO-CaT3K3kuF610AxNB4rolzylcc";
        $response = null;
        $reCaptcha = new \ReCaptcha($secret);
        $capcha_response = $gcaptcha;
    
        $response = $reCaptcha->verifyResponse(
            isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'],
            $capcha_response
        );
    
        if(!$response || !$response->success){
            wp_send_json_error('{"success":false,"data":{"hide":0,"error":1,"response":"Error: Captcha inválido."}}');
            exit;
        }

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
        
        if($post_id) {
            $to = $email;
            $intended_id = false;

            if(get_post($child_id)) {
                $subject = get_the_title($child_id) . " - Stop The Wall";
                $message = get_post_meta($child_id, 'petition_email_signature', true );
                $intended_id = $child_id;

            } else {
                $subject = get_the_title($petition_id) . " - Stop The Wall";
                $message = get_post_meta($post_id, 'petition_email_signature', true );
                $intended_id = $petition_id;
            }

            // Send mail to user
            wp_mail( $to, $subject, $message );
                        
            // Send mail to admin
            $to = get_option('admin_email');
            $subject = "A new sign was made";
            $message = "$name($email) from $country, signed your petition";
            wp_mail( $to, $subject, $message );

            // Send mail to target
            if(!empty(get_post_meta($intended_id, 'petition_target_email', true ))) {
                // Campo message customizavel
                wp_mail( $to, $subject, $message );
            }


            // print_r( [ $subject, $message, $to, $email ]);
            

        }

        // return $post_id;
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

                'child_id' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric( $param );
                    }
                ),

                'g-recaptcha-response' => array(
                    'required' => true,
                ),
            ],

            'permission_callback' => '__return_true',
        ) );
    } );