<div class="reveal-overlay" id="modal-petition-wrapper"></div>

<div class="reveal" id="modal-petition" data-reveal>
    <div class="modal-petition-content text-center">
        <img class="img-block mb20" src="/wp-content/plugins/petitions/images/icon-petition-dark.png" alt="" width="55">
        <h4 class="petition-title mb40"><?= $instance['title'] ?></h4>
        <p class="fz16"><?= $instance['text'] ?></p>

        <?php 
        include (__DIR__.'/../../../petition-share.php');

        global $post;
        $post = get_page_by_path('petition-modal', OBJECT, 'layout_parts');
        
        if($post){
            setup_postdata($post);
            the_content();
        }

        wp_reset_postdata();
        ?>
    </div>
    <button class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
    </button>
</div>