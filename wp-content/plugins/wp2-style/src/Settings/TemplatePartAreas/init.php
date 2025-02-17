<?php
// Path: wp-content/plugins/wp2-style/src/Settings/TemplatePartAreas/init.php
namespace WP2_Style\Settings\TemplatePartAreas;

class Controller
{
    /**
     * Holds all template part definitions.
     *
     * @var array
     */
    private $template_parts = [];

    /**
     * Holds all template area definitions.
     *
     * @var array
     */
    private $template_areas = [];

    /**
     * The meta field (or taxonomy) used to retrieve template parts/areas.
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
        $this->init();
    }

    /**
     * Initializes our filters.
     *
     * @return void
     */
    public function init()
    {

        add_filter('wp_theme_json_data_theme', [$this, 'filter_theme_json_theme']);

        add_filter('default_wp_template_part_areas', [$this, 'filter_default_wp_template_part_areas'], 30);
    }


    public function filter_theme_json_theme($theme_json)
    {
        $current_data = $theme_json->get_data();

        $new_data = [
            'version' => 3,
            'templateParts' => [
                [
                    'name' => 'content-header-part-attachment',
                    'area' => 'content-header',
                    'title' => 'Content Header: Attachment',
                ]
            ],
        ];

        return $theme_json->update_with($new_data);
    }

    public function filter_default_wp_template_part_areas($default_area_definitions)
    {
        $new_areas = [
            [
                'area'        =>  'content-header',
                'label'       => _x('Content Header', 'Template part area'),
                'description' => __(
                    'The header area of the content.',
                    'wp2-style'
                ),
                'icon'        => 'layout',
                'area_tag'    => 'div',
            ],
        ];

        $updated = array_merge($default_area_definitions, $new_areas);

        return $updated;
    }
}

new Controller();