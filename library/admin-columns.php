<?php


add_filter('manage_signature_posts_columns', 'signature_table_head');

function signature_table_head($defaults) {
    unset($defaults['title']);

    $temp = $defaults['date'];
    unset($defaults['date']);

    $defaults['name']  = 'Name';
    $defaults['petition'] = 'Petition';
    $defaults['country']   = 'Country';
    $defaults['date'] = $temp;

    return $defaults;
}

add_action('manage_signature_posts_custom_column', 'signature_table_content', 10, 2);

function signature_table_content($column_name, $post_id) {
    if ($column_name == 'name') {
        $name = get_post_meta($post_id, 'name', true);
        echo  $name;
    }

    if ($column_name == 'petition') {
        $petition_id = get_post_meta($post_id, 'petition_id', true);
        $petition_name = get_the_title($petition_id);
        $petition_permalink = get_edit_post_link($petition_id);

        echo "<a href=\"{$petition_permalink}\">{$petition_name} </a>";
    }

    if ($column_name == 'country') {
        $country = get_post_meta($post_id, 'country', true);
        echo  $country;
    }
}



if (is_admin()) {
    //this hook will create a new filter on the admin area for the specified post type
    add_action('restrict_manage_posts', function () {
        global $wpdb, $table_prefix;
        $post_type = (isset($_GET['post_type'])) ? $_GET['post_type'] : 'post';

        //only add filter to post type you want
        if ($post_type == 'signature') {
            //query database to get a list of years for the specific post type:
            $petitions_query = new WP_Query([
                'post_type' => 'petition',
                'ignore_filtering' => true,
            ]);

            $values = array();

            while ( $petitions_query->have_posts() ) {
                $petitions_query->the_post();
                $values[get_the_title()] = get_the_ID();
                //var_dump(get_post_meta(get_the_ID(), 'petition_parent', true));
            }

            //var_dump($values);

            wp_reset_postdata();

            //give a unique name in the select field
            ?>
            <select name="admin_filter_petition">
                <option value="">All petitions</option>

                <?php
                $current_v = isset($_GET['admin_filter_petition']) ? $_GET['admin_filter_petition'] : '';
                foreach ($values as $label => $value) {
                    printf(
                        '<option value="%s"%s>%s</option>',
                        $value,
                        $value == $current_v ? ' selected="selected"' : '',
                        $label
                    );
                }
                ?>
            </select>
            <?php
        }
    });

    //this hook will alter the main query according to the user's selection of the custom filter we created above:
    add_filter('parse_query', function ($query) {
        global $pagenow;
        $post_type = (isset($_GET['post_type'])) ? $_GET['post_type'] : 'post';

        if ($post_type == 'signature' && $pagenow == 'edit.php' && isset($_GET['admin_filter_petition']) && !empty($_GET['admin_filter_petition'])) {
            if(!$query->get('ignore_filtering') ) {
                $query->query_vars['meta_query'] = [
                    [
                        'key' => 'petition_id',
                        'value' => strval($_GET['admin_filter_petition']),
                        'compare' => 'IN'
                    ]
                ]; 
            }

            //$query->query_vars['post_type'] = 'post'; 
            //var_dump($query);
        }

        if ($post_type == 'petition' && $pagenow == 'edit.php') {
            if(!$query->get('ignore_filtering') ) {
                $query->query_vars['meta_query'] = [
                    [
                        'key' => 'petition_parent',
                        'compare' => 'NOT EXISTS'
                    ]
                ]; 
            }

            //$query->query_vars['post_type'] = 'post'; 
            //var_dump($query);
        }
    });
}


add_filter('manage_petition_posts_columns', 'petition_table_head');

function petition_table_head($defaults) {
    $defaults['signatures_count']  = 'Signatures';
    $defaults['child_petitions']  = 'Associated petitions';
    $defaults['view_signatures']  = '';
    return $defaults;
}

add_action('manage_petition_posts_custom_column', 'petition_table_content', 10, 2);

function petition_table_content($column_name, $post_id) {
    if ($column_name == 'signatures_count') {
        $count = count_signatures($post_id);
        echo  $count;
    }

    if ($column_name == 'view_signatures') {
        echo  "<a href=\"edit.php?post_type=signature&admin_filter_petition=$post_id\">View signatures</a>";
    }

    if ($column_name == 'child_petitions') {
        $petitions_query = new WP_Query([
            'post_type' => 'petition',
            'ignore_filtering' => true,
            'meta_query' => [
                [
                'key' => 'petition_parent',
                'value' => strval($post_id),
                'compare' => '='
                ]
            ]
        ]);

        // echo "<ul>";
        while ( $petitions_query->have_posts() ) {
            $petitions_query->the_post();
            // $values[get_the_title()] = get_the_ID();
            $petition_name = get_the_title();
            $petition_permalink = get_edit_post_link(get_the_ID());
    
            echo "<div>";
            echo "<a href=\"{$petition_permalink}\">{$petition_name} </a>";
            echo "</div>";
        }
        // echo "</ul>";

        //var_dump($values);

        wp_reset_postdata();

        $country = get_post_meta($post_id, 'country', true);
        // echo  "<a href=\"edit.php?post_type=signature&admin_filter_petition=$post_id\">View signatures</a>";
    }

    // if ($column_name == 'petition') {
    //     $petition_id = get_post_meta($post_id, 'petition_id', true);
    //     $petition_name = get_the_title($petition_id);
    //     $petition_permalink = get_edit_post_link($petition_id);

    //     echo "<a href=\"{$petition_permalink}\">{$petition_name} </a>";
    // }


}

