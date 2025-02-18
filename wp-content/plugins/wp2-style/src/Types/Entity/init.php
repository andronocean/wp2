<?php
// Path: wp-content/plugins/wp2-style/src/Types/Entity/init.php

namespace WP2_Style\Types\Entity;

/**
 * Entity Post Type Controller.
 *
 * Registers and manages the custom post type for WP2 Style Entities,
 * including REST API integration and custom taxonomy filtering.
 */
class Controller
{

    /**
     * Textdomain used for localization.
     *
     * @var string
     */
    private string $text_domain = 'wp2-style';

    /**
     * Custom post type identifier.
     *
     * @var string
     */
    private string $single_key = 'wp2_style_entity';

    /**
     * Custom slug for URL rewriting.
     *
     * @var string
     */
    private string $single_slug = 'wp2-style';

    /**
     * Archive slug for the custom post type.
     *
     * @var string
     */
    private string $single_archive = 'wp2-style';

    /**
     * Labels for the custom post type.
     *
     * @var array
     */
    private array $single_labels = [
        'archive_name' => 'Style',
        'singular'     => 'Entity',
        'plural'       => 'Entities',
    ];

    /**
     * Taxonomy key for entity kinds.
     *
     * @var string
     */
    private string $kind_key = 'wp2_style_entity_kind';

    /**
     * REST API parameter key for entity kinds.
     *
     * @var string
     */
    private string $kind_param = 'wp2_style_entity_kinds';

    /**
     * Whether the custom post type should appear in the admin menu.
     *
     * @var bool
     */
    private bool $show_in_menu = true;

    /**
     * REST API namespace.
     *
     * @var string
     */
    private string $rest_namespace = 'wp2-style/v1';

    /**
     * REST API base route.
     *
     * @var string
     */
    private string $rest_base = 'template-parts';

    /**
     * Constructor.
     *
     * Initializes actions and filters for the custom post type.
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type'], 101);
        add_action('init', [$this, 'register_meta'], 102);
    }

    /**
     * Registers the custom post type.
     *
     * @return void
     */
    public function register_post_type(): void
    {
        $type = $this->single_key;

        $type_args = [
            'slug'         => $this->single_slug,
            'archive'      => $this->single_archive,
            'show_in_menu' => $this->show_in_menu,
            'taxonomies'   => [$this->kind_key],
        ];

        $type_args['labels'] = $this->set_labels([
            'plural'      => $this->single_labels['plural'],
            'singular'    => $this->single_labels['singular'],
            'menu_name'   => $this->single_labels['archive_name'],
            'text_domain' => $this->text_domain,
        ]);

        $type_args = $this->set_args($type_args);

        register_post_type($type, $type_args);
    }

    /**
     * Generates labels for the custom post type.
     *
     * @param array $label_args {
     *     Array of arguments for generating labels.
     *
     *     @type string $plural      Plural label.
     *     @type string $singular    Singular label.
     *     @type string $menu_name   Menu name label.
     *     @type string $text_domain Text domain for localization.
     * }
     * @return array Array of labels for the custom post type.
     */
    private function set_labels(array $label_args): array
    {
        $plural      = $label_args['plural'];
        $singular    = $label_args['singular'];
        $menu_name   = $label_args['menu_name'];
        $text_domain = $label_args['text_domain'];

        return [
            'name'               => _x($plural, 'post type general name', $text_domain),
            'singular_name'      => _x($singular, 'post type singular name', $text_domain),
            'menu_name'          => _x($menu_name, 'admin menu', $text_domain),
            'name_admin_bar'     => _x($singular, 'add new on admin bar', $text_domain),
            'add_new'            => _x('New', $singular, $text_domain),
            'add_new_item'       => __('New ' . $singular, $text_domain),
            'new_item'           => __('New ' . $singular, $text_domain),
            'edit_item'          => __('Edit ' . $singular, $text_domain),
            'view_item'          => __('View ' . $singular, $text_domain),
            'all_items'          => __($plural, $text_domain),
            'search_items'       => __('Search ' . $plural, $text_domain),
            'parent_item_colon'  => __('Parent ' . $plural . ':', $text_domain),
            'not_found'          => __('No ' . $plural . ' found.', $text_domain),
            'not_found_in_trash' => __('No ' . $plural . ' found in Trash.', $text_domain),
        ];
    }

    /**
     * Generates arguments for registering the custom post type.
     *
     * @param array $type_args {
     *     Array of initial arguments.
     *
     *     @type string $slug         Custom slug for URL rewriting.
     *     @type string $archive      Archive slug.
     *     @type bool   $show_in_menu Whether to show in admin menu.
     *     @type array  $labels       Array of labels for the post type.
     * }
     * @return array Array of arguments for register_post_type().
     */
    private function set_args(array $type_args): array
    {
        $labels       = $type_args['labels'];
        $slug         = $type_args['slug'];
        $archive      = $type_args['archive'];
        $show_in_menu = $type_args['show_in_menu'];

        return [
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => $show_in_menu,
            'query_var'          => true,
            'rewrite'            => [
                'slug'       => $slug,
                'with_front' => false,
            ],
            'capability_type'    => 'post',
            'has_archive'        => $archive,
            'hierarchical'       => true,
            'rest_base'          => $this->rest_base,
            'rest_namespace'     => $this->rest_namespace,
            'supports'           => [
                'title',
                'editor',
                'thumbnail',
                'excerpt',
                'comments',
                'page-attributes',
                'custom-fields',
                'revisions',
            ],
            'show_in_rest'       => true,
        ];
    }


    public function register_meta()
    {
        $post_type = 'wp2_style_entity';

        $prefix = 'wp2_style_entity_';

        $meta_keys = [
            'template',
            'zone_area',
            'zone',
            'area',
            'layout',
            'template_part',
        ];


        foreach ($meta_keys as $meta_key) {
            $key = $prefix . $meta_key;
            register_post_meta($post_type, $key, [
                'show_in_rest' => true,
                'single'       => true,
                'type'         => 'string',
            ]);
        }
    }
}

new Controller();