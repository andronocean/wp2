<?php
// Path: wp-content/plugins/wp2-style/src/Types/Kind/init.php

namespace WP2_Style\Types\Kind;

/**
 * Kind Taxonomy Controller.
 *
 * Registers and configures the custom taxonomies for WP2 Style Entity kinds.
 */
class Controller
{
    /**
     * Textdomain for localization.
     *
     * @var string
     */
    private string $text_domain = 'wp2-style';

    /**
     * Prefix for related meta or taxonomy keys.
     *
     * @var string
     */
    private string $prefix = 'wp2_style_theme_';

    /**
     * Taxonomies configuration.
     *
     * @var array
     */
    private array $taxonomies = [
        'area' => [
            'name'        => 'Areas',
            'singular'    => 'Area',
            'slug'        => 'theme-areas',
            'description' => 'The areas in the theme.'
        ],
        'layout' => [
            'name'        => 'Layouts',
            'singular'    => 'Layout',
            'slug'        => 'theme-layouts',
            'description' => 'The layouts in the theme.'
        ],
        'part' => [
            'name'        => 'Part Blocks',
            'singular'    => 'Part Block',
            'slug'        => 'theme-parts',
            'description' => 'The parts in the theme.',
            'menu_name'   => 'Blocks',
        ],
        'template' => [
            'name'        => 'Templates',
            'singular'    => 'Template',
            'slug'        => 'theme-templates',
            'description' => 'The templates in the theme.'
        ],
        'zone' => [
            'name'        => 'Zones',
            'singular'    => 'Zone',
            'slug'        => 'theme-zones',
            'description' => 'The zones in the theme.'
        ],
    ];

    /**
     * Post types to which these taxonomies apply.
     *
     * @var array
     */
    private array $post_types = [
        'wp2_style_entity',
    ];

    /**
     * Constructor.
     *
     * Hooks into WordPress to register the taxonomies and ensure required terms.
     */
    public function __construct()
    {
        add_action('init', [$this, 'register_types'], 110);
    }

    /**
     * Registers all custom taxonomies.
     *
     * @return void
     */
    public function register_types(): void
    {
        foreach ($this->taxonomies as $key => $taxonomy) {
            $args = $this->set_args(
                $taxonomy['name'],      // Plural name.
                $taxonomy['singular'],  // Singular name.
                $taxonomy['slug'],      // Rewrite slug.
                $taxonomy['description'],
                $taxonomy['menu_name'] ?? $taxonomy['name']
            );
            // Build the taxonomy name using the prefix and the key.
            register_taxonomy($this->prefix . $key, $this->post_types, $args);
        }
    }

    /**
     * Sets and returns the labels for the custom taxonomy.
     *
     * @param string $singular Singular name.
     * @param string $plural   Plural name.
     * @return array Associative array of taxonomy labels.
     */
    private function set_labels(string $singular, string $plural, string $menu_name): array
    {
        $text_domain = $this->text_domain;

        return [
            'name'              => _x($plural, 'taxonomy general name', $text_domain),
            'singular_name'     => _x($singular, 'taxonomy singular name', $text_domain),
            'search_items'      => __('Search ' . $plural, $text_domain),
            'all_items'         => __('All ' . $plural, $text_domain),
            'parent_item'       => __('Parent ' . $singular, $text_domain),
            'parent_item_colon' => __('Parent ' . $singular . ':', $text_domain),
            'edit_item'         => __('Edit ' . $singular, $text_domain),
            'update_item'       => __('Update ' . $singular, $text_domain),
            'add_new_item'      => __('Add New ' . $singular, $text_domain),
            'new_item_name'     => __('New ' . $singular . ' Name', $text_domain),
            'menu_name'         => __($menu_name, $text_domain),
        ];
    }

    /**
     * Sets and returns the arguments for registering the custom taxonomy.
     *
     * @param string $plural
     * @param string $singular
     * @param string $slug
     * @param string $description
     * @return array Associative array of taxonomy arguments.
     */
    private function set_args(string $plural, string $singular, string $slug, string $description, string $menu_name): array
    {
        $labels = $this->set_labels($singular, $plural, $menu_name);

        return [
            'labels'            => $labels,
            'description'       => $description,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'default_term'      => [
                'name' => 'Undefined',
                'slug' => 'undefined',
            ],
            'rewrite'           => ['slug' => $slug],
            'sort'              => true,
            'show_in_rest'      => true,
            'rest_base'         => $slug,
            'rest_namespace'   => 'wp2-style/v1',
        ];
    }
}

new Controller();