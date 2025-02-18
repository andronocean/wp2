<?php
// Path: wp-content/plugins/wp2-style/src/Settings/TemplatePartAreas/init.php
namespace WP2_Style\Settings\TemplatePartAreas;

class Controller
{

    /**
     * Text domain for translations.
     *
     * @var string
     */
    private $text_domain = 'wp2-style';

    /**
     * Holds all template part definitions.
     *
     * @var array
     */
    private $template_parts = [];

    /**
     * The taxonomy used to retrieve template parts/areas.
     *
     * @var string
     */
    private $taxonomy = 'wp2_style_theme_part';

    /**
     * The post type that holds template parts.
     *
     * @var string
     */
    private $post_type = 'wp2_style_entity';

    public function __construct()
    {
        // Preload template parts immediately.
        $this->template_parts = $this->get_template_parts();
        // Do NOT preload template areas here;
        // we'll load them later in the filter callback when the taxonomy is registered.

        // Register filters early.
        add_filter('default_wp_template_part_areas', [$this, 'filter_default_wp_template_part_areas'], 1);
        add_filter('wp_theme_json_data_theme', [$this, 'filter_theme_json_theme'], 1);
    }

    /**
     * Update theme.json data with template parts.
     *
     * @param object $theme_json
     * @return object
     */
    public function filter_theme_json_theme($theme_json)
    {
        $data = $theme_json->get_data();
        $data['version'] = 3;
        $data['templateParts'] = $this->template_parts;
        return $theme_json->update_with($data);
    }

    /**
     * Merge the default template part areas with our custom areas.
     * We now load custom areas on-demand to ensure taxonomy terms are available.
     *
     * @param array $default_area_definitions
     * @return array
     */
    public function filter_default_wp_template_part_areas($default_area_definitions)
    {
        // Lazy-load the custom areas.
        $custom_areas = $this->get_template_part_areas();

        // Append each custom area as a numerically indexed array item.
        foreach ($custom_areas as $area) {
            if (isset($area['area'])) {
                $default_area_definitions[] = array(
                    'area'        => $area['area'],
                    'area_tag'    => $area['area_tag'],
                    'label'       => $area['label'],
                    'description' => $area['description'],
                    'icon'        => $area['icon'],
                );
            }
        }

        return $default_area_definitions;
    }

    /**
     * Retrieves template parts from posts.
     *
     * @return array
     */
    private function get_template_parts()
    {
        $posts = get_posts([
            'post_type'   => $this->post_type,
            'numberposts' => -1,
            'post_status' => 'any',
        ]);

        $template_parts = [];
        foreach ($posts as $post) {
            $area          = get_post_meta($post->ID, 'wp2_style_entity_zone_area', true);
            $template_part = get_post_meta($post->ID, 'wp2_style_entity_template_part', true);
            $template_parts[] = [
                'area'  => $area,
                'name'  => $template_part,
                'title' => $post->post_title,
            ];
        }
        return $template_parts;
    }

    /**
     * Retrieves template part areas from taxonomy terms.
     * We explicitly set 'fields' => 'all' to ensure we get WP_Term objects.
     *
     * @return array
     */
    private function get_template_part_areas()
    {
        $terms = get_terms([
            'taxonomy'   => $this->taxonomy,
            'hide_empty' => false,
            'fields'     => 'all',
        ]);

        // Debug: log the terms returned.
        if (is_wp_error($terms)) {
            error_log('Error retrieving terms: ' . $terms->get_error_message());
        } elseif (empty($terms)) {
            error_log('No terms found for taxonomy: ' . $this->taxonomy);
        }

        $areas = [];
        if (!is_wp_error($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                $areas[] = [
                    'area'        => $term->slug,
                    'label'       => _x($term->name, 'Template part area'),
                    'description' => __($term->description, $this->text_domain),
                    'icon'        => 'layout',
                    'area_tag'    => 'div',
                ];
            }
        }
        return $areas;
    }
}

// Instantiate the controller (make sure this runs after your taxonomy is registered).
new Controller();