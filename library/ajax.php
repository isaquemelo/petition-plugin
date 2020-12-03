<?php 

// AJAX FUNCTIONS FOR CREATING petition POSTS
add_action( 'wp_ajax_petition_createpost', 'petition_createpost' );
add_action( 'wp_ajax_nopriv_petition_createpost', 'petition_createpost' );
function petition_createpost(){


    if(!class_exists('ReCaptchaResponse')){
        require_once ('recaptchalib.php');
    }

    $secret = "6LeEm6oUAAAAAO-CaT3K3kuF610AxNB4rolzylcc";
    $response = null;
    $reCaptcha = new \ReCaptcha($secret);
    $capcha_response = @$_POST["g-recaptcha-response"];

    $response = $reCaptcha->verifyResponse(
        isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'],
        $capcha_response
    );

    if(!$response || !$response->success){
        wp_send_json_error('{"success":false,"data":{"hide":0,"error":1,"response":"Error: Captcha inválido."}}');
        exit;
    }

    $post_data = array(
        'post_title' => $_POST['name'],
        'post_status' => 'publish',
        'post_type' => 'signatures',
        'meta_input' => [
            'petition_id' => $_GET['petition_id'],
            'email' => $_POST['email'],
            'whatsapp' => $_POST['whatsapp'],
            'cpf' => $_POST['cpf'],
            'cidade' => $_POST['cidade'], 
            'estado' => $_POST['estado'],
        ]
    );

    $post_id = wp_insert_post($post_data);

    echo $post_id;
    wp_die();
}
