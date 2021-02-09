<?php 

    function get_logo_url(){
        return wp_get_attachment_image_src(get_theme_mod( 'custom_logo' ), 'full' )[0];
    }
    
    function set_template_message($contents){
        $template = file_get_contents(__DIR__ . '/../templates/email-template.html');

        $search = ['#site_url', '#logo', "#title", "#year"];
        $replace = [home_url(), get_logo_url(), get_bloginfo('name'), date('Y')];

        $template  = str_replace($search, $replace, $template);

        foreach ($contents as $key => $content)
            $template  = str_replace('#content'.$key, $content, $template);

        return $template;
    }
   

   function target_email_body($post_id, $post_metadatum){
        $from = " ".strtolower(get_post_meta($post_id, 'from_email_field', true ))." ";
        $signed = " ".strtolower(get_post_meta($post_id, 'signed_email_field', true ))." ";
        $description = get_post_meta($post_id, 'petition_form_share_description', true );
        $name = $post_metadatum['name'];
        $country = $post_metadatum['country'];

        $contents[0] = $name . $from . $country . $signed;
        $contents[1] = $description;

        return set_template_message($contents);
    }

    function signer_email_acknowledgment($post_id){
        $petition_url = get_permalink($post_id);
        $acknowledgment_msg = get_post_meta($post_id, 'signer_email_message', true );
        $description = urlencode(get_post_meta($post_id, 'petition_form_share_description', true ).':');

        $share_fb = "<a href='https://www.facebook.com/sharer/sharer.php?u=".$petition_url."'&quote=".$description."'>
                        share with Facebook
                    </a>"; 
        
        $share_tt = "<a href='https://twitter.com/intent/tweet?text=".$description.'&url='.$petition_url."'>
                        share with Twitter
                    </a>";

        $contents[0] = $acknowledgment_msg;
        $contents[1] = "Please, share with your friends this petition:".$share_fb." / ".$share_tt;

        return set_template_message($contents);
    }

