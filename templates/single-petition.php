<?php 

include __DIR__ . '/../library/countries.php';
include __DIR__ . '/../library/utils.php';

get_header('single-petition');
the_post();

$petition_id = get_post_meta(get_the_ID(), 'petition_parent', true);
$child_id = get_the_ID();

if(empty($petition_id)){
    $petition_id = get_the_ID();
}

$signatures_count = count_signatures($petition_id);
$goal = get_post_meta($petition_id, 'petition_goal', true );

if(empty($goal)) {
    $goal = false;
}


?>

<div class="petition entry">
    <div class="petition--header<?= empty(get_the_content())? ' empty-content' : ' has-content' ?><?= has_post_thumbnail()? ' has-thumbnail' : ' no-thumb' ?>">
        <div class="featured-image">
            <?php the_post_thumbnail( 'large' ); ?>
        </div>

        <div class="petition--content<?= empty(get_the_content())? ' empty-content' : ' has-content' ?><?= has_post_thumbnail()? ' has-thumbnail' : ' no-thumb' ?> entry-content">
            <div class="language-selector">
            <?php 
                $petition_children = get_languages($petition_id);
                $original_post_language = get_the_terms($petition_id, 'languages');

                if(count($petition_children) > 0 ){
            ?>
                    <select class="languages-tab pt30" id="petition-language-selector">
                        <?php if($original_post_language): ?>
                            <option <?= $petition_id == get_the_ID() ? 'selected' : '' ?> value="<?= get_the_permalink($petition_id) ?>"><?= $original_post_language[0]->name ?></option>
                        <?php endif; ?>

                        <?php
                        foreach($petition_children as $p_child){
                            $languages = get_the_terms($p_child->post_id, 'languages');

                            if(count($languages) > 0){ ?>
                                    <?php if(isset($languages[0]->name)): ?>
                                        <option <?= $p_child->post_id == get_the_ID() ? 'selected': '' ?> value="<?= get_the_permalink($p_child->post_id) ?>"><?= $languages[0]->name ?></option>
                                    <?php endif; ?>
                                <?php
                            }
                        }
                        ?>
                    </select>
                <?php } ?>
            </div>
            <div class="content-wrapper">
                <div class="sidebar">
                    <div class="petition-block">
                        <div class="signatures-information">
                            <div class="signatures-count">
                                <?php 
                                    $show_count = get_post_meta(get_the_ID(), 'petition_display_counting', true );
                                    if($show_count):
                                ?>
                                    <div class="quantity">
                                        <span>
                                            <?= $signatures_count ?>
                                        </span>

                                        <span>
                                            <?= get_post_meta(get_the_ID(), 'petition_form_signatures', true ) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <div class="join">
                                    <?= get_post_meta(get_the_ID(), 'petition_form_join_title', true ) ?>
                                </div>

                                <?php if($goal && $show_count): ?>
                                    <div class="progress">
                                        <?php 
                                            if($signatures_count < $goal) {
                                                $complete = ($signatures_count / $goal) * 100;
                                            } else {
                                                $complete = 100;
                                            }
                                        ?>
                                        <div class="progress-bar" >
                                            <div class="progressed-area" style="width: <?= $complete ?>%"></div>
                                            <div class="progress-info">
                                                <span> <?= $signatures_count ?> </span>
                                                <span> <?= $goal ?> </span>
                                            </div>
                                        </div>

                                        <div class="progress-helper">
                                            <span> 
                                                <?= get_post_meta(get_the_ID(), 'petition_form_signatures', true ) ?>
                                            </span>
                                            <span>
                                                <?= get_post_meta(get_the_ID(), 'petition_form_goal', true ) ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <div class="signatures-history" data-signature-text="<?= get_post_meta($child_id, 'petition_form_submission', true) ?>">
                                <?php 
                                    // The Query
                                     $the_query = new WP_Query( [
                                        'post_type' => 'signature', 
                                        'meta_query' => [
                                            'relation' => 'AND',
                                            [
                                                'key' => 'petition_id',
                                                'value' => $petition_id,
                                                'compare' => '='
                                            ],
                                            [
                                                'key' => 'show_signature',
                                                'value' => true,
                                                'compare' => '='
                                            ]
                                        ],
                                        'posts_per_page' => get_post_meta($child_id, 'petition_signatures_shown', true)
                                    ] );
                                    
                                    // The Loop
                                    if ( $the_query->have_posts() &&  get_post_meta($child_id, 'petition_signatures_shown', true) !== '0') {
                                        while ( $the_query->have_posts() ) {
                                            $the_query->the_post(); ?>
                                            <div class="user-signature">
                                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-user fa-w-14 fa-3x"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" class=""></path></svg>
                                                <?= (get_post_meta(get_the_ID(), 'name', true) . " ". get_post_meta($child_id, 'petition_form_submission', true)); ?>
                                            </div>

                                        <?php
                                        }
                                    } else {
                                        // no posts found
                                    }
                                    /* Restore original Post Data */
                                    wp_reset_postdata();
                                    
                                
                                ?>
                            </div>

                            <?php 
                                if($signatures_count >= $goal): ?>
                                    <p class="goal-reached">
                                        <?= get_post_meta(get_the_ID(), 'petition_goal_reached_message', true ) ?>
                                    </p>
                                <?php endif;
                            ?>

                            <div class="petition-form">
                                <form action="?" method="POST" id="petition-form" data-petition-id="<?= $petition_id ?>" onsubmit="return false;">
                                    <input type="text" name="name" placeholder="<?= get_post_meta(get_the_ID(), 'petition_form_nome', true ) ?>" required>
                                    <input type="email" name="email" placeholder="<?= get_post_meta(get_the_ID(), 'petition_form_email', true ) ?>" required>
                                    
                                    <fieldset>
                                        <!-- <label for="country">
                                            <?= get_post_meta(get_the_ID(), 'petition_form_country', true ) ?>
                                        </label> -->
                                        
                                        <select id="country" name="country" required>
                                            <option value="0" label="" disabled selected default><?= get_post_meta(get_the_ID(), 'petition_form_country', true ) ?></option>
                                            <option value="Afganistan">Afghanistan</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Anguilla">Anguilla</option>
                                            <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Aruba">Aruba</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Bonaire">Bonaire</option>
                                            <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                            <option value="Brunei">Brunei</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Canary Islands">Canary Islands</option>
                                            <option value="Cape Verde">Cape Verde</option>
                                            <option value="Cayman Islands">Cayman Islands</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Channel Islands">Channel Islands</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Christmas Island">Christmas Island</option>
                                            <option value="Cocos Island">Cocos Island</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo">Congo</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Cote DIvoire">Cote DIvoire</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Curaco">Curacao</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="East Timor">East Timor</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Falkland Islands">Falkland Islands</option>
                                            <option value="Faroe Islands">Faroe Islands</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="French Guiana">French Guiana</option>
                                            <option value="French Polynesia">French Polynesia</option>
                                            <option value="French Southern Ter">French Southern Ter</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Gibraltar">Gibraltar</option>
                                            <option value="Great Britain">Great Britain</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Greenland">Greenland</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guadeloupe">Guadeloupe</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Hawaii">Hawaii</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hong Kong">Hong Kong</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="India">India</option>
                                            <option value="Iran">Iran</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Isle of Man">Isle of Man</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Korea North">Korea North</option>
                                            <option value="Korea Sout">Korea South</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Laos">Laos</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Macau">Macau</option>
                                            <option value="Macedonia">Macedonia</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Martinique">Martinique</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mayotte">Mayotte</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Midway Islands">Midway Islands</option>
                                            <option value="Moldova">Moldova</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montserrat">Montserrat</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Nambia">Nambia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherland Antilles">Netherland Antilles</option>
                                            <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                            <option value="Nevis">Nevis</option>
                                            <option value="New Caledonia">New Caledonia</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Niue">Niue</option>
                                            <option value="Norfolk Island">Norfolk Island</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau Island">Palau Island</option>
                                            <option value="Palestine">Palestine</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Phillipines">Philippines</option>
                                            <option value="Pitcairn Island">Pitcairn Island</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Republic of Montenegro">Republic of Montenegro</option>
                                            <option value="Republic of Serbia">Republic of Serbia</option>
                                            <option value="Reunion">Reunion</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="St Barthelemy">St Barthelemy</option>
                                            <option value="St Eustatius">St Eustatius</option>
                                            <option value="St Helena">St Helena</option>
                                            <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                            <option value="St Lucia">St Lucia</option>
                                            <option value="St Maarten">St Maarten</option>
                                            <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                            <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                            <option value="Saipan">Saipan</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="Samoa American">Samoa American</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Swaziland">Swaziland</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syria">Syria</option>
                                            <option value="Tahiti">Tahiti</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tokelau">Tokelau</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Erimates">United Arab Emirates</option>
                                            <option value="United States of America">United States of America</option>
                                            <option value="Uraguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Vatican City State">Vatican City State</option>
                                            <option value="Venezuela">Venezuela</option>
                                            <option value="Vietnam">Vietnam</option>
                                            <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                            <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                            <option value="Wake Island">Wake Island</option>
                                            <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zaire">Zaire</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>   
                                        

                                        <div>
                                            <input type="checkbox" id="accept-terms" name="accept-terms" required>
                                            <label for="accept-terms">
                                                <?= get_post_meta(get_the_ID(), 'petition_terms_text', true ) ?>
                                            </label>
                                        </div>
                                        
                                        <div>
                                            <!-- petition_form_enable_keep_me_updated     -->
                                            <?php $required_keep_me_updated = get_post_meta(get_the_ID(), 'petition_form_enable_keep_me_updated', true); ?>
                                            <input type="checkbox" id="keep-me-update" name="keep_me_updated" <?= $required_keep_me_updated? 'required' : '' ?>>
                                            <label for="keep-me-update">
                                                <?= get_post_meta(get_the_ID(), 'petition_form_keep_me_updated', true ) ?>
                                            </label>
                                        </div>

                                        <div>
                                            <input type="checkbox" id="show-signature" name="show_signature">
                                            <label for="show-signature">
                                                <?= get_post_meta(get_the_ID(), 'show_signature', true ) ?>
                                            </label>
                                        </div>

                                    </fieldset>

                                    <input type="text" id="petition-id" name="petition_id" hidden value="<?= $petition_id ?>">
                                    <input type="text" id="child_id" name="child_id" hidden value="<?= $child_id ?>">
                                    
                                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                    <?php if(!empty(get_option('captcha_site_key'))): ?>
                                    <button class="g-recaptcha hide" data-sitekey="<?= get_option('captcha_site_key') ?>" data-size="invisible">Submit</button>
                                    <?php endif; ?>

                                    <button style="width: 100%" class="button primary mt20 block"><?= get_post_meta(get_the_ID(), 'petition_submit_text', true) ?></button>
                                </form>

                                <div class="loading-area">
                                    <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                                </div>

                                <div class="success-message">
                                    <?= get_post_meta($child_id, 'petition_terms_thank_text', true); ?>
                                </div>

                                <div class="repeated-signature-message">
                                    <?= get_post_meta($child_id, 'petition_terms_repeated_signature_text', true); ?>
                                </div>
                            </div>

                        </div>


                    </div>

                    <?php dynamic_sidebar('petition_plugin_sidebar') ?>
                </div>

                <div class="post-content">
                    <?php the_content() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>

