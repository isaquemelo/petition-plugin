<?php

add_action('cmb2_admin_init', function() {
    
    $cmb_petition_page = new_cmb2_box([
        'id' => 'petition_page_metabox',
        'title' => 'Petitions details',
        'object_types' => ['petition'],
        'priority' => 'high',
    ]);

    $cmb_petition_page->add_field([
        'name' => 'Select a parent petition',
        'description' => 'The count will be sent to the parent',
        'id' => 'petition_parent',
        'type' => 'post_search_text',
        'post_type'   => 'petition',
        'select_type' => 'radio',
        'select_behavior' => 'replace',
    ]);

    $cmb_petition_page->add_field([
        'name' => 'Target e-mail',
        'description' => 'Leave it empty if you don\'t  want to send an e-mail to a target',
        'id' => 'petition_target_email',
        'type' => 'text',
    ]);

    $cmb_petition_page->add_field([
        'name' => 'Goal',
        'id' => 'petition_goal',
        'type' => 'text',
    ]);

    $cmb_petition_page->add_field([
        'name' => 'Display counting?',
        'id' => 'petition_display_counting',
        'type' => 'checkbox',
        'default' => true,
    ]);

    $cmb_petition_page->add_field([
        'name' => 'E-mail after signature',
        'id' => 'petition_email_signature',
        'type' => 'textarea',
        'default' => 'Thank you for your signature!',
    ]);

    $cmb_petition_form = new_cmb2_box([
        'id' => 'petition_form_metabox',
        'title' => 'Fields translation',
        'object_types' => ['petition'],
        'priority' => 'high',
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Join us',
        'id' => 'petition_form_join_title',
        'type' => 'text',
        'default' => 'Join the cause!'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Signatures',
        'id' => 'petition_form_signatures',
        'type' => 'text',
        'default' => 'Signatures'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'The goal',
        'id' => 'petition_form_goal',
        'type' => 'text',
        'default' => 'The goal'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Recently submission',
        'id' => 'petition_form_submission',
        'type' => 'text',
        'default' => 'signed recently'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Add your sign now',
        'id' => 'petition_form_add_sign',
        'type' => 'text',
        'default' => 'Add your sign now'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Name',
        'id' => 'petition_form_nome',
        'type' => 'text',
        'default' => 'Your name'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'E-mail',
        'id' => 'petition_form_email',
        'type' => 'text',
        'default' => 'E-mail'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Country',
        'id' => 'petition_form_country',
        'type' => 'text',
        'default' => 'Country'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Please keep me updated checkbox',
        'id' => 'petition_form_keep_me_updated',
        'type' => 'text',
        'default' => 'Please keep me updated'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Is "keep me updated" mandatory?',
        'id' => 'petition_form_enable_keep_me_updated',
        'type' => 'checkbox',
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Terms of use textfield (HTML allowed)',
        'id' => 'petition_terms_text',
        'type' => 'textarea_code',
        'default' => 'I accept the terms of use',
        'sanitization_cb' => 'petition_terms_text_callback', // function should return a sanitized value
    ]);
   
    // Signature post type fields
    $cmb_signature = new_cmb2_box([
        'id' => 'singature_metabox',
        'title' => 'Signature meta',
        'object_types' => ['signature'],
        'priority' => 'high',
    ]);

    $cmb_signature->add_field([
        'name' => 'Petition ID',
        'id' => 'petition_id',
        'type' => 'text',
    ]);

    $cmb_signature->add_field([
        'name' => 'E-mail',
        'id' => 'email',
        'type' => 'text',
    ]);

    $cmb_signature->add_field([
        'name' => 'Name',
        'id' => 'name',
        'type' => 'text',
    ]);

    $cmb_signature->add_field([
        'name' => 'Country',
        'id' => 'country',
        'type' => 'text',
    ]);

    $cmb_signature->add_field([
        'name' => 'Keep me updated',
        'id' => 'keep_me_updated',
        'type' => 'checkbox',
    ]);
});


function petition_terms_text_callback($value) {
       /*
     * Do your custom sanitization. 
     * strip_tags can allow whitelisted tags
     * http://php.net/manual/en/function.strip-tags.php
     */
    $value = strip_tags( $value, '<p><a><br><br/>' );

    return $value;
}