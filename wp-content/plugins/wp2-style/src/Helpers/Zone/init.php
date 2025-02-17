<?php

/**
 * Controller class for handling zones.
 *
 * This class initializes zone definitions and sets up the appropriate actions.
 *
 * @package WP2_Style\Helpers\Zone
 */

namespace WP2_Style\Helpers\Zone;

/**
 * Controller class.
 */
class Controller
{

    /**
     * Type of zone.
     *
     * @var string
     */
    private $kind = 'zones';

    /**
     * Array of zones.
     *
     * @var array
     */
    private $zones = [];


    /**
     * Post type for entities.
     *
     * @var string
     */
    private $option_name  = 'wp2_style_entities';

    /**
     * Zone definitions.
     *
     * All zone definitions have been moved here to make editing easier.
     *
     * @var array
     */
    private $zone_definitions = array(
        'root'    => array(
            'name'        => 'root',
            'title'       => 'Root',
            'description' => 'The root zone of the site.',
        ),
        'content' => array(
            'name'        => 'content',
            'title'       => 'Content',
            'description' => 'The content zone of the site.',
        ),
        'focus'   => array(
            'name'        => 'focus',
            'title'       => 'Focus',
            'description' => 'The focus zone of the site.',
        ),
        'primary' => array(
            'name'        => 'primary',
            'title'       => 'Primary',
            'description' => 'The primary zone of the site.',
        ),
    );


    /**
     * Constructor.
     *
     * Initializes area definitions and adds the 'init' action hook.
     */
    public function __construct()
    {
        $this->get_zones();

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
        $entities[$this->kind] = $this->zones;
        update_option($this->option_name, $entities);
    }

    /**
     * Retrieves the zones.
     *
     * @return array Array of zones.
     */
    public function get_zones()
    {
        $this->zones = $this->define_zones();
        return $this->zones;
    }

    /**
     * Defines zones based on the area definitions.
     *
     * Populates the $zones property using the definitions provided in the
     * $area_definitions property. It also sets the 'area_name' key for each area.
     *
     * @return void
     */
    private function define_zones()
    {
        $zones = [];
        foreach ($this->zone_definitions as $zone) {
            $zone['zone_name'] = $zone['name'];
            $zones[$zone['name']] = $zone;
        }
        return $zones;
    }
}

$zone_controller = new Controller();
$zone_controller->init();