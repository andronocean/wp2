<?php

namespace WP2_Style\Syncs\TemplatePart;

class Controller
{

    /**
     * Post type to query for template parts.
     *
     * @var string
     */
    private string $post_type = 'wp2_style_entity';

    /**
     * Holds the template parts.
     *
     * @var array
     */
    private $template_parts = [];

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
     * Initializes the template part sync.
     *
     * @return void
     */
    public function init(): void
    {
        $this->execute_sync();
    }

    /**
     * Execute the template part sync process.
     *
     * Queries the posts and creates HTML files in the theme's
     * /parts directory.
     *
     * @return void
     */
    public function execute_sync(): void
    {
        $this->generate_theme_template_part_files();
    }

    /**
     * Generate theme template part files.
     *
     * @return void
     */
    private function generate_theme_template_part_files(): void
    {
        $parts = $this->get_theme_template_parts();

        foreach ($parts as $part) {
            $this->create_theme_template_part_file($part);
        }
    }

    /**
     * Get theme template parts by querying posts.
     *
     * @return array
     */
    private function get_theme_template_parts(): array
    {
        $posts = get_posts([
            'post_type'   => $this->post_type,
            'post_status' => 'publish',
            'numberposts' => -1,
        ]);

        $template_parts = [];

        if (! empty($posts)) {
            foreach ($posts as $post) {
                $template_parts[] = [
                    'slug'              => $post->post_name,
                    'name'              => $post->post_title,
                ];

                $meta = get_post_meta($post->ID);
                foreach ($meta as $key => $value) {
                    if (strpos($key, 'wp2_style_entity_') === 0) {
                        $key = str_replace('wp2_style_entity_', '', $key);
                        $template_parts[count($template_parts) - 1][$key] = $value[0];
                    }
                }
            }
        }

        $this->template_parts = $template_parts;
        return $template_parts;
    }

    /**
     * Create a theme template part file.
     *
     * @param array $part Template part data.
     *
     * @return void
     */
    private function create_theme_template_part_file(array $part): void
    {
        $filename         = $part['template_part'];

        $file_path        = trailingslashit(get_template_directory()) . 'parts/' . $filename . '.html';
        $class_name       = $part['slug'];
        $wrapper_name     = $part['name'];

        $this->create_part_file($file_path, $part);
    }

    /**
     * Create a part file.
     *
     * @param string $file_path     The file path to save the HTML.
     * @param string $template_name The name of the template.
     * @param string $class_name    CSS class name.
     * @param string $wrapper_name  Wrapper metadata name.
     * @param string $template      The template content.
     * @param string $template_zone The zone/attributes.
     *
     * @return void
     */
    private function create_part_file(
        string $file_path,
        array $part
    ): void {
        $class_name     = $part['slug'];
        $wrapper_name   = $part['name'];
        $template       = $part['template'];
        $template_zone  = $part['zone_area'];

        $attributes = json_encode([
            'zone_area' => $template_zone,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $group_attributes = json_encode([
            'lock'      => ['move' => true, 'remove' => true],
            'className' => $class_name,
            'metadata'  => ['name' => $wrapper_name],
            'layout'    => ['type' => 'constrained'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $inner_content = $this->generate_part_content($template, $attributes);

        $wrapper = sprintf(
            '<!-- wp:group %s --><div class="wp-block-group">%s</div><!-- /wp:group -->',
            $group_attributes,
            $inner_content
        );

        file_put_contents($file_path, '');
    }

    /**
     * Generate inner content for a template part.
     *
     * @param string $template   The template content.
     * @param string $attributes The attributes to inject.
     *
     * @return string
     */
    private function generate_part_content(string $template, string $attributes): string
    {
        return sprintf($template, $attributes);
    }
}