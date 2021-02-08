<?php 
    function sign_petition($params) {
        $petition_id = $params['petition_id'];
        $child_id = $params['child_id'];
        $name = $params['name'];
        $email = $params['email'];
        $country = $params['country'];
        $keep_me_updated = $params['keep_me_updated'];
        $gcaptcha = $params['g-recaptcha-response'];

        // Chek if there's a signature already
        $args = [
            'post_type' => 'signature',
            'ignore_filtering' => true,
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'petition_id',
                    'value' => $petition_id,
                    'compare' => '='
                ],

                [
                    'key' => 'email',
                    'value' => $email,
                    'compare' => '='
                ]

            ]
        ];

        
        $arr_post = get_posts($args);

        if(sizeof($arr_post) >= 1) {
            return -1;
        }


        if(!class_exists('ReCaptchaResponse')){
            require_once ('recaptchalib.php');
        }
        
        $secret = get_option('captcha_secret');
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
                $message = signer_email_acknowledgment($child_id);
                $intended_id = $child_id;

            } else {
                $subject = get_the_title($petition_id) . " - Stop The Wall";
                $message = signer_email_acknowledgment($post_id);
                $intended_id = $petition_id;
            }

            // Send mail to user
            wp_mail( $to, $subject, $message );
                 
            // Send mail to target
            if(!empty(get_post_meta($intended_id, 'petition_target_email', true ))) {
                // Campo message customizavel
              
                $to = get_post_meta($intended_id, 'petition_target_email', true );
              
                $custom_subject = get_post_meta($petition_id, 'petition_target_subject', true );
              
                $subject = $custom_subject ? $custom_subject : "New sign in: ".get_the_title($petition_id);
                
                $message = target_email_body($intended_id, $post_metadatum);
                
                wp_mail( $to, $subject, $message );
               
            }
        }

        return $post_id;
    }

    function target_email_body($post_id, $post_metadatum){

        $from = " ".get_post_meta($post_id, 'from_target_email', true )." ";
        $signed = " ".get_post_meta($post_id, 'signed_target_email', true )." ";
        $description = get_post_meta($post_id, 'petition_form_share_description', true );
        $name = $post_metadatum['name'];
        $country = $post_metadatum['country'];

        return "<p>". $name . $from . $country . $signed . "</p> 
                <p>".$description."</p>";
    }


    function signer_email_acknowledgment($post_id){

        $petition_url = get_permalink($post_id);
        $acknowledgment_msg = get_post_meta($post_id, 'petition_email_signature', true );
        $title = urlencode(get_post_meta($post_id, 'petition_form_share_title', true ).':');

        return "<p>".$acknowledgment_msg."</p>
                <p>Please, share with your friends this petition: 
                    <a href='https://www.facebook.com/sharer/sharer.php?u=".$petition_url."'>share with Facebook</a>  / 
                    <a href='https://twitter.com/intent/tweet?text=".$title.'&url='.$petition_url.">share with Twitter</a> 
                </p>";
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