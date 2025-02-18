<?php

namespace WP2_Style\Syncs\Template;

class Controller
{

    /**
     * Holds the template data.
     *
     * @var array
     */
    private $templates = [];

    /**
     * Constructor.
     *
     * Hooks into WordPress.
     */
    public function __construct()
    {
        add_action('init', [$this, 'init'], 101);
    }

    /**
     * Initializes the template sync.
     *
     * @return void
     */
    public function init(): void
    {
        $this->execute_sync();
    }

    /**
     * Execute the template sync process.
     *
     * Queries the taxonomy terms and creates HTML files in the theme's
     * /templates directory.
     *
     * @return void
     */
    public function execute_sync(): void
    {
        $this->generate_theme_template_files();
    }

    /**
     * Generate theme template files.
     *
     * @return void
     */
    private function generate_theme_template_files(): void
    {
        $templates = $this->get_theme_templates();

        foreach ($templates as $template) {
            $this->create_theme_template_file($template);
        }
    }

    /**
     * Get theme templates from the taxonomy.
     *
     * @return array
     */
    private function get_theme_templates(): array
    {
        $terms = get_terms([
            'taxonomy'   => 'wp2_style_theme_template',
            'hide_empty' => true,
        ]);

        $templates = [];

        if (! is_wp_error($terms) && ! empty($terms)) {
            foreach ($terms as $term) {
                $templates[] = [
                    'slug' => $term->slug,
                    'name' => $term->name,
                    'file' => $term->slug . '.html',
                    'id'   => $term->term_id,
                ];
            }
        }

        $this->templates = $templates;
        return $templates;
    }

    /**
     * Create a theme template file.
     *
     * @param array $template Template data.
     *
     * @return void
     */
    private function create_theme_template_file(array $template): void
    {
        $file_path = trailingslashit(get_template_directory()) . 'templates/' . $template['file'];
        $markup    = $this->generate_theme_html_markup($template);

        file_put_contents($file_path, $markup);
    }

    /**
     * Generate HTML markup for a theme template.
     *
     * @param string $template_slug The template slug.
     * @param string $zone          The zone (here, "templates").
     *
     * @return string
     */
    private function generate_theme_html_markup($template): string
    {

        $slug       = $template['slug'];
        $name       = $template['name'];
        $term_id    = $template['id'];
        $class_name = "wp2-root";

        $lock = ['move' => true, 'remove' => true];

        $option = [
            'value' => $slug,
            'label' => $name
        ];

        $metadata = [
            'name' => $name
        ];

        $attributes = [
            "className" => $class_name,
            "metadata" => [
                "name" => $name
            ],
            "blockstudio" => [
                "attributes" => [
                    "0" => "o",
                    "slug" => $slug,
                    "lock" => $lock,
                    "className" => $class_name,
                    "blockstudio" => [
                        "data" => [
                            "option" => [
                                "value" => $slug,
                                "label" => $name
                            ]
                        ]
                    ],
                    "option" => [
                        "value" => $term_id,
                        "label" => $name
                    ]
                ]
            ]
        ];

        $inner_content = $this->get_zone_template_parts($slug, 'root');

        return $this->markup_template_part($attributes, $inner_content);
    }

    /**
     * Markup for a template part block.
     *
     * @param array $attributes The block attributes.
     *
     * @return string
     */
    private function markup_template_part(array $attributes, string $inner_content = ''): string
    {
        return sprintf(
            "<!-- wp:wp2/root %s -->%s<!-- /wp:wp2/root -->",
            json_encode($attributes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            $inner_content
        );
    }

    private function get_zone_template_parts($template_slug, $zone_slug)
    {
        $template_parts_markup = '';

        $posts = get_posts([
            'post_type' => 'wp2_style_entity',
            'post_status' => 'publish',
            'tax_query' => [
                [
                    'taxonomy' => 'wp2_style_theme_zone',
                    'field'    => 'slug',
                    'terms'    => $zone_slug,
                ],
                [
                    'taxonomy' => 'wp2_style_theme_template',
                    'field'    => 'slug',
                    'terms'    => $template_slug,
                ],
            ],
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        foreach ($posts as $post) {

            $template_part = get_post_meta($post->ID, 'wp2_style_entity_template_part', true);

            $attributes = [
                'slug' => $template_part,
                'lock' => ['move' => true, 'remove' => true],
            ];

            $template_parts_markup .= sprintf(
                '<!-- wp:template-part %s --><!-- /wp:template-part -->',
                json_encode($attributes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            );
        }

        return $template_parts_markup;
    }
}