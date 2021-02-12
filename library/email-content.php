<?php 

include __DIR__ . '/../library/utils.php';

function get_logo_url(){
    return wp_get_attachment_image_src(get_theme_mod( 'custom_logo' ), 'full' )[0];
}

function set_template_message($contents){
    $template = file_get_contents(__DIR__ . '/../templates/email-template.html');

    $search = ['#site_url', '#logo', "#title", "#year"];
    $replace = [home_url(), get_logo_url(), get_bloginfo('name'), date('Y')];

    $template  = str_replace($search, $replace, $template);

    foreach ($contents as $key => $content){
        $search = ['#content'.$key, '#align'.$key];
        $replace = [$content['content'], $content['align']];
        $template  = str_replace($search, $replace, $template);
    }

    return $template;
}


function target_email_body($post_id, $post_metadatum){
    $from = " ".strtolower(get_post_meta($post_id, 'from_email_field', true ))." ";
    $signed = " ".strtolower(get_post_meta($post_id, 'signed_email_field', true ))." ";
    $description = get_post_meta($post_id, 'petition_form_share_description', true );
    $custom_message = get_post_meta($post_id, 'message_target_email', true );
    
    $name = $post_metadatum['name'];
    $country = $post_metadatum['country'];

    $contents[0]['content'] = $name . $from . $country . $signed;
    $contents[0]['align'] = 'center';

    $contents[1]['content'] = $custom_message ? $custom_message : $description;
    $contents[1]['align'] = 'left';
    
    return set_template_message($contents);
}

function signer_email_acknowledgment($post_id){
    $acknowledgment_msg = get_post_meta($post_id, 'signer_email_message', true );
    $description = get_post_meta($post_id, 'petition_form_share_description', true ).':';

    $content_link =  ['facebook' => 'Facebook', 'twitter' => 'Twitter']; 
    $share = share_links($description, $post_id, $content_link); 

    $contents[0]['content'] = $acknowledgment_msg;
    $contents[0]['align'] = 'center';
    
    $contents[1]['content'] = $share['facebook']." / ".$share['twitter'];
    $contents[1]['align'] = 'center';

    return set_template_message($contents);
}

