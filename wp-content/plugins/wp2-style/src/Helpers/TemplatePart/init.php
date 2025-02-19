<?php

/**
 * Template Part Controller.
 *
 * This class generates template parts by combining templates and zone areas,
 * and retrieves the associated layout for each template part. A filter is available
 * to modify the generated data.
 *
 * @package WP2_Style\Helpers\TemplatePart
 */

namespace WP2_Style\Helpers\TemplatePart;

use WP2_Style\Helpers\Template\Controller as TemplateController;
use WP2_Style\Helpers\Zone\Controller as ZoneController;
use WP2_Style\Helpers\Area\Controller as AreaController;
use WP2_Style\Helpers\Layout\Controller as LayoutController;

class Controller
{

    /**
     * Type of element.
     *
     * @var string
     */
    private $kind = 'template_parts';

    /**
     * Array of templates.
     *
     * @var array
     */
    private $templates = array();

    /**
     * Array of zone areas.
     *
     * @var array
     */
    private $zone_areas = array();

    /**
     * Array of combined template parts.
     *
     * @var array
     */
    private $template_parts = array();

    /**
     * Option name for entities.
     *
     * @var string
     */
    private $option_name  = 'wp2_style_entities';

    /**
     * Constructor.
     *
     * Registers the 'init' action hook.
     */
    public function __construct()
    {
        add_action('init', array($this, 'init'), 101);
    }

    /**
     * Initializes the controller.
     *
     * Retrieves the saved zones and areas from our option,
     * builds the combined zone areas and template parts,
     * then updates the option.
     *
     * @return void
     */
    public function init()
    {
        // Retrieve the stored entities.
        $entities = get_option($this->option_name, array());

        // Use existing zones and areas if they are stored; otherwise, retrieve via controllers.
        $zones = isset($entities['zones']) ? $entities['zones'] : $this->get_zones();
        $areas = isset($entities['areas']) ? $entities['areas'] : $this->get_areas();

        // Generate the zone areas from zones and areas.
        $this->zone_areas = $this->generate_zone_areas_from($zones, $areas);

        // Generate template parts by combining templates with zone areas.
        $this->template_parts = $this->generate_template_parts();

        // Store the template parts in our option.
        $entities[$this->kind] = $this->template_parts;
        update_option($this->option_name, $entities);
    }

    /**
     * Retrieves the combined template parts.
     *
     * @return array Array of template parts.
     */
    public function get_template_parts()
    {
        return $this->template_parts;
    }

    /**
     * Retrieves all templates.
     *
     * @return array Array of templates.
     */
    public function get_templates()
    {
        $template_controller = new TemplateController();
        return $template_controller->get_templates();
    }

    /**
     * Generates combined template parts by combining templates and zone areas.
     *
     * @return array Array of template parts.
     */
    private function generate_template_parts()
    {
        $templates   = $this->get_templates();
        $zone_areas  = $this->zone_areas;
        $template_parts = array();

        foreach ($templates as $template) {
            foreach ($zone_areas as $zone_area) {
                $template_parts[] = $this->generate_template_part($template, $zone_area);
            }
        }

        return $template_parts;
    }

    /**
     * Generates a single combined template part.
     *
     * Combines a template and a zone area to create a template part and retrieves
     * the associated layout.
     *
     * @param array $template  Template data.
     * @param array $zone_area Zone area data.
     * @return array Combined template part data.
     */
    private function generate_template_part($template, $zone_area)
    {
        $name        = $zone_area['name'] . '-part-' . $template['name'];
        $title       = $zone_area['title'] . ': ' . $template['title'];
        $description = 'The ' . $template['title'] . ' ' . $zone_area['title'] . ' template part of the site.';

        $template_name  = $template['name'];
        $zone_area_name = $zone_area['name'];
        $zone_name      = $zone_area['zone_name'];
        $area_name      = $zone_area['area_name'];

        $layout = $this->get_layout_by_template_name($template_name);
        $layout_name = isset($layout['name']) ? $layout['name'] : 'undefined';

        $template_part = array(
            'title'         => $title,
            'description'   => $description,
            'template_part' => $name,
            'template'      => $template_name,
            'zone_area'     => $zone_area_name,
            'zone'          => $zone_name,
            'area'          => $area_name,
            'layout'        => $layout_name,
        );

        /**
         * Filter the generated template part.
         *
         * Allows developers to modify the template part data, such as title,
         * description, and other properties.
         *
         * @param array $template_part The template part data.
         * @param array $template      The original template data.
         * @param array $zone_area     The original zone area data.
         * @param array $layout        The layout data associated with the template.
         */
        return apply_filters('wp2_style_template_part', $template_part, $template, $zone_area, $layout);
    }

    /**
     * Retrieves the layout associated with a given template name.
     *
     * @param string $template_name Template name.
     * @return array|null Layout data if found, or null.
     */
    public function get_layout_by_template_name($template_name)
    {
        $layout_controller = new LayoutController();
        $layout_controller->init(); // Ensure layouts are defined.
        return $layout_controller->get_template_layout($template_name);
    }

    /**
     * Retrieves zones.
     *
     * Uses stored option data if available, or instantiates the ZoneController.
     *
     * @return array Array of zones.
     */
    public function get_zones()
    {
        $entities = get_option($this->option_name, array());
        if (isset($entities['zones'])) {
            return $entities['zones'];
        }
        $zone_controller = new ZoneController();
        return $zone_controller->get_zones();
    }

    /**
     * Retrieves areas.
     *
     * Uses stored option data if available, or instantiates the AreaController.
     *
     * @return array Array of areas.
     */
    public function get_areas()
    {
        $entities = get_option($this->option_name, array());
        if (isset($entities['areas'])) {
            return $entities['areas'];
        }
        $area_controller = new AreaController();
        return $area_controller->get_areas();
    }

    /**
     * Generates combined zone areas from zones and areas.
     *
     * Iterates over each zone and area to generate a combined zone area.
     *
     * @param array $zones Array of zones.
     * @param array $areas Array of areas.
     * @return array Array of zone areas.
     */
    private function generate_zone_areas_from($zones, $areas)
    {
        $zone_areas = array();

        foreach ($zones as $zone) {
            foreach ($areas as $area) {
                $zone_areas[] = $this->generate_zone_area($zone, $area);
            }
        }

        return $zone_areas;
    }

    /**
     * Generates a single combined zone area.
     *
     * Combines a zone and an area to create a zone area and allows modification
     * through the 'wp2_style_zone_area' filter.
     *
     * @param array $zone Zone data.
     * @param array $area Area data.
     * @return array Combined zone area.
     */
    private function generate_zone_area($zone, $area)
    {
        $name        = $zone['name'] . '-' . $area['name'];
        $title       = $zone['title'] . ' ' . $area['title'];
        $description = 'The ' . $zone['title'] . ' ' . $area['title'] . ' zone area of the site.';
        $zone_name   = $zone['name'];
        $area_name   = $area['name'];

        $zone_area = array(
            'name'        => $name,
            'title'       => $title,
            'description' => $description,
            'zone_name'   => $zone_name,
            'area_name'   => $area_name,
        );

        /**
         * Filter the generated zone area.
         *
         * Allows developers to modify the zone area data, such as the title
         * and description.
         *
         * @param array $zone_area The zone area data.
         * @param array $zone      The original zone data.
         * @param array $area      The original area data.
         */
        return apply_filters('wp2_style_zone_area', $zone_area, $zone, $area);
    }
}

new Controller();