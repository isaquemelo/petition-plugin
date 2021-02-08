<?php

add_action('cmb2_admin_init', function () {

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
        'name' => 'Goal',
        'id' => 'petition_goal',
        'type' => 'text',
        'description' => "You don't need to fill this if this petition is associated with a parent",
        'render_row_cb' => 'cmb_goal_row_cb',
    ]);

    $cmb_petition_page->add_field([
        'name' => 'Quantity of signatures shown',
        'id' => 'petition_signatures_shown',
        'type' => 'text',
        'default' => 5,
        'render_row_cb' => 'cmb_goal_row_cb',

    ]);

    $cmb_petition_page->add_field([
        'name' => 'Display counting?',
        'id' => 'petition_display_counting',
        'type' => 'checkbox',
        'default' => true,
    ]);


    $cmb_petition_page->add_field([
        'name' => 'Goal reached message',
        'id' => 'petition_goal_reached_message',
        'type' => 'textarea',
        'default' => "Thanks for your support! We've managed to reach our goal. But, feel free to help us to increase this number!",
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
        'name' => 'Share title',
        'id' => 'petition_form_share_title',
        'type' => 'text',
        'default' => '',
        'description' => 'Leave empty to not create the share box'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Share description',
        'id' => 'petition_form_share_description',
        'type' => 'wysiwyg',
        'default' => '',
    ]);


    $cmb_petition_form->add_field([
        'name' => 'Signatures petition\'s field',
        'id' => 'petition_form_signatures',
        'type' => 'text',
        'default' => 'Signatures'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'From',
        'id' => 'from_email_field',
        'type' => 'text',
        'default' => 'From',
        'description' => '1st signer\'s email line: &lt;signature\'s name&gt; from &lt;country&gt; signed'
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Signed',
        'id' => 'signed_email_field',
        'type' => 'text',
        'default' => 'Signed',
        'description' => '1st signer\'s email line: &lt;signature\'s name&gt; from &lt;country&gt; signed'
    ]);

     $cmb_petition_form->add_field([
        'name' => 'Thank you signer email',
        'id' => 'thanks_email_field',
        'type' => 'text',
        'default' => 'Thank you for your signature! Please, share this petition with your friends:',
    ]);


    $cmb_petition_form->add_field([
        'name' => 'The goal',
        'id' => 'petition_form_goal',
        'type' => 'text',
        'default' => 'The goal',
        // 'render_row_cb' => 'cmb_goal_row_cb',

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
        'type' => 'textarea',
        'default' => 'I accept the terms of use',
        'sanitization_cb' => 'petition_terms_text_callback', // function should return a sanitized value
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Submit button',
        'id' => 'petition_submit_text',
        'type' => 'text',
        'default' => 'Sign now',
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Thank you message',
        'id' => 'petition_terms_thank_text',
        'description' => 'This message will be shown after the signature is made',
        'type' => 'textarea',
        'default' => 'Thank you for your signature!',
    ]);

    $cmb_petition_form->add_field([
        'name' => 'Repeated signature message',
        'id' => 'petition_terms_repeated_signature_text',
        'description' => 'This message will be shown after the signature is made',
        'type' => 'textarea',
        'default' => 'Signature already made!',
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

    // Emails details for target fields
    $cmb_target_email = new_cmb2_box([
        'id' => 'target_email_config',
        'title' => 'Email configuration for target',
        'object_types' => ['petition'],
    ]);

    $cmb_target_email->add_field([
        'name' => 'Target e-mail',
        'id' => 'petition_target_email',
        'description' => 'Leave it empty if you don\'t want to send an e-mail to a target',
        'type' => 'text',
    ]);

    $cmb_target_email->add_field([
        'name' => 'Subject e-mail',
        'id' => 'petition_target_subject',
        'description' => 'Leave it empty if you don\'t want to give a custom target\'s subject. By default, this field is the pettiton\'s name',
        'type' => 'text',
    ]);

    $cmb_target_email->add_field([
        'name' => 'Message body',
        'id' => 'message_target_email',
        'default' => 'Signed this petition',
        'type' => 'textarea',
    ]);


    // Emails details for signer
    $cmb_signer_email = new_cmb2_box([
        'id' => 'signer_email_config',
        'title' => 'Email configuration for signer',
        'object_types' => ['petition'],
    ]);

    $cmb_signer_email->add_field([
        'name' => 'Target e-mail',
        'id' => 'petition',
        'description' => 'Leave it empty if you don\'t want to send an e-mail to a target',
        'type' => 'text',
    ]);

    $cmb_signer_email->add_field([
        'name' => 'Subject',
        'id'   => 'subject_signer_email',
        'description' => 'The petition\'s name',
        'type' => 'title',
    ]);


    $cmb_signer_email->add_field([
        'name' => 'Message',
        'id'   => 'signer_email_message_label',
        'type' => 'title',
    ]);

     $cmb_signer_email->add_field([
        'name' => 'Thank you message',
        'id'   => 'signer_email_message',
        'type' => 'textarea',
        'default' => 'Thank you for your signature! Please, share this petition with your friends:',
    ]);

});

function petition_terms_text_callback($value) {
    /*
     * Do your custom sanitization. 
     * strip_tags can allow whitelisted tags
     * http://php.net/manual/en/function.strip-tags.php
     */
    $value = strip_tags($value, '<p><a><br><br/>');

    return $value;
}


function petition_goal_callback($value) {
    return $value;
}


function cmb_goal_row_cb($field_args, $field) {
    $id          = $field->args('id');
    $label       = $field->args('name');
    $name        = $field->args('_name');
    $value       = $field->escaped_value();
    $description = $field->args('description');
?>

    <div class="cmb-row cmb-type-text cmb2-id-petition-form-signatures table-layout" data-fieldtype="text">
        <div class="cmb-th">
            <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
        </div>

        <div class="cmb-td">
            <input id="<?php echo $id; ?>" type="number" min="0" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
            <p class="cmb2-metabox-description"><?= $description ?></p>
        </div>
    </div>
<?php
}
