<?php 
include __DIR__ . '/../library/countries.php';

get_header('single');
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

include include __DIR__ . '/../library/utils.php';

?>

<div class="petition">
    <div class="petition--header<?= empty(get_the_content())? ' empty-content' : ' has-content' ?><?= has_post_thumbnail()? ' has-thumbnail' : ' no-thumb' ?>">
        <div class="featured-image">
            <?php the_post_thumbnail( 'large' ); ?>
        </div>

        <div class="petition--content<?= empty(get_the_content())? ' empty-content' : ' has-content' ?><?= has_post_thumbnail()? ' has-thumbnail' : ' no-thumb' ?>">
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
                                            [
                                                'key' => 'petition_id',
                                                'value' => $petition_id,
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

                            <?php 
                                $default_country = get_post_meta(get_the_ID(), 'petition_default_country', true );
                            ?>
                            
                                
                            <div class="petition-form">
                                <form action="?" method="POST" id="petition-form" data-petition-id="<?= $petition_id ?>" onsubmit="return false;">
                                    <input type="text" name="name" placeholder="<?= get_post_meta(get_the_ID(), 'petition_form_nome', true ) ?>" required>
                                    <input type="email" name="email" placeholder="<?= get_post_meta(get_the_ID(), 'petition_form_email', true ) ?>" required>
                                    <input type="tel" name="phone" placeholder="<?= get_post_meta(get_the_ID(), 'petition_form_phone', true ) ?>">
                                    
                                    <fieldset>
                                        <!-- <label for="country">
                                            <?= get_post_meta(get_the_ID(), 'petition_form_country', true ) ?>
                                        </label> -->
                                        
                                        <select id="country" name="country" required>
                                            <option value="0" label="" disabled selected default><?= get_post_meta(get_the_ID(), 'petition_form_country', true ) ?></option>
                                            <?php foreach ($country_array as $country): ?>
                                                <option value="<?= $country ?>" <?= $country == $default_country? "selected" : "" ?>><?= $country ?></option>
                                            <?php endforeach ?>
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
                    
                    <div class="sidebar-content">
                        <?php 
                            $share_title = get_post_meta($child_id, 'petition_form_share_title', true);
                            $description = get_post_meta($child_id, 'petition_form_share_description', true);

                            if(!empty($share_title)): 
                        ?>
                            <div class="share-petition">
                                <div class="share-petition--title">
                                    <h3><?= $share_title ?></h3>
                                </div>

                                <div class="share-petition--content">
                                    <?= $description ?>
                                </div>
                                <?php 

                                $icons = [
                                          'facebook'=>'<i class="fab fa-facebook-f"></i>', 
                                          'twitter'=>'<i class="fab fa-twitter"></i>'
                                        ]; 

                                $share = share_links($description, $child_id, $icons); ?>
                                
                                <div class="share-petition--networks">
                                    <div class="facebook">
                                        <?= $share['facebook'] ?>
                                    </div>
                                    <div class="twitter">
                                       <?= $share['twitter'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php dynamic_sidebar('petition_plugin_sidebar') ?>
                    </div>
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

