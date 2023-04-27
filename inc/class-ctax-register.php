<?php
/**
 * Custom taxonomies register
 *
 * @package Wider-Themes
 * @since   1.0
 */

class EFramework_CTax_Register
{
    /**
     * Core singleton class
     *
     * @var self - pattern realization
     * @access private
     */
    private static $_instance;

    /**
     * Store supported taxonomies in an array
     * @var array
     * @access private
     */
    private $taxonomies = array();

    /**
     * Constructor
     *
     * @access private
     */
    function __construct()
    {
        add_action('init', array($this, 'init'), 0);
    }

    /**
     * init hook - 0
     */
    function init()
    {
        $this->taxonomies = apply_filters('evolt_extra_taxonomies', array(
            'portfolio-category' => array(
                'status'     => true,
                'post_type'  => array('portfolio'),
                'taxonomy'   => esc_html__('Portfolio Category', EVOLT_TEXT_DOMAIN),
                'taxonomies' => esc_html__('Portfolio Categories', EVOLT_TEXT_DOMAIN),
                'args'       => array(),
                'labels'     => array()
            ),
        ));
        foreach ($this->taxonomies as $key => $evolt_taxonomy) {
            if ($evolt_taxonomy['status'] === true) {
                $categories = array_merge(array(
                    'hierarchical'       => true,
                    'show_ui'            => true,
                    'show_in_menu'       => true,
                    'show_in_nav_menus'  => true,
                    'show_admin_column'  => true,
                    'show_in_rest'       => true,
                    'show_in_quick_edit' => true,
                    'labels'             => array_merge(array(
                        'name'              => $evolt_taxonomy['taxonomies'],
                        'singular_name'     => $evolt_taxonomy['taxonomy'],
                        'edit_item'         => esc_html__('Edit', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomy'],
                        'update_item'       => esc_html__('Update', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomy'],
                        'add_new_item'      => esc_html__('Add New', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomy'],
                        'new_item_name'     => esc_html__('New Type', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomy'],
                        'all_items'         => esc_html__('All', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomies'],
                        'search_items'      => esc_html__('Search', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomy'],
                        'parent_item'       => esc_html__('Parent', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomy'],
                        'parent_item_colon' => esc_html__('Parent', EVOLT_TEXT_DOMAIN) . ' ' . $evolt_taxonomy['taxonomy'] . ':',
                    ), $evolt_taxonomy['labels']),
                    'rewrite'      => array(
	                    'slug' => $key
                    )
                ), $evolt_taxonomy['args']);

                register_taxonomy($key, $evolt_taxonomy['post_type'], $categories);
            }
        }

    }

    /**
     * Get instance of the class
     *
     * @access public
     * @return object this
     */
    public static function get_instance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
}