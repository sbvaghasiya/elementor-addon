<?php

if(!function_exists('evolt_print_html')){
    function evolt_print_html($content){
        echo $content;
    }
}

if(!function_exists('evolt_print_shortcode')) {
    function evolt_print_shortcode($content)
    {
        echo do_shortcode($content);
    }
}

if(!function_exists('evolt_register_shortcode')) {
    function evolt_register_shortcode($tag, $callback)
    {
        add_shortcode($tag, $callback);
    }
}

if(!function_exists('evolt_register_wp_widget')) {
    function evolt_register_wp_widget($class)
    {
        register_widget($class);
    }
}

if(!function_exists('replace_all_special_character')){
    function replace_all_special_character($subject = '', $replace = '_'){
        return preg_replace('/[^A-Za-z0-9]/', $replace, $subject);
    }
}

if(!function_exists('evolt_get_template') && !function_exists('evolt_get_locate_template')){
    function evolt_get_template($widget, $template_path = '', $default_path = ''){
        $settings = $widget->get_settings_for_display();
        $settings['element_id'] = $widget->get_id();
        $settings['element_name'] = $widget->get_name();
        $template_name = $widget->get_name();
        $layout = (isset($settings['layout']) && !empty($settings['layout']))?$settings['layout']:'1';

        $located = evolt_get_locate_template($template_name, $layout, $template_path, $default_path);

        if (!file_exists($located)) {
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '1.0');

            return false;
        }

        $located = apply_filters('evolt_template_part', $located, $template_name, $settings, $template_path, $default_path);

        include($located);

    }

    function evolt_get_locate_template($template_name, $layout = '1', $template_path = '', $default_path = '')
    {
        $layout_name = 'layout' . $layout . '.php';
        if (!$template_path) {
            $template_path = apply_filters('evolt_template_path', 'elementor/templates/widgets/' . $template_name . '/');
        }

        if (!$default_path) {
            $default_path = EVOLT_PATH . 'templates/widgets/' . $template_name . '/';
        }

        // Look within passed path within the theme - this is priority.
        $template = locate_template(
            array(
                trailingslashit($template_path) . $layout_name,
                $layout_name
            )
        );

        // Get default template/
        if (!$template) {
            $template = $default_path . $layout_name;
        }

        // Return what we found.
        return apply_filters('evolt_locate_template', $template, $template_name, $template_path);
    }
}

if(!function_exists('evolt_get_post_types')){
    function evolt_get_post_types(){

    }
}
if(!function_exists('evolt_get_post_type_options')){
    function evolt_get_post_type_options(){
        $post_types = get_post_types([
            'public'   => true,
            //'_builtin' => false
        ], 'objects');
        $DefaultExcludedPostTypes = [
            'page',
            'attachment',
            'revision',
            'nav_menu_item',
            'vc_grid_item',
            'custom_css',
            'customize_changeset',
            'oembed_cache',
            'evolt-mega-menu',
            'elementor_library',
        ];
        $ExtraExcludedPostTypes = apply_filters('evolt_get_post_types', []);
        $excludedPostTypes      = array_merge($DefaultExcludedPostTypes, $ExtraExcludedPostTypes );

        $result = [];
        if (!is_array($post_types))
            return $result;
        foreach ($post_types as $post_type) {
            if (!$post_type instanceof WP_Post_Type)
                continue;
            if (in_array($post_type->name, $excludedPostTypes))
                continue;
            $result[$post_type->name] = $post_type->labels->singular_name;;
        }
  
        return $result;
    }
}
if(!function_exists('evolt_get_post_categories')){
    function evolt_get_post_categories(){
        return get_terms('category', array(
            'hide_empty' => false,
            'order' => 'desc',
        ));
    }
    if(!function_exists('evolt_get_post_categories_options')){
        function evolt_get_post_categories_options(){
            $categories = evolt_get_post_categories();
            $options = array();
            if(!is_wp_error($categories)){
                foreach ($categories as $cat){
                    $options[$cat->slug . "|" . "category"] = $cat->name;
                }
            }
            return $options;
        }
    }
}

if(!function_exists('evolt_get_element_id')){
    function evolt_get_element_id($settings){
        return $settings['element_name'] . '-' . $settings['element_id'];
    }
}

if(!function_exists('evolt_get_grid_term_list')){
    function evolt_get_grid_term_list($post_type, $taxonomy = array())
    {
        if (empty($taxonomy)) {
            $taxonomy = get_object_taxonomies($post_type, 'names');
        }
        $term_list = array();
        $term_list['terms'] = array();
        $term_list['auto_complete'] = array();
        foreach ($taxonomy as $tax) {
            $terms = get_terms(
                array(
                    'taxonomy' => $tax,
                    'hide_empty' => true,
                )
            );
            foreach ($terms as $term) {
                $term_list['terms'][] = $term->slug . '|' . $tax;
                $term_list['auto_complete'][] = array(
                    'value' => $term->slug . '|' . $tax,
                    'label' => $term->name,
                );
            }
        }

        return $term_list;
    }
}

if(!function_exists('evolt_get_grid_term_options')){
    function evolt_get_grid_term_options($post_type, $taxonomy = array())
    {
        if (empty($taxonomy)) {
            $taxonomy = get_object_taxonomies($post_type, 'names');
        }
        $term_list = array();
        foreach ($taxonomy as $tax) {
            $terms = get_terms(
                array(
                    'taxonomy' => $tax,
                    'hide_empty' => true,
                )
            );
            foreach ($terms as $term) {
                $term_list[$term->slug . '|' . $tax] = $term->name;
            }
        }

        return $term_list;
    }
}

if(!function_exists('evolt_get_posts_of_grid')) {
    function evolt_get_posts_of_grid($post_type = 'post', $atts = array(), $taxonomy = array(), $args_extra = array())
    {
        extract($atts);
        if (!empty($post_ids)) {
            $evolt_query = new WP_Query(array(
                'post_type' => $post_type,
                'post__in' => array_map('intval', explode(',', $post_ids))
            ));
            $evolt_paged = 1;
            $posts = $evolt_query->query($evolt_query->query_vars);
        } else {
            $args = array(
                'post_type' => $post_type,
                'posts_per_page' => !empty($limit) ? intval($limit) : 6,
                'order' => !empty($order) ? $order : 'DESC',
                'orderby' => !empty($orderby) ? $orderby : 'date',
                'tax_query' => array(
                    'relation' => 'OR',
                )
            );
            $args = array_merge($args, $args_extra);
            if($currentPostId = get_the_ID()){
                $args['post__not_in'] = [ $currentPostId ];
            }

            // if select term on custom post type, move term item to cat.
            if (!empty($source)) {
                foreach ($source as $terms) {
                    $tmp = explode('|', $terms);
                    if (isset($tmp[0]) && isset($tmp[1])) {
                        $args['tax_query'][] = array(
                            'taxonomy' => $tmp[1],
                            'field' => 'slug',
                            'operator' => 'IN',
                            'terms' => array($tmp[0]),
                        );
                    }
                }
            }
            if (get_query_var('paged')) {
                $evolt_paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $evolt_paged = get_query_var('page');
            } else {
                $evolt_paged = 1;
            }

            $evolt_query = new WP_Query($args);
            $evolt_query->set('paged', intval($evolt_paged));
            $evolt_query->set('posts_per_page', !empty($limit) ? intval($limit) : 6);
            $query = $evolt_query->query($evolt_query->query_vars);
            $posts = $query;
        }

        if (empty($source)) {
            $source_new = evolt_get_grid_term_list($post_type, $taxonomy);
            $categories = $source_new['terms'];
        }
        else{
            $categories = $source;
        }
        global $wp_query;
        $wp_query = $evolt_query;
        $pagination = get_the_posts_pagination(array(
            'screen_reader_text' => '',
            'mid_size' => 2,
            'prev_text' => esc_html__('Back', EVOLT_TEXT_DOMAIN),
            'next_text' => esc_html__('Next', EVOLT_TEXT_DOMAIN),
        ));
        global $paged;
        $paged = $evolt_paged;
        $categories = is_array($categories) ? $categories : array();

        wp_reset_query();

        return array(
            'posts' => $posts,
            'categories' => $categories,
            'query' => $evolt_query,
            'args' => $args,
            'paged' => $paged,
            'max' => $evolt_query->max_num_pages,
            'next_link' => next_posts($evolt_query->max_num_pages, false),
            'total' => $evolt_query->found_posts,
            'pagination' => $pagination
        );
    }
}

if(!function_exists('evolt_get_term_of_post_to_class')){
    function evolt_get_term_of_post_to_class($post_id, $tax = array())
    {
        $term_list = array();
        foreach ($tax as $taxo) {
            $term_of_post = wp_get_post_terms($post_id, $taxo);
            foreach ($term_of_post as $term) {
                $term_list[] = $term->slug;
            }
        }

        return implode(' ', $term_list);
    }
}

if(!function_exists('evolt_get_all_page')){
    function evolt_get_all_page(){
        $all_posts = get_posts( array(
                'posts_per_page'    => -1,
                'post_type'         => 'page',
            )
        );
        if( !empty( $all_posts ) && !is_wp_error( $all_posts ) ) {
            foreach ( $all_posts as $post ) {
                $options[ $post->ID ] = strlen( $post->post_title ) > 20 ? substr( $post->post_title, 0, 20 ).'...' : $post->post_title;
            }
        }
        return $options;
    }
}