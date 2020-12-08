<?php

function add_petition_settings_page() {
    add_options_page("Petition Plugin", "Petition Plugin", "manage_options", "petition-plugin-options", "petition_options_page", null, 99);
}
add_action( 'admin_menu', 'add_petition_settings_page' );

function petition_options_page() {
    ?>
    <div class="wrap">
    <h1>Petition Settings</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields("petition-options-grp");
                do_settings_sections("petition-options");
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

function theme_section_description(){
    // echo '<p></p>';
}

function display_site_key(){
    $captcha_site_key = get_option( 'captcha_site_key' );
    echo '<input name="captcha_site_key" id="captcha_site_key" type="text" value="'. $captcha_site_key .'" class="code" /> ';
}

function display_secret(){
    $captcha_secret = get_option( 'captcha_secret' );
    echo '<input name="captcha_secret" id="captcha_secret" type="password" value="'. $captcha_secret .'" class="code" /> ';
}

function add_facebook_sections_fields(){
    //add_option('captcha_site_key', 0);
    add_settings_section( 'recaptcha_section', 'Google ReCaptcha', 'theme_section_description','petition-options');

    add_settings_field( 'captcha_site_key','Site Key','display_site_key','petition-options','recaptcha_section');
    register_setting( 'petition-options-grp', 'captcha_site_key');

    add_settings_field( 'captcha_secret','Secret','display_secret','petition-options','recaptcha_section');
    register_setting( 'petition-options-grp', 'captcha_secret');

}

add_action('admin_init','add_facebook_sections_fields');