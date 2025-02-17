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
        $markup    = $this->generate_theme_html_markup($template['slug'], 'templates');

        file_put_contents($file_path, '');
    }

    /**
     * Generate HTML markup for a theme template.
     *
     * @param string $template_slug The template slug.
     * @param string $zone          The zone (here, "templates").
     *
     * @return string
     */
    private function generate_theme_html_markup(string $template_slug, string $zone): string
    {
        $part      = $zone;
        $slug      = "";
        $className = "";

        $template = [
            'value' => $template_slug,
            'label' => ucfirst(str_replace('-', ' ', $template_slug))
        ];

        $theme_zone = [
            'value' => $zone,
            'label' => ucfirst($zone)
        ];

        $theme_area = [
            'value' => $part,
            'label' => ucfirst($part)
        ];

        $lock = ['move' => true, 'remove' => true];

        $attributes = [
            'slug'       => $slug,
            'lock'       => $lock,
            'className'  => $className,
            'template'   => $template,
            'theme_zone' => $theme_zone,
            'theme_area' => $theme_area,
            'blockstudio' => [
                'attributes' => [
                    "0"         => "o",
                    'slug'      => $slug,
                    'lock'      => $lock,
                    'className' => $className,
                    'blockstudio' => [
                        'data' => [
                            'template'   => $template,
                            'theme_zone' => $theme_zone,
                            'theme_area' => $theme_area,
                        ],
                    ],
                    'template'   => $template,
                    'theme_zone' => $theme_zone,
                    'theme_area' => $theme_area,
                ],
            ],
        ];

        return $this->markup_template_part($attributes);
    }

    /**
     * Markup for a template part block.
     *
     * @param array $attributes The block attributes.
     *
     * @return string
     */
    private function markup_template_part(array $attributes): string
    {
        return sprintf(
            "<!-- wp:template-part %s /-->",
            json_encode($attributes, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }
}