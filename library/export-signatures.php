<?php 

function admin_post_list_add_export_button( $which ) {
    global $typenow;
  
    if ( 'signature' === $typenow && 'top' === $which && (!isset($_GET['post_status']) || $_GET['post_status'] === "all" ||  $_GET['post_status'] === "publish" ) ) {
        ?>
        <input type="submit" name="export_all_posts" class="button button-primary" value="<?php _e('Export signatures'); ?>" />
        <?php
    }
}
 
add_action( 'manage_posts_extra_tablenav', 'admin_post_list_add_export_button', 20, 1 );

function func_export_all_posts() {
    if(isset($_GET['export_all_posts'])) {
        $args = [
            'post_type' => 'signature',
            'ignore_filtering' => true,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ];

        if(isset($_GET['admin_filter_petition']) && !empty($_GET['admin_filter_petition'])) {
            $args['meta_query'] = [
                [
                    'key' => 'petition_id',
                    'value' => $_GET['admin_filter_petition'],
                    'compare' => '='
                ]
            ];
        } else if(isset($_GET['post'])) {
            $args['post__in'] = $_GET['post'];
        }
  
        global $post;
        $arr_post = get_posts($args);

        // var_dump($arr_post);
        
        if ($arr_post) {
  
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="wp-posts.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
  
            $file = fopen('php://output', 'w');
  
            // fputcsv($file, array('Post Title', 'URL', 'Categories', 'Tags'));
            fputcsv($file, array('Name', 'Email', 'Phone', 'Country', 'Keep me updated', 'Petition', 'Date'));
  
            foreach ($arr_post as $post) {
                setup_postdata($post);
                $name = get_post_meta(get_the_ID(), 'name', true);
                $phone = get_post_meta(get_the_ID(), 'phone', true);
                $email = get_post_meta(get_the_ID(), 'email', true);
                $country = get_post_meta(get_the_ID(), 'country', true);
                $keep_me_updated = get_post_meta(get_the_ID(), 'keep_me_updated', true); 
                $petition = get_the_title(strval($_GET['admin_filter_petition']));
                $date = get_the_date();


                if($keep_me_updated === "on") {
                    $keep_me_updated = "Yes";
                } else {
                    $keep_me_updated = "No";
                }

                fputcsv($file, array($name, $email, $phone, $country, $keep_me_updated, $petition, $date));
            }
  
            exit();
        }
    }
}
 
add_action( 'init', 'func_export_all_posts' );