<?php

/**
 * Controller class for handling areas.
 *
 * This class initializes area definitions and sets up the appropriate action hook.
 *
 * @package WP2_Style\Helpers\Area
 */

namespace WP2_Style\Helpers\Area;

/**
 * Controller class.
 */
class Controller
{

    /**
     * Type of area.
     *
     * @var string
     */
    private $kind = 'areas';

    /**
     * Array of areas.
     *
     * @var array
     */
    private $areas = [];


    /**
     * Post type for entities.
     *
     * @var string
     */
    private $option_name  = 'wp2_style_entities';

    /**
     * Area definitions.
     *
     * All area definitions have been moved here to make editing easier.
     *
     * @var array
     */
    private $area_definitions = array(
        'header' => array(
            'name'        => 'header',
            'title'       => 'Header',
            'description' => 'The header area of the site.',
        ),
        'footer' => array(
            'name'        => 'footer',
            'title'       => 'Footer',
            'description' => 'The footer area of the site.',
        ),
        'main'   => array(
            'name'        => 'main',
            'title'       => 'Main',
            'description' => 'The main area of the site.',
        ),
        'aside'  => array(
            'name'        => 'aside',
            'title'       => 'Aside',
            'description' => 'The aside area of the site.',
        ),
    );

    /**
     * Constructor.
     *
     * Initializes area definitions and adds the 'init' action hook.
     */
    public function __construct()
    {
        $this->get_areas();

        add_action('init', array($this, 'init'), 101);
    }

    /**
     * Initializes the controller.
     *
     * This method is hooked to WordPress's 'init' action.
     *
     * @return void
     */
    public function init()
    {
        $entities = get_option($this->option_name, array());
        $entities[$this->kind] = $this->areas;
        update_option($this->option_name, $entities);
    }

    /**
     * Retrieves the areas.
     *
     * @return array Array of areas.
     */
    public function get_areas()
    {
        $this->areas = $this->define_areas();
        return $this->areas;
    }

    /**
     * Defines areas based on the area definitions.
     *
     * Populates the $areas property using the definitions provided in the
     * $area_definitions property. It also sets the 'area_name' key for each area.
     *
     * @return void
     */
    private function define_areas()
    {
        $areas = [];
        foreach ($this->area_definitions as $area) {
            $area['area_name'] = $area['name'];
            $areas[$area['name']] = $area;
        }
        return $areas;
    }
}

new Controller();